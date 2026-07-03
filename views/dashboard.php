<?php

if (!defined('ALLOW_ACCESS')) {
    exit('Direct access to this file is prohibited.');
}

?>

<div class="page">

    <?php

    c('page_header', [

        'title' => 'Dashboard',

        'description' =>
        'Welcome back, <strong>' .
            htmlspecialchars($_SESSION['username']) .
            '</strong>. Here\'s a quick overview of your cooperative management system.'

    ]);

    ?>

    <?php c('flash_messages'); ?>

    <?php

    c('section_header', [

        'title' => 'System Overview',

        'description' => 'Key cooperative statistics.'

    ]);

    ?>

    <?php

    c('stats_grid', [

        'cards' => $cards

    ]);

    ?>

    <?php

    c('section_header', [

        'title' => 'System Health',

        'description' => 'Monitor issues requiring administrator attention.'

    ]);

    ?>

    <?php

    c('system_alerts', [

        'alerts' => $alerts

    ]);

    ?>

    <?php

    c('section_header', [

        'title' => 'Quick Actions',

        'description' => 'Frequently used modules.'

    ]);

    ?>

    <?php

    c('module_grid', [

        'modules' => $modules

    ]);

    ?>

</div>