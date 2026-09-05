<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate | SUHAY</title>

    <link rel="icon" href="{{ asset('images/suhayLogo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }

        .hover-scale {
            transition: all 0.25s ease;
        }
        .hover-scale:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        html { scroll-behavior: smooth; }

        .modal-enter {
            animation: zoomFadeIn 0.3s ease forwards;
        }

        .modal-exit {
            animation: zoomFadeOut 0.25s ease forwards;
        }

        @keyframes zoomFadeIn {
            0% { opacity: 0; transform: scale(0.7); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes zoomFadeOut {
            0% { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(0.7); }
        }
    </style>
</head>

<body class="bg-gray-100">

@include('components.navbar')

<!-- HERO -->
<section class="relative h-[280px] w-full" data-aos="fade-up" data-aos-duration="1000">
    <img src="{{ asset('images/hero.jpg') }}" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-[#0e243a]/80"></div>

    <div class="absolute inset-0 flex items-center px-10">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white">Donate Today</h1>
            <p class="text-[#f2c94c] text-lg mt-2">Your Peso Matters</p>
        </div>
    </div>
</section>

<!-- NGO LIST -->
<section class="py-16 bg-white" data-aos="fade-up" data-aos-duration="800">
<div class="max-w-4xl mx-auto px-6">

    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-[#0e243a]">Verified Partners</h2>
        <p class="text-gray-600">Support our trusted NGOs</p>
    </div>

    <div class="space-y-5">

    @foreach($ngos as $ngo)
    <div onclick="openModal({{ $ngo['id'] }})"
        class="bg-white border-2 border-[#0e243a] rounded-2xl p-8 md:p-10 flex items-center justify-between hover-scale cursor-pointer">

        <div class="flex items-center gap-6">

            <div class="w-16 h-16 md:w-20 md:h-20 flex items-center justify-center">
                <!-- Updated logo logic -->
                @if(!empty($ngo['logo']))
                    <img src="https://vqywnoljhhcnybzbvhhh.supabase.co/storage/v1/object/public/profile-pictures/{{ $ngo['logo'] }}"
                        class="w-full h-full object-contain rounded-lg" 
                        alt="{{ $ngo['name'] }} Logo"
                        onerror="this.src='{{ asset('images/suhayLogo.png') }}'">
                @else
                    <img src="{{ asset('images/suhayLogo.png') }}"
                        class="w-full h-full object-contain rounded-lg"
                        alt="Suhay Logo">
                @endif
            </div>

            <div>
                <h3 class="font-bold text-[#0e243a] text-xl md:text-2xl">
                    {{ $ngo['name'] }}
                </h3>
                <p class="text-base text-gray-600 mt-1">
                    {{ $ngo['contact_number'] ?? 'No contact' }} • {{ $ngo['address'] ?? 'No address' }}
                </p>
            </div>
        </div>

        <button class="bg-[#f2c94c] px-6 py-3 rounded-xl font-semibold text-[#0e243a] text-base hover:bg-[#d39a11] transition">
            View Details
        </button>
    </div>
    @endforeach

    </div>
</div>
</section>

<!-- MODAL -->
<div id="ngoModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    <div id="modalContent"
        class="bg-white w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-xl">
    </div>

</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>

AOS.init({
    duration: 800,
    once: false,
    offset: 100,
    easing: 'ease-out-cubic'
});

// DATA FROM API
const ngoData = @json($ngos);

// OPEN MODAL
function openModal(id) {
    const ngo = ngoData.find(n => n.id == id) || {};

    document.getElementById('modalContent').innerHTML = `
    <div class="p-6 md:p-8 relative">

    <div class="text-center mb-6">
        <img src="${ngo.logo ? ngo.logo : '{{ asset('images/suhayLogo.png') }}'}" 
            class="h-16 mx-auto mb-2 object-contain">
        <h2 class="text-2xl font-bold text-[#0e243a]">${ngo.name || ''}</h2>
        <p class="text-sm text-gray-600 mt-1">${ngo.description || ''}</p>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-6 text-sm bg-gray-50 p-5 rounded-xl">

        <!-- CONTACT -->
        <div class="flex flex-col items-center text-center gap-2">
            <div class="flex items-center gap-2 font-semibold text-[#0e243a]">
                <img src="{{ asset('images/VolunteerIcons/VPhone.png') }}" class="w-4 h-4">
                Contact
            </div>
            <p>${ngo.contact_number || 'N/A'}</p>
        </div>

        <!-- LOCATION -->
        <div class="flex flex-col items-center text-center gap-2">
            <div class="flex items-center gap-2 font-semibold text-[#0e243a]">
                <img src="{{ asset('images/VolunteerIcons/VLocation.png') }}" class="w-4 h-4">
                Location
            </div>
            <p>${ngo.address || 'N/A'}</p>
        </div>

    </div>

    <div class="grid md:grid-cols-2 gap-6">

        <!-- GCASH -->
        <div class="border rounded-xl p-5">
            <h3 class="font-bold text-[#0e243a] mb-6 text-center">GCASH</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-gray-600 mb-1">Account Name:</span>
                        <span class="font-medium block">${ngo.name || 'N/A'}</span>
                    </div>
                    <div>
                        <span class="block text-gray-600 mb-1">Account Number:</span>
                        <span class="font-medium block">${ngo.gcash || 'N/A'}</span>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg border text-center">
                    <img src="{{ asset('images/suhayLogo.png') }}" class="w-full max-h-40 object-contain mx-auto block rounded">
                </div>
            </div>
        </div>

        <!-- BANK -->
        <div class="border rounded-xl p-5">
            <h3 class="font-bold text-[#0e243a] mb-6 text-center">BANK</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="block text-gray-600 mb-1">Account Name:</span>
                        <span class="font-medium block">${ngo.name || 'N/A'}</span>
                    </div>
                    <div>
                        <span class="block text-gray-600 mb-1">Bank:</span>
                        <span class="font-medium block">${ngo.bank_account || 'N/A'}</span>
                    </div>
                    <div>
                        <span class="block text-gray-600 mb-1">Account Number:</span>
                        <span class="font-medium block">${ngo.bank_number || 'N/A'}</span>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg border text-center">
                    <img src="{{ asset('images/suhayLogo.png') }}" class="w-full max-h-40 object-contain mx-auto block rounded">
                </div>
            </div>
        </div>

    </div>

    <div class="text-center mt-6">
        <button onclick="closeModal()" 
            class="bg-[#f2c94c] px-8 py-2 rounded-full font-bold text-[#0e243a] hover:bg-[#d39a11] transition">
            Close
        </button>
    </div>

</div>
`;

    const modal = document.getElementById('ngoModal');
    const content = document.getElementById('modalContent');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    content.classList.remove('modal-exit');
    content.classList.add('modal-enter');

    document.body.style.overflow = 'hidden';
}

// CLOSE
function closeModal() {
    const modal = document.getElementById('ngoModal');
    const content = document.getElementById('modalContent');

    content.classList.remove('modal-enter');
    content.classList.add('modal-exit');

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 250);
}

// FIXED CLICK OUTSIDE
document.getElementById('ngoModal').addEventListener('click', function(e) {
    if (e.target.id === 'ngoModal') {
        closeModal();
    }
});
</script>

</body>
</html>