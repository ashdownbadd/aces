<?php

$headers = $headers ?? [];
$rows = $rows ?? [];
$emptyMessage = $emptyMessage ?? 'No records found.';

?>

<div class="table-wrapper">

    <table class="table">

        <thead>

            <tr>

                <?php foreach ($headers as $header): ?>

                    <th><?= htmlspecialchars($header) ?></th>

                <?php endforeach; ?>

            </tr>

        </thead>

        <tbody>

            <?php if (empty($rows)): ?>

                <tr>

                    <td
                        colspan="<?= max(count($headers), 1) ?>"
                        class="table__empty">

                        <?= htmlspecialchars($emptyMessage) ?>

                    </td>

                </tr>

            <?php else: ?>

                <?php foreach ($rows as $row): ?>

                    <tr>

                        <?php foreach ($row as $cell): ?>

                            <td><?= $cell ?></td>

                        <?php endforeach; ?>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

        </tbody>

    </table>

</div>