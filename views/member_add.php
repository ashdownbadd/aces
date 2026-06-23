<?php
// views/member_add.php
if (!defined('ALLOW_ACCESS')) {
    die('Direct access to this file is prohibited.');
}
?>

<div class="container" style="font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: #fff;">
    <p><a href="index.php?route=members" style="text-decoration: none; color: #337ab7; font-weight: bold;">← Back to Members Registry</a></p>

    <h2 style="margin-bottom: 5px;">Register New Cooperative Member</h2>
    <p style="color: #666; font-size: 0.95em; margin-top: 0; margin-bottom: 20px;">Complete the comprehensive intake form to establish a new shareholder portfolio.</p>
    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 20px;">

    <?php if (isset($_SESSION['error_message'])): ?>
        <p style="color: #721c24; background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px;">
            <?php echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']); ?>
        </p>
    <?php endif; ?>

    <form action="index.php?route=add_coop_member" method="POST">

        <div style="background: #f9f9f9; padding: 15px; border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px;">
            <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #ddd; padding-bottom: 5px;">Personal Identity</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">First Name *</label>
                    <input type="text" name="first_name" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Middle Name</label>
                    <input type="text" name="middle_name" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Last Name *</label>
                    <input type="text" name="last_name" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>
        </div>

       <div style="background: #f9f9f9; padding: 15px; border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px;">
            <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #ddd; padding-bottom: 5px;">Demographics</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Date of Birth</label>
                    <input type="date" name="date_of_birth" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Sex</label>
                    <select name="sex" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">Select...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Marital Status</label>
                    <select name="marital_status" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">Select...</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Divorced">Divorced</option>
                    </select>
                </div>
            </div>
        </div>

        <div style="background: #f9f9f9; padding: 15px; border: 1px solid #eee; border-radius: 6px; margin-bottom: 20px;">
            <h3 style="margin-top: 0; color: #333; border-bottom: 2px solid #ddd; padding-bottom: 5px;">Contact & Location</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Email Address</label>
                    <input type="email" name="email" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Primary Phone</label>
                    <input type="text" name="phone_no_1" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Secondary Phone</label>
                    <input type="text" name="phone_no_2" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">House Number</label>
                    <input type="text" name="house_number" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Street</label>
                    <input type="text" name="street" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Barangay</label>
                    <input type="text" name="barangay" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Town / City</label>
                    <input type="text" name="town_city" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Province</label>
                    <input type="text" name="province" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Region</label>
                    <input type="text" name="region" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>
        </div>

        <button type="submit" style="background: #28a745; color: white; padding: 12px 20px; font-size: 1.1em; font-weight: bold; border: none; border-radius: 4px; cursor: pointer; width: 100%;">
            Register Cooperative Member
        </button>

    </form>
</div>