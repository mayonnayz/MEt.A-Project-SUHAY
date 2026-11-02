<?php
use App\Models\Program;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    
    public function assignVolunteer($id)
    {
        $program = Program::findOrFail($id);

        $volunteers = Volunteer::all();
        $assigned = $program->volunteers; 

        return view('assign_volunteer', compact('program', 'volunteers', 'assigned'));
    }

   
    public function assign(Request $request)
    {
        $program = Program::findOrFail($request->program_id);

        $program->volunteers()->attach($request->volunteer_id);

        return back()->with('success', 'Volunteer assigned successfully!');
    }

  
    public function remove(Request $request)
    {
        $program = Program::findOrFail($request->program_id);

        $program->volunteers()->detach($request->volunteer_id);

        return back()->with('success', 'Volunteer removed!');
    }
}