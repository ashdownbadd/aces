<section class="card">

    <div class="card__header">

        <div>

            <h2 class="card__title">

                <i class="fas fa-user"></i>

                Borrower

            </h2>

            <p class="card__subtitle">

                Select the cooperative member applying for this loan.

            </p>

        </div>

    </div>

    <div class="card__body">

        <div class="form-group">

            <label
                class="form-label"
                for="member_id">

                Member

            </label>

            <select
                class="form-control"
                id="member_id"
                name="member_id"
                required>

                <option value="">

                    -- Select Member --

                </option>

                <?php foreach ($members as $member): ?>

                    <option
                        value="<?= (int) $member['id'] ?>"
                        data-name="<?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>"
                        data-number="<?= htmlspecialchars($member['member_number']) ?>">

                        <?= htmlspecialchars(
                            $member['last_name']
                                . ', '
                                . $member['first_name']
                                . ' ('
                                . $member['member_number']
                                . ')'
                        ) ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div
            class="loan-member"
            id="loanMemberPreview">

            <div class="loan-member__avatar">

                <i class="fas fa-user"></i>

            </div>

            <div class="loan-member__content">

                <strong
                    class="loan-member__name"
                    id="loanMemberName">

                    No member selected

                </strong>

                <span
                    class="loan-member__number"
                    id="loanMemberNumber">

                    Select a borrower to continue.

                </span>

            </div>

        </div>

    </div>

</section>