<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class updatepost_controller extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SUPABASE CONNECTION
    |--------------------------------------------------------------------------
    */

    private function supabase()
    {
        $url = rtrim(env('SUPABASE_URL'), '/');
        $key = env('SUPABASE_SERVICE_KEY');

        if (!$url || !$key) {
            throw new \RuntimeException(
                'Supabase configuration is missing.'
            );
        }

        return Http::withHeaders([
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->baseUrl($url . '/rest/v1/');
    }

    /*
    |--------------------------------------------------------------------------
    | GET AUTHENTICATED NGO STAFF ACCOUNT
    |--------------------------------------------------------------------------
    */

    private function getNgoStaff()
    {
        $userId = session('user_id');

        if (!$userId) {
            return [
                'error' => redirect('/login')
                    ->with('error', 'Please log in first.'),
            ];
        }

        $response = $this->supabase()->get('accounts', [
            'select' => 'id,roles,ngo_id',
            'id' => 'eq.' . (int) $userId,
        ]);

        if (!$response->successful()) {
            Log::error('Update posts: account lookup failed.', [
                'user_id' => $userId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'error' => redirect()->back()
                    ->with('error', 'Unable to verify your account.'),
            ];
        }

        $account = collect($response->json())->first();

        if (!$account) {
            return [
                'error' => redirect('/login')
                    ->with('error', 'Account not found.'),
            ];
        }

        // SUHAY roles:
        // 0 = NGO Head
        // 1 = Volunteer Manager
        // 2 = Donation Manager

        if (!in_array((int) $account['roles'], [0, 1, 2], true)) {
            return [
                'error' => redirect()->back()
                    ->with('error', 'You are not authorized to manage NGO updates.'),
            ];
        }

        if (empty($account['ngo_id'])) {
            return [
                'error' => redirect()->back()
                    ->with('error', 'Your account has no assigned NGO.'),
            ];
        }

        return [
            'account' => $account,
            'user_id' => (int) $account['id'],
            'ngo_id' => (int) $account['ngo_id'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE POST / PUBLISH / SAVE DRAFT
    |--------------------------------------------------------------------------
    |
    | Status:
    | 0 = Draft
    | 1 = Published
    | 2 = Archived
    |
    */

   
public function store(Request $request)
{
    $staff = $this->getNgoStaff();

    if (isset($staff['error'])) {
        return $staff['error'];
    }

    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string', 'max:5000'],
        'status' => ['required', 'integer', 'in:0,1'],
        'image' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:5120',
        ],
    ]);

    $postId = null;
    $uploadedPath = null;

    try {
        // STEP 1: CREATE THE POST FIRST
        $postResponse = $this->supabase()
            ->withHeaders([
                'Prefer' => 'return=representation',
            ])
            ->post('update_posts', [
                'ngo_id' => $staff['ngo_id'],
                'title' => trim($validated['title']),
                'description' => trim($validated['description']),
                'status' => (int) $validated['status'],
                'posted_by_id' => $staff['user_id'],
            ]);

        if (!$postResponse->successful()) {
            Log::error('Update post creation failed.', [
                'status' => $postResponse->status(),
                'body' => $postResponse->body(),
            ]);

            return redirect()->back()
                ->withInput($request->except('_token', 'image'))
                ->with('error', 'Unable to save the NGO update.');
        }

        // Get the newly created post ID.
        $createdPost = collect($postResponse->json())->first();

        if (!$createdPost || empty($createdPost['id'])) {
            Log::error('Post created but its ID was not returned.', [
                'response' => $postResponse->body(),
            ]);

            return redirect()->back()
                ->with('error', 'The post was saved, but its ID could not be retrieved.');
        }

        $postId = (int) $createdPost['id'];

        // STEP 2: UPLOAD IMAGE IF PROVIDED
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $extension = strtolower($image->getClientOriginalExtension());
            $filename = 'post_' . $postId . '_' . bin2hex(random_bytes(8))
                . '.' . $extension;

            $uploadedPath = $filename;

            $supabaseUrl = rtrim(env('SUPABASE_URL'), '/');
            $supabaseKey = env('SUPABASE_SERVICE_KEY');

            $uploadResponse = Http::withHeaders([
                'apikey' => $supabaseKey,
                'Authorization' => 'Bearer ' . $supabaseKey,
                'Content-Type' => $image->getMimeType(),
                'x-upsert' => 'false',
            ])->withBody(
                file_get_contents($image->getRealPath()),
                $image->getMimeType()
            )->post(
                $supabaseUrl . '/storage/v1/object/posts/' . $filename
            );

            if (!$uploadResponse->successful()) {
                Log::error('Post image upload failed.', [
                    'post_id' => $postId,
                    'status' => $uploadResponse->status(),
                    'body' => $uploadResponse->body(),
                ]);

                return redirect()->back()
                    ->with('warning', 'The post was saved, but its image could not be uploaded.');
            }

            // STEP 3: GENERATE THE PUBLIC IMAGE URL
            $imageUrl = $supabaseUrl
                . '/storage/v1/object/public/posts/'
                . $filename;

            // STEP 4: SAVE IMAGE URL TO media_table
            $mediaResponse = $this->supabase()
                ->withHeaders([
                    'Prefer' => 'return=representation',
                ])
                ->post('media_table', [
                    'key_id' => $postId,
                    'type' => 'POST',
                    'path' => $imageUrl,
                ]);

            if (!$mediaResponse->successful()) {
                Log::error('Post image media record creation failed.', [
                    'post_id' => $postId,
                    'image_url' => $imageUrl,
                    'status' => $mediaResponse->status(),
                    'body' => $mediaResponse->body(),
                ]);

                return redirect()->back()
                    ->with('warning', 'The post and image were saved, but the media record could not be created.');
            }
        }

        $status = (int) $validated['status'];

        return redirect()->back()->with(
            'success',
            $status === 0
                ? 'NGO update saved as draft.'
                : 'NGO update published successfully.'
        );

    } catch (\Throwable $e) {
        Log::error('Update post creation exception.', [
            'post_id' => $postId,
            'message' => $e->getMessage(),
        ]);

        return redirect()->back()
            ->with('error', 'An unexpected error occurred while saving the post.');
    }
}

    /*
    |--------------------------------------------------------------------------
    | UPDATE EXISTING POST
    |--------------------------------------------------------------------------
    */

   
public function update(Request $request, $id)
{
    $staff = $this->getNgoStaff();

    if (isset($staff['error'])) {
        return $staff['error'];
    }

    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string', 'max:5000'],
    ]);

    try {
        $response = $this->supabase()
            ->withHeaders([
                'Prefer' => 'return=representation',
            ])
            ->patch(
                'update_posts?id=eq.' . (int) $id
                    . '&ngo_id=eq.' . $staff['ngo_id'],
                [
                    'title' => trim($validated['title']),
                    'description' => trim($validated['description']),
                ]
            );

        if (!$response->successful()) {
            Log::error('Update post edit failed.', [
                'post_id' => $id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return redirect()->back()
                ->with('error', 'Unable to update the NGO post.');
        }

        if (empty($response->json())) {
            return redirect()->back()
                ->with('error', 'Post not found or you do not own this post.');
        }

        return redirect()->back()
            ->with('success', 'NGO update edited successfully.');

    } catch (\Throwable $e) {
        Log::error('Update post edit exception.', [
            'post_id' => $id,
            'message' => $e->getMessage(),
        ]);

        return redirect()->back()
            ->with('error', 'An unexpected error occurred.');
    }
}



    public function changeStatus(Request $request, $id)
    {
        $staff = $this->getNgoStaff();

        if (isset($staff['error'])) {
            return $staff['error'];
        }

        $validated = $request->validate([
            'status' => ['required', 'integer', 'in:0,1,2'],
        ]);

        $newStatus = (int) $validated['status'];

        try {
            $response = $this->supabase()
                ->withHeaders([
                    'Prefer' => 'return=representation',
                ])
                ->patch(
                    'update_posts?id=eq.' . (int) $id
                        . '&ngo_id=eq.' . $staff['ngo_id'],
                    [
                        'status' => $newStatus,
                    ]
                );

            if (!$response->successful()) {
                Log::error('Update post status change failed.', [
                    'post_id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return redirect()->back()
                    ->with('error', 'Unable to change the post status.');
            }

            if (empty($response->json())) {
                return redirect()->back()
                    ->with('error', 'Post not found or you do not own this post.');
            }

            $messages = [
                0 => 'Post saved as draft.',
                1 => 'Post published successfully.',
                2 => 'Post archived successfully.',
            ];

            return redirect()->back()
                ->with('success', $messages[$newStatus]);

        } catch (\Throwable $e) {
            Log::error('Update post status exception.', [
                'post_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'An unexpected error occurred.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE POST
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $staff = $this->getNgoStaff();

        if (isset($staff['error'])) {
            return $staff['error'];
        }

        try {
            $response = $this->supabase()
                ->withHeaders([
                    'Prefer' => 'return=representation',
                ])
                ->delete(
                    'update_posts?id=eq.' . (int) $id
                        . '&ngo_id=eq.' . $staff['ngo_id']
                );

            if (!$response->successful()) {
                Log::error('Update post deletion failed.', [
                    'post_id' => $id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return redirect()->back()
                    ->with('error', 'Unable to delete the NGO post.');
            }

            if (empty($response->json())) {
                return redirect()->back()
                    ->with('error', 'Post not found or you do not own this post.');
            }

            return redirect()->back()
                ->with('success', 'NGO update deleted successfully.');

        } catch (\Throwable $e) {
            Log::error('Update post deletion exception.', [
                'post_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'An unexpected error occurred.');
        }
    }
}
