<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="form-grid form-grid--2">

    <div class="form-group">

        <label class="form-label">

            First Name <span class="required">*</span>

        </label>

        <input
            class="form-control"
            type="text"
            name="first_name"
            required
            placeholder="Juan">

    </div>

    <div class="form-group">

        <label class="form-label">

            Last Name <span class="required">*</span>

        </label>

        <input
            class="form-control"
            type="text"
            name="last_name"
            required
            placeholder="Dela Cruz">

    </div>

</div>

<div class="form-grid">

    <div class="form-group">

        <label class="form-label">

            Middle Name

        </label>

        <input
            class="form-control"
            type="text"
            name="middle_name"
            placeholder="Santos">

    </div>

</div>

<div class="form-grid form-grid--3">

    <div class="form-group">

        <label class="form-label">

            Date of Birth <span class="required">*</span>

        </label>

        <input
            class="form-control"
            type="date"
            name="date_of_birth"
            required>

    </div>

    <div class="form-group">

        <label class="form-label">

            Sex <span class="required">*</span>

        </label>

        <select
            class="form-control"
            name="sex"
            required>

            <option value="">Select...</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>

        </select>

    </div>

    <div class="form-group">

        <label class="form-label">

            Marital Status

        </label>

        <select
            class="form-control"
            name="marital_status">

            <option value="">Select...</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Divorced">Divorced</option>

        </select>

    </div>

</div>