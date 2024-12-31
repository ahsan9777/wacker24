<?php include("../lib/session_head.php"); ?>
<!DOCTYPE html>
<html lang="de">

<head>
    <?php include("includes/html_header.php"); ?>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <?php include("includes/sidebar.php"); ?>

        <!-- Main content -->
        <div class="main-content">
            <!-- Top bar -->
            <?php include("includes/topbar.php"); ?>

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