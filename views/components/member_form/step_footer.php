<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<button
    type="button"
    class="btn btn--secondary"
    id="wizardPrevious">

    <i class="fas fa-arrow-left"></i>

    Previous

</button>

<div class="member-panel__actions">

    <button
        type="button"
        class="btn btn--primary"
        id="wizardNext">

        Continue

        <i class="fas fa-arrow-right"></i>

    </button>

    <button
        type="submit"
        class="btn btn--success"
        id="wizardSubmit">

        <i class="fas fa-save"></i>

        Save Member

    </button>

</div>