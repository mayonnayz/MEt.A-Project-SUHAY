<!DOCTYPE html>
<html>
    <head>
    <title>Volunteer Application Form</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>
    <div class="back-container">
        <a href="/volunteer-page" class="back-btn-fixed" id="backBtn">Back</a>
    </div>
    
    <!-- LOGO -->
    <div class="logo-container">
        <img src="{{ asset('images/suhayLogo.png') }}" alt="logo">
    </div>

    <h2>Volunteer Application Form</h2>


<form id="volunteerForm" method="POST" action="#">
    @csrf

    <!-- 🔹 PERSONAL INFORMATION -->
    <h3>Personal Information</h3>

    <label>Full Name:</label><br>
    <input type="text" name="full_name" required><br><br>

    <label>Email Address:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contact Number:</label><br>
    <input type="text" name="contact" required><br><br>

   <h3>Address Information</h3>

        <label>Province:</label><br>
        <input type="text" name="province" required><br><br>

        <label>City / Municipality:</label><br>
        <input type="text" name="city" required><br><br>

        <label>Barangay:</label><br>
        <input type="text" name="barangay" required><br><br>

        <label>House Number / Street:</label><br>
        <input type="text" name="house_number" required><br><br>

        <label>Additional Address Details (optional):</label><br>
        <textarea name="address_notes" placeholder="Apartment, landmark, etc."></textarea><br><br>

    <label>Date of Birth:</label><br>
    <input type="date" name="dob" max="{{ date('Y-m-d') }}"><br><br>

    <label>Gender:</label><br>
    <select name="gender">
        <option value="">Select</option>
        <option>Male</option>
        <option>Female</option>
        <option>Prefer not to say</option>
    </select><br><br>

<h3>Availability</h3>

<label>Select your available schedule (drag to select):</label>

<input type="hidden" name="availability" id="availability">

<table class="schedule-table" id="schedule">
    <thead>
        <tr>
            <th>Day</th>
            <th>Morning</th>
            <th>Afternoon</th>
            <th>Evening</th>
        </tr>
    </thead>
    <tbody>
        @php
            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            $times = ['Morning','Afternoon','Evening'];
        @endphp

        @foreach($days as $day)
        <tr>
            <td>{{ $day }}</td>
            @foreach($times as $time)
                <td class="slot" data-value="{{ $day }}-{{ $time }}"></td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>


    <!-- 🔹 VOLUNTEERING QUESTIONS -->
    <h3>About You</h3>

    <label>Why do you want to volunteer?</label><br>
    <textarea name="reason" required></textarea><br><br>

    <label>Do you have previous volunteer experience?</label><br>
    <select name="experience">
        <option value="">Select</option>
        <option>Yes</option>
        <option>No</option>
    </select><br><br>

    <label>If yes, please describe:</label><br>
    <textarea name="experience_details"></textarea><br><br>

    <!-- 🔹 SKILLS & INTERESTS -->
    <h3>Skills & Interests</h3>

    <label>Select your skills:</label><br>
    <input type="checkbox" name="skills[]" value="Teaching"> Teaching<br>
    <input type="checkbox" name="skills[]" value="Event Planning"> Event Planning<br>
    <input type="checkbox" name="skills[]" value="Fundraising"> Fundraising<br>
    <input type="checkbox" name="skills[]" value="Communication"> Communication<br>
    <input type="checkbox" name="skills[]" value="Technical/IT"> Technical / IT<br>
    <input type="checkbox" name="skills[]" value="Healthcare"> Healthcare<br>
    <input type="checkbox" name="skills[]" value="Other"> Other<br><br>

    <label>Areas of Interest:</label><br>
    <input type="checkbox" name="interests[]" value="Environment"> Environment<br>
    <input type="checkbox" name="interests[]" value="Education"> Education<br>
    <input type="checkbox" name="interests[]" value="Community Service"> Community Service<br>
    <input type="checkbox" name="interests[]" value="Health"> Health<br>
    <input type="checkbox" name="interests[]" value="Disaster Response"> Disaster Response<br><br>

    <!-- 🔹 SUBMIT -->
     <center>
        <button type="submit" class="submit-btn">
            Submit Application
        </button>
    </center>
</form>


<script>
    const slots = document.querySelectorAll('.slot');
    const hiddenInput = document.getElementById('availability');

    let isDragging = false;

    slots.forEach(slot => {

        slot.addEventListener('mousedown', () => {
            isDragging = true;
            slot.classList.toggle('selected');
            updateAvailability();
        });

        slot.addEventListener('mouseover', () => {
            if (isDragging) {
                slot.classList.add('selected');
                updateAvailability();
            }
        });

        slot.addEventListener('mouseup', () => {
            isDragging = false;
        });

    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
    });

    function updateAvailability() {
        const selected = document.querySelectorAll('.slot.selected');
        let values = [];

        selected.forEach(s => {
            values.push(s.dataset.value);
        });

        hiddenInput.value = JSON.stringify(values);
    }


    
</script>
<script>
    const form = document.getElementById('volunteerForm');
    const backBtn = document.getElementById('backBtn');

    // Function to check if form has data
    function isFormDirty() {
        const inputs = form.querySelectorAll('input, textarea, select');

        for (let input of inputs) {
            if (
                (input.type === "checkbox" && input.checked) ||
                (input.type !== "checkbox" && input.value.trim() !== "")
            ) {
                return true;
            }
        }
        return false;
    }

    // Back button click
    backBtn.addEventListener('click', function (e) {
        if (isFormDirty()) {
            const confirmLeave = confirm("Are you sure you want to go back? All entered data will be lost.");

            if (!confirmLeave) {
                e.preventDefault(); // stop navigation
            }
        }
    });
</script>
</body>
</html>