<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="form-grid form-grid--2">

    <div class="form-group">

        <label class="form-label">

            Email Address

        </label>

        <input
            class="form-control"
            type="email"
            name="email"
            placeholder="juan.delacruz@email.com">

    </div>

    <div class="form-group">

        <label class="form-label">

            Primary Phone <span class="required">*</span>

        </label>

        <input
            class="form-control"
            type="text"
            name="phone_no_1"
            placeholder="09XXXXXXXXX"
            required>

    </div>

</div>

<div class="form-grid">

    <div class="form-group">

        <label class="form-label">

            Secondary Phone

            <span style="font-weight:400;color:var(--text-muted);">

                (Optional)

            </span>

        </label>

        <input
            class="form-control"
            type="text"
            name="phone_no_2"
            placeholder="Optional">

    </div>

</div>