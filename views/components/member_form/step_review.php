<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="member-step__content">

    <p class="member-step__description">
        Review all information before creating this member.
    </p>

    <div class="review-grid">

        <div class="review-card">

            <div class="review-card__header">

                <div class="review-card__title">

                    <i class="fas fa-piggy-bank"></i>

                    <h3>Initial Investment</h3>

                </div>

                <button
                    type="button"
                    class="btn btn--ghost btn--sm"
                    data-review-step="0">

                    Edit

                </button>

            </div>

            <dl class="review-list">

                <div class="review-list__item">

                    <dt>Initial Share Capital</dt>

                    <dd id="reviewShareCapital">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Membership Classification</dt>

                    <dd id="reviewMembership">Associate</dd>

                </div>

            </dl>

        </div>

        <!-- ==========================================================
             PERSONAL INFORMATION
        =========================================================== -->

        <div class="review-card">

            <div class="review-card__header">

                <div class="review-card__title">

                    <i class="fas fa-user"></i>

                    <h3>Personal Information</h3>

                </div>

                <button
                    type="button"
                    class="btn btn--ghost btn--sm"
                    data-review-step="0">

                    Edit

                </button>

            </div>

            <dl class="review-list">

                <div class="review-list__item">

                    <dt>Name</dt>

                    <dd id="reviewName">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Date of Birth</dt>

                    <dd id="reviewBirth">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Sex</dt>

                    <dd id="reviewSex">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Marital Status</dt>

                    <dd id="reviewMarital">-</dd>

                </div>

            </dl>

        </div>

        <!-- ==========================================================
             CONTACT INFORMATION
        =========================================================== -->

        <div class="review-card">

            <div class="review-card__header">

                <div class="review-card__title">

                    <i class="fas fa-phone"></i>

                    <h3>Contact Information</h3>

                </div>

                <button
                    type="button"
                    class="btn btn--ghost btn--sm"
                    data-review-step="1">

                    Edit

                </button>

            </div>

            <dl class="review-list">

                <div class="review-list__item">

                    <dt>Email</dt>

                    <dd id="reviewEmail">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Primary Phone</dt>

                    <dd id="reviewPhone1">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Secondary Phone</dt>

                    <dd id="reviewPhone2">-</dd>

                </div>

            </dl>

        </div>

        <!-- ==========================================================
             ADDRESS
        =========================================================== -->

        <div class="review-card review-card--full">

            <div class="review-card__header">

                <div class="review-card__title">

                    <i class="fas fa-map-marker-alt"></i>

                    <h3>Address Information</h3>

                </div>

                <button
                    type="button"
                    class="btn btn--ghost btn--sm"
                    data-review-step="2">

                    Edit

                </button>

            </div>

            <dl class="review-list">

                <div class="review-list__item">

                    <dt>Current Address</dt>

                    <dd id="reviewAddress">-</dd>

                </div>

            </dl>

        </div>

        <!-- ==========================================================
             EMPLOYMENT
        =========================================================== -->

        <div class="review-card">

            <div class="review-card__header">

                <div class="review-card__title">

                    <i class="fas fa-briefcase"></i>

                    <h3>Employment</h3>

                </div>

                <button
                    type="button"
                    class="btn btn--ghost btn--sm"
                    data-review-step="3">

                    Edit

                </button>

            </div>

            <dl class="review-list">

                <div class="review-list__item">

                    <dt>Status</dt>

                    <dd id="reviewEmploymentStatus">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Occupation</dt>

                    <dd id="reviewOccupation">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Employer / Business</dt>

                    <dd id="reviewEmployer">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Monthly Income</dt>

                    <dd id="reviewIncome">-</dd>

                </div>

            </dl>

        </div>

        <!-- ==========================================================
             EDUCATION
        =========================================================== -->

        <div class="review-card">

            <div class="review-card__header">

                <div class="review-card__title">

                    <i class="fas fa-graduation-cap"></i>

                    <h3>Educational Background</h3>

                </div>

                <button
                    type="button"
                    class="btn btn--ghost btn--sm"
                    data-review-step="4">

                    Edit

                </button>

            </div>

            <dl class="review-list">

                <div class="review-list__item">

                    <dt>Highest Educational Attainment</dt>

                    <dd id="reviewEducation">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Course / Degree</dt>

                    <dd id="reviewCourse">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>School / Institution</dt>

                    <dd id="reviewSchool">-</dd>

                </div>

                <div class="review-list__item">

                    <dt>Year Graduated</dt>

                    <dd id="reviewYearGraduated">-</dd>

                </div>

            </dl>

        </div>

        <!-- ==========================================================
             BENEFICIARIES
        =========================================================== -->

        <div class="review-card review-card--full">

            <div class="review-card__header">

                <div class="review-card__title">

                    <i class="fas fa-users"></i>

                    <h3>Beneficiaries</h3>

                </div>

                <button
                    type="button"
                    class="btn btn--ghost btn--sm"
                    data-review-step="5">

                    Edit

                </button>

            </div>

            <div id="reviewBeneficiaries">

                <div class="empty-state">

                    <i class="fas fa-users"></i>

                    <p>No beneficiaries added.</p>

                </div>

            </div>

        </div>

        <!-- ==========================================================
             CONFIRMATION
        =========================================================== -->

        <div class="review-card review-card--full">

            <div class="review-card__header">

                <div class="review-card__title">

                    <i class="fas fa-circle-check"></i>

                    <h3>Confirmation</h3>

                </div>

            </div>

            <div class="review-confirmation">

                <p>

                    Please review all of the information carefully before
                    saving this member.

                </p>

                <ul class="review-confirmation__list">

                    <li>
                        Ensure all required fields have been completed.
                    </li>

                    <li>
                        Verify contact information is correct.
                    </li>

                    <li>
                        Confirm beneficiary allocations total exactly
                        <strong>100%</strong>.
                    </li>

                    <li>
                        Once saved, the member will become part of the
                        cooperative records.
                    </li>

                </ul>

            </div>

        </div>

    </div>

</div>