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
                            <li><a href="subCategoriesOneManagmentSystem"><span class="text">Unterkategorien</span></a></li>
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
                <div class="main_table_container">
                    <div class="table-controls">


                        <div class="search-box">
                            <input type="text" class="input_style" placeholder="Search:">
                        </div>
                    </div>

                    <div class="table-controls">
                        <h1>Customers</h1>
                        <button class="add-customer">Add new customer</button>

                    </div>
                    <div class="table_responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>customer number</th>
                                    <th>Full Name</th>
                                    <th>E-mail</th>
                                    <th>zip code</th>
                                    <th>Street</th>
                                    <th>type</th>
                                    <th>Created</th>
                                    <th>type</th>
                                    <th>Created</th>
                                    <th>type</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td>1</td>
                                    <td>sayed Ka</td>
                                    <td>red25558@gmail.com</td>
                                    <td></td>
                                    <td></td>
                                    <td><span class="badge business">Business</span></td>
                                    <td>2024.10.31<br>09:00:24</td>
                                    <td><span class="badge business">Business</span></td>
                                    <td>2024.10.31<br>09:00:24</td>
                                    <td><span class="badge business">Business</span></td>
                                    <td>2024.10.31<br>09:00:24</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </section>
        </div>
    </div>
    <script src="./assets/js/jquery.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>