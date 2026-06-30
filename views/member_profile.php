<?php
// views/member_profile.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}


// Helper function to safely output data and handle nulls
function display_value($value, $fallback = 'N/A') {
    return htmlspecialchars(!empty($value) ? $value : $fallback);
}

/** * @var array $member  The array containing core profile data for the targeted cooperative member    
 * @var array $member
 */
?>

<div class="container" style="font-family: Arial, sans-serif; padding: 20px; max-width: 1200px; margin: 0 auto;">
    
    <div style="margin-bottom: 20px;">
        <a href="index.php?route=members" style="text-decoration: none; color: #337ab7; font-weight: bold;">← Back to Member Registry</a>
    </div>

    <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="margin: 0; color: #333;">
                <?php echo display_value($member['first_name'] . ' ' . ($member['middle_name'] ? $member['middle_name'] . ' ' : '') . $member['last_name']); ?>
            </h1>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 1.1em;">
                ID: <strong><?php echo display_value($member['member_number']); ?></strong> | 
                Type: <strong><?php echo display_value($member['membership_type']); ?></strong>
            </p>
        </div>
        <div style="text-align: right;">
            <?php 
                $statusColor = $member['status'] === 'active' ? '#28a745' : ($member['status'] === 'inactive' ? '#ffc107' : '#dc3545');
            ?>
            <span style="background: <?php echo $statusColor; ?>; color: #fff; padding: 8px 16px; border-radius: 20px; font-weight: bold; font-size: 0.9em; text-transform: uppercase;">
                <?php echo display_value($member['status']); ?>
            </span>
            <p style="margin: 5px 0 0 0; font-size: 0.85em; color: #888;">
                Joined: <?php echo display_value($member['date_of_membership']); ?>
            </p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        
        <div>
            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 20px;">
                <h3 style="margin-top: 0; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Personal Information</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr><td style="padding: 5px 0; width: 40%; color: #555;">Date of Birth</td> <td><strong><?php echo display_value($member['date_of_birth']); ?></strong></td></tr>
                    <tr><td style="padding: 5px 0; color: #555;">Sex</td> <td><strong><?php echo display_value($member['profile']['sex'] ?? null); ?></strong></td></tr>
                    <tr><td style="padding: 5px 0; color: #555;">Marital Status</td> <td><strong><?php echo display_value($member['profile']['marital_status'] ?? null); ?></strong></td></tr>
                </table>
            </div>

            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 20px;">
                <h3 style="margin-top: 0; border-bottom: 2px solid #ddd; padding-bottom: 10px;">Contact & Address</h3>
                <p style="margin: 5px 0;"><strong>Email:</strong> <?php echo display_value($member['contact']['email'] ?? null); ?></p>
                <p style="margin: 5px 0;"><strong>Primary Phone:</strong> <?php echo display_value($member['contact']['phone_no_1'] ?? null); ?></p>
                <p style="margin: 5px 0;"><strong>Secondary Phone:</strong> <?php echo display_value($member['contact']['phone_no_2'] ?? null); ?></p>
                
                <h4 style="margin: 15px 0 8px 0; color: #555; border-bottom: 1px solid #e0e0e0; padding-bottom: 3px;">Registered Address</h4>
                
                <?php if (empty($member['address']['town_city'])): ?>
                    <p style="margin: 0; color: #888; font-style: italic;">No address info recorded.</p>
                <?php else: ?>
                    <p style="margin: 0 0 5px 0; line-height: 1.4;">
                        <strong>Type:</strong> <?php echo display_value($member['address']['address_type'] ?? 'Home'); ?>
                    </p>
                    <p style="margin: 0; line-height: 1.5; font-size: 1.05em; color: #333;">
                        <?php 
                        // Compile elements dynamically, filtering empty values
                        $addressParts = array_filter([
                            $member['address']['house_number'] ?? '',
                            $member['address']['street'] ?? '',
                            !empty($member['address']['barangay']) ? 'Brgy. ' . $member['address']['barangay'] : '',
                            $member['address']['zone'] ?? '',
                            $member['address']['district'] ?? '',
                            $member['address']['town_city'] ?? '',
                            $member['address']['province'] ?? '',
                            $member['address']['region'] ?? ''
                        ], 'trim');

                        echo htmlspecialchars(implode(', ', $addressParts));
                        ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <div style="background: #fff5e6; padding: 20px; border-radius: 8px; border: 1px solid #ffe8cc; margin-bottom: 20px;">
                <h3 style="margin-top: 0; border-bottom: 2px solid #f5d8ab; padding-bottom: 10px; color: #b35900;">Beneficiaries</h3>
                <?php if (empty($member['beneficiaries'])): ?>
                    <p style="color: #888; font-style: italic;">No beneficiaries registered.</p>
                <?php else: ?>
                    <ul style="padding-left: 20px; margin: 0;">
                        <?php foreach ($member['beneficiaries'] as $ben): ?>
                            <li style="margin-bottom: 5px;">
                                <strong>
                                    <?php echo display_value($ben['first_name'] ?? '') . ' ' . display_value($ben['last_name'] ?? ''); ?>
                                </strong> 
                                <span style="color: #666;">(<?php echo display_value($ben['relation'] ?? null); ?>)</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div style="background: #eef7ff; padding: 20px; border-radius: 8px; border: 1px solid #cce5ff; margin-bottom: 20px;">
                <h3 style="margin-top: 0; border-bottom: 2px solid #b8daff; padding-bottom: 10px; color: #004085;">Employment / Experience</h3>
                <?php if (empty($member['experience'])): ?>
                    <p style="color: #888; font-style: italic;">No experience records found.</p>
                <?php else: ?>
                    <?php foreach ($member['experience'] as $exp): ?>
                        <div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px dashed #ccc;">
                            <strong><?php echo display_value($exp['job_title'] ?? null); ?></strong> at <?php echo display_value($exp['organization'] ?? null); ?><br>
                            <span style="font-size: 0.85em; color: #666;">
                                Duration: <?php echo display_value($exp['date_started'] ?? 'Unknown'); ?> to <?php echo display_value($exp['date_ended'] ?? 'Present'); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div style="background: #f3e5f5; padding: 20px; border-radius: 8px; border: 1px solid #e1bee7; margin-bottom: 20px;">
                <h3 style="margin-top: 0; border-bottom: 2px solid #ce93d8; padding-bottom: 10px; color: #4a148c;">Educational Background</h3>
                <?php if (empty($member['education'])): ?>
                    <p style="color: #888; font-style: italic;">No educational records found.</p>
                <?php else: ?>
                    <?php foreach ($member['education'] as $edu): ?>
                        <div style="margin-bottom: 10px;">
                            <strong><?php echo display_value($edu['program'] ?? null); ?></strong><br>
                            <?php echo display_value($edu['school_university'] ?? null); ?>
                            <span style="color: #666; font-size: 0.85em;">(Ended: <?php echo display_value($edu['date_ended'] ?? 'N/A'); ?>)</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>