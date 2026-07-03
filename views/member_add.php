<?php
// views/member_add.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>

<div class="container container--md">

    <div class="form-card">

        <div class="header-actions">
            <p>
                <a href="index.php?route=members" class="member-link">
                    ← Back to Members Registry
                </a>
            </p>
        </div>

        <h2 class="u-mb-sm">Register New Cooperative Member</h2>

        <p class="u-text-muted u-mb-lg">
            Complete the comprehensive intake form to establish a new shareholder portfolio.
        </p>

        <hr>

        <?php if (isset($_SESSION['error_message'])): ?>

            <div class="alert alert--danger">

                <?= htmlspecialchars($_SESSION['error_message']); ?>

                <?php unset($_SESSION['error_message']); ?>

            </div>

        <?php endif; ?>

        <form action="index.php?route=add_member" method="POST">

            <!-- =======================================================
                 PERSONAL IDENTITY
            ======================================================== -->

            <section class="form-section">

                <h3 class="form-section__title">
                    Personal Identity
                </h3>

                <div class="form-grid form-grid--3">

                    <div class="form__group">

                        <label class="form__label">

                            First Name
                            <span class="required">*</span>

                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="first_name"
                            required>

                    </div>

                    <div class="form__group">

                        <label class="form__label">
                            Middle Name
                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="middle_name">

                    </div>

                    <div class="form__group">

                        <label class="form__label">

                            Last Name
                            <span class="required">*</span>

                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="last_name"
                            required>

                    </div>

                </div>

            </section>

            <!-- =======================================================
                 DEMOGRAPHICS
            ======================================================== -->

            <section class="form-section">

                <h3 class="form-section__title">

                    Demographics

                </h3>

                <div class="form-grid form-grid--3">

                    <div class="form__group">

                        <label class="form__label">

                            Date of Birth

                        </label>

                        <input
                            class="form__control"
                            type="date"
                            name="date_of_birth">

                    </div>

                    <div class="form__group">

                        <label class="form__label">

                            Sex

                        </label>

                        <select
                            class="form__control"
                            name="sex">

                            <option value="">
                                Select...
                            </option>

                            <option value="Male">
                                Male
                            </option>

                            <option value="Female">
                                Female
                            </option>

                        </select>

                    </div>

                    <div class="form__group">

                        <label class="form__label">

                            Marital Status

                        </label>

                        <select
                            class="form__control"
                            name="marital_status">

                            <option value="">
                                Select...
                            </option>

                            <option value="Single">
                                Single
                            </option>

                            <option value="Married">
                                Married
                            </option>

                            <option value="Widowed">
                                Widowed
                            </option>

                            <option value="Divorced">
                                Divorced
                            </option>

                        </select>

                    </div>

                </div>

            </section>
            <!-- =======================================================
                 CONTACT & LOCATION
            ======================================================== -->

            <section class="form-section">

                <h3 class="form-section__title">
                    Contact &amp; Location
                </h3>

                <div class="form-grid form-grid--3">

                    <div class="form__group">

                        <label class="form__label">
                            Email Address
                        </label>

                        <input
                            class="form__control"
                            type="email"
                            name="email">

                    </div>

                    <div class="form__group">

                        <label class="form__label">
                            Primary Phone
                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="phone_no_1">

                    </div>

                    <div class="form__group">

                        <label class="form__label">
                            Secondary Phone
                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="phone_no_2">

                    </div>

                </div>

                <div class="form-grid form-grid--address u-mt-md">

                    <div class="form__group">

                        <label class="form__label">
                            House Number
                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="house_number">

                    </div>

                    <div class="form__group">

                        <label class="form__label">
                            Street
                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="street">

                    </div>

                    <div class="form__group">

                        <label class="form__label">
                            Barangay
                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="barangay">

                    </div>

                </div>

                <div class="form-grid form-grid--3 u-mt-md">

                    <div class="form__group">

                        <label class="form__label">
                            Town / City
                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="town_city">

                    </div>

                    <div class="form__group">

                        <label class="form__label">
                            Province
                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="province">

                    </div>

                    <div class="form__group">

                        <label class="form__label">
                            Region
                        </label>

                        <input
                            class="form__control"
                            type="text"
                            name="region">

                    </div>

                </div>

            </section>

            <div class="form-footer">

                <button
                    type="submit"
                    class="btn btn--success btn--block">

                    Register Cooperative Member

                </button>

            </div>

        </form>

    </div>

</div>