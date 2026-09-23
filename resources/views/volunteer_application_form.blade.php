<!DOCTYPE html>

<html>

<head>


<title>Volunteer Application Form</title>

<link rel="stylesheet" href="{{ asset('style.css') }}">


</head>

<body>


<div class="back-container">

    <a
        href="/volunteer-page"
        class="back-btn-fixed"
        id="backBtn"
    >
        Back
    </a>

</div>


<!-- LOGO -->

<div class="logo-container">

    <img
        src="{{ asset('images/suhayLogo.png') }}"
        alt="Suhay Logo"
    >

</div>


<h2>Volunteer Application Form</h2>


<form
    id="volunteerForm"
    method="POST"
    action="#"
>

    @csrf


    <!-- ========================= -->
    <!-- PERSONAL INFORMATION -->
    <!-- ========================= -->

    <h3>Personal Information</h3>


    <label>First Name:</label><br>

    <input
        type="text"
        name="first_name"
        required
    >

    <br><br>


    <label>Last Name:</label><br>

    <input
        type="text"
        name="last_name"
        required
    >

    <br><br>


    <label>Email Address:</label><br>

    <input
        type="email"
        name="email"
        required
    >

    <br><br>


    <label>Contact Number:</label><br>

    <input
        type="text"
        name="contact"
        required
    >

    <br><br>


    <label>Date of Birth:</label><br>

    <input
        type="date"
        name="dob"
        max="{{ date('Y-m-d') }}"
        required
    >

    <br><br>


    <!-- ========================= -->
    <!-- ADDRESS INFORMATION -->
    <!-- ========================= -->

    <h3>Address Information</h3>


    <label>Province:</label><br>

    <input
        type="text"
        name="province"
        required
    >

    <br><br>


    <label>City / Municipality:</label><br>

    <input
        type="text"
        name="city"
        required
    >

    <br><br>


    <label>Barangay:</label><br>

    <input
        type="text"
        name="barangay"
        required
    >

    <br><br>


    <label>House Number / Street:</label><br>

    <input
        type="text"
        name="house_number"
        required
    >

    <br><br>


    <label>Additional Address Details:</label><br>

    <textarea
        name="address_notes"
        placeholder="Apartment, unit number, subdivision, landmark, etc."
    ></textarea>

    <br><br>


    <!-- ========================= -->
    <!-- SKILLS -->
    <!-- ========================= -->

    <h3>Skills</h3>

    <label>Select your skills:</label>

    <br>


    <input
        type="checkbox"
        name="skills[]"
        value="Teaching"
    >
    Teaching

    <br>


    <input
        type="checkbox"
        name="skills[]"
        value="Event Planning"
    >
    Event Planning

    <br>


    <input
        type="checkbox"
        name="skills[]"
        value="Fundraising"
    >
    Fundraising

    <br>


    <input
        type="checkbox"
        name="skills[]"
        value="Communication"
    >
    Communication

    <br>


    <input
        type="checkbox"
        name="skills[]"
        value="Technical/IT"
    >
    Technical / IT

    <br>


    <input
        type="checkbox"
        name="skills[]"
        value="Healthcare"
    >
    Healthcare

    <br>


    <input
        type="checkbox"
        name="skills[]"
        value="Other"
    >
    Other

    <br><br>


    <!-- ========================= -->
    <!-- SUBMIT -->
    <!-- ========================= -->

    <center>

        <button
            type="submit"
            class="submit-btn"
        >
            Submit Application
        </button>

    </center>


</form>


<!-- EXTERNAL JAVASCRIPT -->

<script src="{{ asset('js/volunteer-application.js') }}"></script>


</body>

</html>
