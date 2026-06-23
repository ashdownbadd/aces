<?php
// helpers/AmortizationEngine.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}

class AmortizationEngine {
    
    public static function calculateDeductions($principal, $terms) {
        $processingFee = $principal * 0.02;
        $insurance = ($principal / 1000) * 1.2 * $terms;
        $notarialFee = 400.00;
        $netProceeds = $principal - $processingFee - $insurance - $notarialFee;
        
        return [
            'processingFee' => $processingFee,
            'insurance'     => $insurance,
            'notarialFee'   => $notarialFee,
            'netProceeds'   => $netProceeds
        ];
    }

    public static function generateSchedule($params) {
        $principal = floatval($params['principal']);
        $rate = floatval($params['interest_rate']) / 100; // convert % to decimal
        $terms = intval($params['terms']);
        $startDate = $params['start_date'];
        $amortType = $params['amortization_type'];
        $loanType = $params['loan_type'];
        $freq = $params['payment_frequency'] ?? 'Monthly';
        $manualPayment = floatval($params['manual_payment'] ?? 0);

        $schedule = [];
        $currentDate = new DateTime($startDate);

        // 1. MICRO-FINANCE MODE SPECIAL FLAT-RATE RULES
        if ($loanType === 'Micro-Finance Loan') {
            $multiplier = 1;
            if ($freq === 'Bi-Monthly') $multiplier = 2;
            if ($freq === 'Weekly') $multiplier = 4;

            $ratePerPeriod = $rate / $multiplier;
            $totalPeriods = $terms * $multiplier;
            
            $principalPerPeriod = $principal / $totalPeriods;
            $interestPerPeriod = $principal * $ratePerPeriod;

            for ($i = 1; $i <= $totalPeriods; $i++) {
                if ($freq === 'Bi-Monthly') {
                    $currentDate->modify('+15 days');
                } elseif ($freq === 'Weekly') {
                    $currentDate->modify('+7 days');
                } else {
                    $currentDate->modify('+1 month');
                }

                $schedule[] = [
                    'period' => $i,
                    'due_date' => $currentDate->format('Y-m-d'),
                    'principal' => $principalPerPeriod,
                    'interest' => $interestPerPeriod
                ];
            }
            return $schedule;
        }

        // 2. STANDARD MODES (Straight-line, Diminishing, Manual)
        $currentBalance = $principal;
        $monthlyRate = $rate; 
        $fixedPrincipal = $principal / $terms;

        for ($i = 1; $i <= $terms; $i++) {
            $currentDate->modify('+1 month');
            $dueDateStr = $currentDate->format('Y-m-d');

            if ($amortType === 'Straight-line') {
                $pPortion = $fixedPrincipal;
                $iPortion = $principal * $monthlyRate;
            } 
            elseif ($amortType === 'Diminishing balance') {
                $pPortion = $fixedPrincipal;
                $iPortion = $currentBalance * $monthlyRate;
                $currentBalance -= $fixedPrincipal;
            } 
            elseif ($amortType === 'Manual') {
                $iPortion = $currentBalance * $monthlyRate;
                $pPortion = $manualPayment - $iPortion;
                if ($pPortion < 0) $pPortion = 0;
                $currentBalance -= $pPortion;
                if ($currentBalance < 0) $currentBalance = 0;
            }

            $schedule[] = [
                'period' => $i,
                'due_date' => $dueDateStr,
                'principal' => $pPortion,
                'interest' => $iPortion
            ];
        }

        return $schedule;
    }

    public static function calculateMonthsOverdue($dueDateStr) {
        $today = new DateTime('now');
        $dueDate = new DateTime($dueDateStr);
        
        if ($dueDate >= $today) return 0;
        
        $diffYear = intval($today->format('Y')) - intval($dueDate->format('Y'));
        $diffMonth = intval($today->format('m')) - intval($dueDate->format('m'));
        $months = ($diffYear * 12) + $diffMonth;
        
        return max(1, $months);
    }
}