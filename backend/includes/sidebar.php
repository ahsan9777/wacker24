<?php
$pageName = basename($_SERVER["PHP_SELF"]);
$posName = strpos($pageName, '.php');
$pageName = (substr($pageName, 0, $posName));
$pg = $pageName;
$c = 'style="max-height: 118px;"';
$cu = 'active';
?>
<aside class="sidebar">

    <div class="logo">
        <img src="<?php print(config_site_logo)?>" style="width: 100%;" alt="">
    </div>

    <nav>
        <ul class="accordion-menu">
            <li>
                <a href="index.php" title="Dashboard Management" ><span class="material-icons icon">dashboard</span><span class="text">Dashboard</span></a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link accordion <?php echo ( $pg == 'manage_category' || $pg == 'manage_sub_category') ? $cu : ''; ?>"><span class="material-icons icon">category</span> <span class="text">Kategorien</span></a>
                <ul class="sub-menu panel" <?php echo ( $pg == 'manage_category' || $pg == 'manage_sub_category') ? $c : ''; ?>>
                    <li><a href="manage_category.php" title="Hauptkategorien Management" ><span class="text">Hauptkategorien</span></a></li>
                    <li><a href="manage_sub_category.php" title="Unterkategorien Management" ><span class="text">Unterkategorien</span></a></li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link accordion <?php echo ( $pg == 'manage_products' || $pg == 'manage_sales_system') ? $cu : ''; ?>"><span class="material-icons icon">add</span> <span class="text">Artikel</span></a>
                <ul class="sub-menu panel" <?php echo ( $pg == 'manage_products' || $pg == 'manage_sales_system') ? $c : ''; ?>>
                    <li><a href="manage_products.php" title="Artical Management"><span class="text">View All Artical</span></a></li>
                    <li><a href="manage_sales_system.php" title="Sales System Management"><span class="text">Sales System</span></a></li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link accordion <?php echo ( $pg == 'manage_appointment_category' || $pg == 'manage_appointment_schedule' || $pg == 'manage_appointment_holidays') ? $cu : ''; ?>"><span class="material-icons icon">schedule</span> <span class="text">Appointment</span></a>
                <ul class="sub-menu panel" <?php echo ( $pg == 'manage_appointment_category' || $pg == 'manage_appointment_schedule' || $pg == 'manage_appointment_holidays') ? $c : ''; ?>>
                    <li><a href="manage_appointment_category.php" title="Category Management"><span class="text">Category</span></a></li>
                    <li><a href="manage_appointment_schedule.php" title="Schedule Management"><span class="text">Schedule</span></a></li>
                    <li><a href="manage_appointment_holidays.php" title="Holidays Management"><span class="text">Holidays</span></a></li>
                </ul>
            </li>
            

            <li>
                <a href="manage_users.php" title="Kunden Management" ><span class="material-icons icon">face</span> <span class="text">Kunden</span> </a>
            </li>
            <li>
                <a href="manage_brands.php" title="Marken Management" ><span class="material-icons icon">star</span> <span class="text">Marken</span> </a>
            </li>
            <li>
                <a href="manage_orders.php" title="Auftragsverwaltung Management" ><span class="material-icons icon">inventory</span> <span class="text">Auftragsverwaltung</span> </a>
            </li>
        </ul>
        <ul class="accordion-menu border-top border-black">
            <li>
                <a href="manage_admin_users.php" title="Benutzerverwaltung Management" ><span class="material-icons icon">admin_panel_settings</span><span class="text">Benutzerverwaltung</span></a>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link accordion <?php echo ( $pg == 'manage_banner' || $pg == 'manage_site_setting') ? $cu : ''; ?>"><span class="material-icons icon">settings</span> <span class="text">CMS</span></a>
                <ul class="sub-menu panel" <?php echo ( $pg == 'manage_banner' || $pg == 'manage_site_setting') ? $c : ''; ?>>
                    <li><a href="manage_banner.php" title="Banner Management" ><span class="text" >Banner</span></a></li>
                    <li><a href="manage_site_setting.php" title="Site Setting Management" ><span class="text" >Site Setting</span></a></li>
                </ul>
            </li>
            <li>
                <a href="manage_payment_methods.php" title="Payment Method Management" ><span class="material-icons icon">payments</span><span class="text">Zahlungsarten</span></a>
            </li>
        </ul>
    </nav>
</aside>