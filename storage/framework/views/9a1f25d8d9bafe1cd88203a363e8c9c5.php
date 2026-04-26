<!DOCTYPE html>
<html>
    <head>
    <title>Volunteer Application Form</title>
    <link rel="stylesheet" href="<?php echo e(asset('style.css')); ?>">
</head>

<body>
    <div class="back-container">
        <a href="/volunteer/events" class="back-btn-fixed" id="backBtn">Back</a>
    </div>
    
    <!-- LOGO -->
    <div class="logo-container">
        <img src="<?php echo e(asset('images/suhayLogo.png')); ?>" alt="logo">
    </div>

    <h2>Volunteer Application Form</h2>


<form id="volunteerForm" method="POST" action="/submit-application">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="event_id" value="<?php echo e($eventId); ?>">

    <h3>Personal Information</h3>

    <label>Full Name:</label><br>
    <input type="text" value="<?php echo e($user['first_name']); ?> <?php echo e($user['last_name']); ?>" readonly class="bg-gray-100"><br><br>

    <label>Email Address:</label><br>
    <input type="email" value="<?php echo e($user['email']); ?>" readonly><br><br>

    <label>Contact Number:</label><br>
    <input type="text" value="<?php echo e($user['contact_number']); ?>" readonly><br><br>

    <label>Date of Birth:</label><br>
    <input type="text" value="<?php echo e($user['birth_date']); ?>" readonly><br><br>

    <label>Full Address:</label><br>
    <textarea readonly><?php echo e($user['address']); ?></textarea><br><br>

    
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
            <?php
                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                $times = ['Morning','Afternoon','Evening'];
            ?>

            <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($day); ?></td>
                <?php $__currentLoopData = $times; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="slot" data-value="<?php echo e($day); ?>-<?php echo e($time); ?>"></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>


    <h3>About You</h3>

    <label>Why do you want to volunteer?</label><br>
    <textarea name="remarks"></textarea><br><br>

    <label>Do you have previous volunteer experience?</label><br>
    <select name="has_experience">
        <option value="No">No</option>    
        <option value="Yes">Yes</option>
    </select><br><br>

    <label>If yes, please describe:</label><br>
    <textarea name="experience_details"></textarea><br><br>

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
    
    
     <center>
        <button type="submit" class="submit-btn">
            Submit Application
        </button>
    </center>
    <div id="skillsError" style="color:red; display:none;">
        Please select at least one skill.
    </div>
    <div id="interestsError" style="color:red; display:none;">
        Please select at least one area of interest.
    </div>
    <div id="submitError" style="color:red; display:none;">
        Please complete all required fields before submitting.
    </div>
</form>

<div id="confirmModal" style="display:none; position:fixed; top:0; left:0; 
width:100%; height:100%; background:rgba(0,0,0,0.5); 
justify-content:center; align-items:center;">

    <div style="background:white; padding:20px; border-radius:10px; text-align:center; width:300px;">
        <p>Are you sure all details are correct?</p>

        <button id="confirmYes" style="background:green; color:white; padding:8px 12px; margin:5px;">
            Yes
        </button>

        <button id="confirmNo" style="background:red; color:white; padding:8px 12px; margin:5px;">
            No
        </button>
    </div>

</div>

<script>
document.getElementById('volunteerForm').addEventListener('submit', function () {

    let selected = [...document.querySelectorAll('.slot.selected')]
        .map(el => el.dataset.value);

    let days = selected.map(s => s.split('-')[0]);
    let times = selected.map(s => s.split('-')[1]);

    let uniqueDays = [...new Set(days)];
    let uniqueTimes = [...new Set(times)];

    let availability = "Flexible";

    if (uniqueTimes.length === 1) {
        availability = uniqueTimes[0] + "s";
    } 
    else if (uniqueDays.every(d => ['Saturday','Sunday'].includes(d))) {
        availability = "Weekends";
    } 
    else if (uniqueDays.every(d => ['Monday','Tuesday','Wednesday','Thursday','Friday'].includes(d))) {
        availability = "Weekdays";
    }

    document.getElementById('availability').value = availability;
});
</script>
<script>
    const slots = document.querySelectorAll('.slot');
    const hiddenInput = document.getElementById('availability');

    let isDragging = false;

    slots.forEach(slot => {

        slot.addEventListener('mousedown', () => {
            isDragging = true;
            slot.classList.toggle('selected');
        });

        slot.addEventListener('mouseover', () => {
            if (isDragging) {
                slot.classList.add('selected');
            }
        });

        slot.addEventListener('mouseup', () => {
            isDragging = false;
        });

    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
    });

</script>
<script>
    const appform = document.getElementById('volunteerForm');
    const backBtn = document.getElementById('backBtn');

    // Function to check if form has data
    function isFormDirty() {
        const inputs = appform.querySelectorAll('input, textarea, select');

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
                e.preventDefault(); 
            }
        }
    });

    const experienceSelect = document.querySelector('select[name="has_experience"]');
    const experienceDetails = document.querySelector('textarea[name="experience_details"]');

    function toggleExperienceField() {
        if (experienceSelect.value === "No") {
            experienceDetails.disabled = true;
            experienceDetails.value = ""; // clear value
            experienceDetails.style.opacity = 0.5;
        } else {
            experienceDetails.disabled = false;
            experienceDetails.style.opacity = 1;
        }
    }

    // run on change
    experienceSelect.addEventListener('change', toggleExperienceField);

    // run on page load (important for default value)
    toggleExperienceField();
</script>
<script>
const form = document.getElementById('volunteerForm');
const modal = document.getElementById('confirmModal');

const skillsError = document.getElementById('skillsError');
const interestsError = document.getElementById('interestsError');
const submitError = document.getElementById('submitError');

form.addEventListener('submit', function (e) {
    e.preventDefault();

    // reset errors
    skillsError.style.display = "none";
    interestsError.style.display = "none";
    submitError.style.display = "none";

    let skillsChecked = document.querySelectorAll('input[name="skills[]"]:checked');
    let interestsChecked = document.querySelectorAll('input[name="interests[]"]:checked');

    let valid = true;

    // validate skills
    if (skillsChecked.length === 0) {
        skillsError.style.display = "block";
        valid = false;
    }

    // validate interests
    if (interestsChecked.length === 0) {
        interestsError.style.display = "block";
        valid = false;
    }

    let availabilitySelected = document.querySelectorAll('.slot.selected');
    if (availabilitySelected.length === 0) {
        submitError.innerText = "Please select availability.";
        submitError.style.display = "block";
        valid = false;
    }

    if (!valid) return;

    modal.style.display = "flex";
});
</script>
<script>
document.getElementById('confirmYes').addEventListener('click', function () {
    document.getElementById('confirmModal').style.display = "none";

    const selected = [...document.querySelectorAll('.slot.selected')]
        .map(el => el.dataset.value);

    document.getElementById('volunteerForm').submit();
});

document.getElementById('confirmNo').addEventListener('click', function () {
    document.getElementById('confirmModal').style.display = "none";
});
</script>


</body>
</html><?php /**PATH D:\Acads\MEt.A-Project-SUHAY\resources\views/volunteer_application_form.blade.php ENDPATH**/ ?>