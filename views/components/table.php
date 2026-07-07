<?php

$headers = $headers ?? [];
$rows = $rows ?? [];

$caption = $caption ?? '';

$emptyMessage = $emptyMessage ?? 'No records found.';

?>

<div class="table">

    <?php if ($caption): ?>

        <div class="table__caption">

            <?= htmlspecialchars($caption) ?>

        </div>

    <?php endif; ?>

    <div class="table__scroll">

        <table>

            <thead>

                <tr>

                    <?php foreach ($headers as $header): ?>

                        <th>

                            <?= htmlspecialchars($header) ?>

                        </th>

                    <?php endforeach; ?>

                </tr>

            </thead>

            <tbody>

                <?php if (!$rows): ?>

                    <tr>

                        <td
                            colspan="<?= max(count($headers), 1); ?>"
                            class="table__empty">

                            <div class="table__empty-icon">

                                <i class="fas fa-folder-open"></i>

                            </div>

                            <div class="table__empty-title">

                                <?= htmlspecialchars($emptyMessage) ?>

                            </div>

                            <div class="table__empty-description">

                                There are currently no records to display.

                            </div>

                        </td>

                    </tr>

                <?php else: ?>

                    <?php foreach ($rows as $row): ?>

                        <tr>

                            <?php foreach ($row as $cell): ?>

                                <td>

                                    <?= $cell ?>

                                </td>

                            <?php endforeach; ?>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>