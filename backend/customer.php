<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Dashboard</title>
    <link rel="stylesheet" href="./assets/style/styles.css">
    <link rel="stylesheet" href="./assets/style/scrollbar.css">
    <link rel="stylesheet" href="./assets/style/responsive.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">

            <div class="logo">
                <img src="./assets/images/logo.png" style="width: 100%;" alt="">
            </div>

            <nav>
                <ul class="accordion-menu">

                    <li>
                        <a href="backend-cpanel"><span class="material-icons icon">dashboard</span>
                            <span class="text">Dashboard</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="#" class="menu-link accordion">
                            <span class="material-icons icon">category</span>
                            <span class="text">Kategorien</span>
                        </a>
                        <ul class="sub-menu panel">
                            <li><a href="categoriesManagmentSystem"><span class="text">Hauptkategorien</span></a></li>
                            <li><a href="subCategoriesOneManagmentSystem"><span class="text">Unterkategorien</span></a>
                            </li>
                            <li><a href="ArtikelTypes"><span class="text">Artikel Types</span></a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link accordion">
                            <span class="material-icons icon">category</span>
                            <span class="text">Kategorien</span>
                        </a>
                        <ul class="sub-menu panel">
                            <li><a href="categoriesManagmentSystem"><span class="text">Hauptkategorien</span></a></li>
                            <li><a href="subCategoriesOneManagmentSystem"><span class="text">Unterkategorien</span></a>
                            </li>
                            <li><a href="ArtikelTypes"><span class="text">Artikel Types</span></a></li>
                        </ul>
                    </li>

                    <li><a href="usersManagmentSystem"><span class="material-icons icon">face</span> <span
                                class="text">Kunden</span> </a></li>

            </nav>
        </aside>

        <!-- Main content -->
        <div class="main-content">
            <!-- Top bar -->
            <header class="top-bar">
                <div class="left" style="display: flex; gap: 15px;">
                    <button class="back-button sidebar-toggle">
                        <span class="material-icons">menu</span>
                    </button>
                    <button class="back-button toggle-sidebar desktop"> <span class="material-icons">first_page</span>
                    </button>
                    <span>View Site</span>
                </div>
                <div class="right" style="display: flex; gap: 15px;">
                    <button class="back-button">Dashboard</button>
                    <button class="back-button">Logout</button>
                    <span>
                        <img src="./assets/images/germany.png" width="30px" height="30px" alt="German flag"
                            class="flag" />
                    </span>

                </div>
            </header>

            <!-- Content -->
            <section class="content" id="main-content">
                <div class="main_container">
                    <h2>
                        Add New Customer
                    </h2>
                    <form action="">
                        <div class="input_div">
                            <label for="">Customer Type</label>
                            <select name="" class="input_style" id="">
                                <option> Private </option>
                                <option> Private </option>
                                <option> Private </option>
                            </select>
                        </div>
                        <div class="grid_form">
                            <div class="input_div">
                                <label for="">Customer Type</label>
                                <select name="" class="input_style" id="">
                                    <option> Private </option>
                                    <option> Private </option>
                                    <option> Private </option>
                                </select>
                            </div>
                            <div class="input_div">
                                <label for="">Customer Type</label>
                                <select name="" class="input_style" id="">
                                    <option> Private </option>
                                    <option> Private </option>
                                    <option> Private </option>
                                </select>
                            </div>
                        </div>
                        <div class="grid_form">
                            <div class="input_div">
                                <label for="">Customer Type</label>
                                <div class="">
                                    <label for="file-upload" class="upload-btn">
                                        <span class="material-icons">cloud_upload</span>
                                        <span>Upload Files</span>
                                    </label>
                                    <input id="file-upload" type="file" class="file-input" multiple>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </section>
        </div>
    </div>
    <script src="./assets/js/jquery.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>