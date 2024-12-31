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
        <img src="./assets/images/logo.png" style="width: 100%;" alt="">
    </div>

    <nav>
        <ul class="accordion-menu">

            <li>
                <a href="index.php"><span class="material-icons icon">dashboard</span>
                    <span class="text">Dashboard</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link accordion <?php echo ( $pg == 'manage_import_category') ? $cu : ''; ?>"><span class="material-icons icon">category</span> <span class="text">Kategorien</span></a>
                <ul class="sub-menu panel" <?php echo ( $pg == 'manage_import_category') ? $c : ''; ?>>
                    <li><a href="manage_import_category.php"><span class="text">Hauptkategorien</span></a></li>
                    <li><a href="subCategoriesOneManagmentSystem"><span class="text">Unterkategorien</span></a></li>
                    <li><a href="ArtikelTypes"><span class="text">Artikel Types</span></a></li>
                </ul>
            </li>
            

            <li><a href="usersManagmentSystem"><span class="material-icons icon">face</span> <span class="text">Kunden</span> </a></li>

    </nav>
</aside>