<?php
// views/loan_create.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>
<div class="container" style="font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto;">
    <p><a href="index.php?route=amortization_dashboard">← Back to Loans Dashboard</a></p>

    <h2>Generate Amortization Schedule</h2>
    <p>Setup parameters below to automatically formulate an explicit financial schedule matrix matching portfolio logic rules.</p>
    <hr>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: red; font-weight: bold; background: #fee; padding: 8px; border: 1px solid red; margin-bottom: 15px; border-radius: 4px;">
            <?php echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <form action="index.php?route=create_loan" method="POST" enctype="multipart/form-data" style="background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 6px;">

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Cooperative Member Profile:</label>
            <select name="member_id" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                <option value="">-- Select Target Account Owner --</option>
                <?php foreach ($members as $m): ?>
                    <option value="<?php echo $m['id']; ?>"><?php echo htmlspecialchars($m['last_name'] . ', ' . $m['first_name'] . ' (' . $m['member_number'] . ')'); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Loan Type Classification:</label>
                <select name="loan_type" id="loan_type" onchange="handleLoanTypeChange()" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="Personal Loan">Personal Loan</option>
                    <option value="Bridge Financing">Bridge Financing</option>
                    <option value="Investment Loan">Investment Loan</option>
                    <option value="Pension Loan">Pension Loan</option>
                    <option value="Productivity Loan">Productivity Loan</option>
                    <option value="Salary Loan">Salary Loan</option>
                    <option value="Micro-Finance Loan">Micro-Finance Loan</option>
                </select>
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Asset Collateral Class:</label>
                <select name="collateral" id="collateral" onchange="handleCollateralChange()" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="Post-Dated Check">Post-Dated Check</option>
                    <option value="Real Property">Real Property</option>
                    <option value="Chattels / Movable Assets">Chattels / Movable Assets</option>
                </select>
            </div>
        </div>

        <div id="real_property_panel" style="display: none; background: #fff; border: 1px solid #337ab7; border-radius: 4px; padding: 15px; margin-bottom: 15px;">
            <h4 style="margin-top:0; color:#337ab7;">Real Property Registration Assets</h4>
            <div style="display:flex; gap:10px; margin-bottom:10px;">
                <div style="flex:1;">
                    <label style="font-size:0.9em; font-weight:bold;">TCT No:</label>
                    <input type="text" name="tct_no" style="width:100%; padding:6px; box-sizing:border-box;">
                </div>
                <div style="flex:1;">
                    <label style="font-size:0.9em; font-weight:bold;">Tax Declaration No:</label>
                    <input type="text" name="tax_declaration_no" style="width:100%; padding:6px; box-sizing:border-box;">
                </div>
                <div style="flex:1;">
                    <label style="font-size:0.9em; font-weight:bold;">Tax Payments Status:</label>
                    <select name="real_property_status" style="width:100%; padding:6px;">
                        <option value="Updated">Updated</option>
                        <option value="Pending">Pending</option>
                        <option value="Overdue">Overdue</option>
                    </select>
                </div>
            </div>
            <div>
                <label style="font-size:0.9em; font-weight:bold; display:block;">Undertaking Document (PDF Only):</label>
                <input type="file" name="undertaking_doc" accept="application/pdf" style="margin-bottom:10px;">
                <label style="font-size:0.9em; font-weight:bold; display:block;">Assignment of Deed of Rights (PDF Only):</label>
                <input type="file" name="deed_of_rights_doc" accept="application/pdf">
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Amortization Method:</label>
                <select name="amortization_type" id="amortization_type" onchange="handleAmortTypeChange()" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="Straight-line">Straight-line Mode</option>
                    <option value="Diminishing balance">Diminishing Balance Mode</option>
                    <option value="Manual">Manual Installment Payment</option>
                </select>
            </div>
            <div style="flex: 1; display: none;" id="frequency_panel">
                <label style="display: block; font-weight: bold; margin-bottom: 5px; color: green;">Micro-Finance Frequency Multiplier:</label>
                <select name="payment_frequency" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; background:#eef9ee;">
                    <option value="Monthly">Monthly Structure</option>
                    <option value="Bi-Monthly">Bi-Monthly (+15 Days Split)</option>
                    <option value="Weekly">Weekly Cycle (+7 Days Split)</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Principal Core Sum (₱):</label>
                <input type="number" name="principal" id="principal" step="0.01" required oninput="calculateLiveDeductions()" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing:border-box;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Interest Rate (% Per Month):</label>
                <input type="number" name="interest_rate" id="interest_rate" step="0.01" required oninput="flagManualInterest()" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing:border-box;">
            </div>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Term Duration (Months):</label>
                <input type="number" name="terms" id="terms" required oninput="calculateLiveDeductions()" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing:border-box;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Schedule Start Release Date:</label>
                <input type="date" name="start_date" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing:border-box;">
            </div>
        </div>

        <div style="margin-bottom: 20px; display: none;" id="manual_payment_panel">
            <label style="display: block; font-weight: bold; margin-bottom: 5px; color: #c9302c;">Explicit Target Amount Per Period (₱):</label>
            <input type="number" name="manual_payment" step="0.01" value="0.00" style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; box-sizing:border-box;">
        </div>

        <div id="deductions_panel" style="background: #f0f4f8; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px dashed #337ab7;">
            <h4 style="margin: 0 0 10px 0; color: #2c3e50;">Computed Capital Deductions (Waterfall Projections)</h4>
            <p style="margin: 4px 0;">Processing Fee (2%): <span id="lbl_processing" style="font-weight: bold;">₱0.00</span></p>
            <p style="margin: 4px 0;">Mutual Insurance Protection Line: <span id="lbl_insurance" style="font-weight: bold;">₱0.00</span></p>
            <p style="margin: 4px 0;">Notarial Fee Base Line: <span id="lbl_notarial" style="font-weight: bold;">₱0.00</span></p>
            <hr style="border: 0; border-top: 1px solid #ccc; margin: 8px 0;">
            <p style="margin: 4px 0; font-size: 1.1em;"><strong>Projected Net Loan Proceeds: <span id="lbl_net" style="color: green;">₱0.00</span></strong></p>
        </div>

        <button type="submit" style="width: 100%; background: #5cb85c; color: white; padding: 10px; border: none; font-weight: bold; border-radius: 4px; cursor: pointer; font-size: 1.1em;">
            Establish Account & Commit Amortization Schedule
        </button>
    </form>
</div>

<script>
    let isInterestManuallyEdited = false;

    function handleLoanTypeChange() {
        const loanType = document.getElementById('loan_type').value;
        const rateInput = document.getElementById('interest_rate');
        const freqPanel = document.getElementById('frequency_panel');
        const amortSelect = document.getElementById('amortization_type');

        if (loanType === 'Micro-Finance Loan') {
            rateInput.value = "5.00";
            isInterestManuallyEdited = false;
            freqPanel.style.display = 'block';

            // Lock select option
            amortSelect.value = "Straight-line";
            amortSelect.style.backgroundColor = "#eee";
            amortSelect.disabled = true;
            amortSelect.removeAttribute('name'); // FIX: Stop blank elements from hijacking payload

            // Inject hidden fallback input control
            if (!document.getElementById('hidden_amort_type')) {
                let hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = 'hidden_amort_type';
                hiddenInput.name = 'amortization_type';
                hiddenInput.value = 'Straight-line';
                amortSelect.parentNode.appendChild(hiddenInput);
            }
        } else {
            if (!isInterestManuallyEdited) {
                rateInput.value = "2.00";
            }
            freqPanel.style.display = 'none';
            amortSelect.disabled = false;
            amortSelect.setAttribute('name', 'amortization_type');
            amortSelect.style.backgroundColor = "#fff";

            let hiddenInput = document.getElementById('hidden_amort_type');
            if (hiddenInput) hiddenInput.remove();
        }
        handleAmortTypeChange();
        calculateLiveDeductions();
    }

    function flagManualInterest() {
        isInterestManuallyEdited = true;
    }

    function handleAmortTypeChange() {
        const type = document.getElementById('amortization_type').value;
        const manualPanel = document.getElementById('manual_payment_panel');
        if (manualPanel) {
            manualPanel.style.display = (type === 'Manual') ? 'block' : 'none';
        }
    }

    function handleCollateralChange() {
        const classVal = document.getElementById('collateral').value;
        const propertyPanel = document.getElementById('real_property_panel');
        propertyPanel.style.display = (classVal === 'Real Property') ? 'block' : 'none';
    }

    function calculateLiveDeductions() {
        const principal = parseFloat(document.getElementById('principal').value) || 0;
        const terms = parseInt(document.getElementById('terms').value) || 0;

        if (principal > 0 && terms > 0) {
            const processing = principal * 0.02;
            const insurance = (principal / 1000) * 1.2 * terms;
            const notarial = 400.00;
            const net = principal - processing - insurance - notarial;

            document.getElementById('lbl_processing').innerText = '₱' + processing.toFixed(2);
            document.getElementById('lbl_insurance').innerText = '₱' + insurance.toFixed(2);
            document.getElementById('lbl_notarial').innerText = '₱' + notarial.toFixed(2);
            document.getElementById('lbl_net').innerText = '₱' + net.toFixed(2);
        } else {
            document.getElementById('lbl_processing').innerText = '₱0.00';
            document.getElementById('lbl_insurance').innerText = '₱0.00';
            document.getElementById('lbl_notarial').innerText = '₱0.00';
            document.getElementById('lbl_net').innerText = '₱0.00';
        }
    }

    window.onload = function() {
        handleLoanTypeChange();
    };
</script>