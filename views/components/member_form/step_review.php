<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="review-grid">

    <div class="review-card">

        <div class="review-card__header">

            <i class="fas fa-user"></i>

            <h3>Personal Information</h3>

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

    <div class="review-card">

        <div class="review-card__header">

            <i class="fas fa-phone"></i>

            <h3>Contact Information</h3>

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

    <div class="review-card review-card--full">

        <div class="review-card__header">

            <i class="fas fa-map-marker-alt"></i>

            <h3>Address</h3>

        </div>

        <dl class="review-list">

            <div class="review-list__item">

                <dt>Current Address</dt>

                <dd id="reviewAddress">-</dd>

            </div>

        </dl>

    </div>

</div>