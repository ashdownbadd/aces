<?php

$id = $id ?? '';

$title = $title ?? '';

$body = $body ?? '';

$footer = $footer ?? '';

?>

<div
    class="modal"
    id="<?= htmlspecialchars($id) ?>"
>

    <div class="modal__content">

        <div class="modal__header">

            <h2>

                <?= htmlspecialchars($title) ?>

            </h2>

        </div>

        <div class="modal__body">

            <?= $body ?>

        </div>

        <div class="modal__footer">

            <?= $footer ?>

        </div>

    </div>

</div>