<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

c('member_form/wizard', [
    'member' => $member ?? []
]);
