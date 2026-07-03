<?php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>

<div class="container">

    <div class="header-actions">

        <p>
            <a href="index.php?route=dashboard">
                ← Back to Dashboard
            </a>
        </p>

    </div>

    <h2>Cooperative Official Members Registry</h2>

    <p class="u-mb-lg">
        This workspace tracks actual cooperative shareholders,
        registration logs,
        and capital statements.
    </p>

    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>

        <a
            href="index.php?route=add_member"
            class="btn btn--success u-mb-lg">

            + Register New Cooperative Member

        </a>

    <?php else: ?>

        <div class="badge badge--staff u-mb-lg">

            🔒 Account Tier Mode:
            Staff View (Read-Only)

        </div>

    <?php endif; ?>

    <?php if (isset($_SESSION['success_message'])): ?>

        <div class="alert alert--success">

            <?= htmlspecialchars($_SESSION['success_message']); ?>

            <?php unset($_SESSION['success_message']); ?>

        </div>

    <?php endif; ?>

    <form
        action="index.php"
        method="GET"
        class="toolbar__search">

        <input
            type="hidden"
            name="route"
            value="members">

        <label
            class="form__label"
            for="search">

            Search Member

        </label>

        <input
            class="form__control"
            type="text"
            id="search"
            name="search"
            placeholder="Enter name or member number..."
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

        <button
            class="btn btn--primary"
            type="submit">

            Filter

        </button>

        <?php if (!empty($_GET['search'])): ?>

            <a
                href="index.php?route=members">

                Clear Filter

            </a>

        <?php endif; ?>

    </form>

    <table class="table">

        <thead>

            <tr>

                <th>Member No.</th>

                <th>Full Name</th>

                <th>Email</th>

                <th>Phone</th>

                <th>Share Capital</th>

                <th>Status</th>

            </tr>

        </thead>

        <tbody>

            <?php if (empty($coop_members)): ?>

                <tr>

                    <td colspan="6" class="table__empty">

                        No members found.

                    </td>

                </tr>

            <?php else: ?>

                <?php foreach ($coop_members as $m): ?>

                    <tr>

                        <td>

                            <code class="code">

                                <?= htmlspecialchars($m['member_number']); ?>

                            </code>

                        </td>

                        <td>

                            <a
                                class="module-card__title"
                                href="index.php?route=member_profile&id=<?= $m['id']; ?>">

                                <?= htmlspecialchars($m['last_name'] . ', ' . $m['first_name']); ?>

                            </a>

                        </td>

                        <td>

                            <?= htmlspecialchars($m['email'] ?: 'N/A'); ?>

                        </td>

                        <td>

                            <?= htmlspecialchars($m['phone'] ?: 'N/A'); ?>

                        </td>

                        <td>

                            <strong>

                                $<?= number_format($m['share_capital'] ?? 0, 2); ?>

                            </strong>

                        </td>

                        <td>

                            <?php if (($m['status'] ?? '') === 'active'): ?>

                                <span class="status status--active">

                                    ACTIVE

                                </span>

                            <?php else: ?>

                                <span class="status status--inactive">

                                    <?= htmlspecialchars(strtoupper($m['status'] ?? 'UNKNOWN')); ?>

                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

        </tbody>

    </table>

</div>