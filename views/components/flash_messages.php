<?php

if ($message = flash('success_message')) {

    c('alert', [
        'type'    => 'success',
        'title'   => 'Success',
        'message' => htmlspecialchars($message)
    ]);
}

if ($message = flash('error_message')) {

    c('alert', [
        'type'    => 'danger',
        'title'   => 'Error',
        'message' => htmlspecialchars($message)
    ]);
}
