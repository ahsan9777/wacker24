<?php include("../lib/session_head.php"); ?>
<!DOCTYPE html>
<html lang="de">

<head>
    <?php include("includes/html_header.php"); ?>
</head>

<body>
    <div class="container_main">
        <!-- Sidebar -->
        <?php include("includes/sidebar.php"); ?>

        <!-- Main content -->
        <div class="main-content">
            <!-- Top bar -->
            <?php include("includes/topbar.php"); ?>

            <!-- Content -->
            <section class="content" id="main-content">
                <div class="table-controls">
                    <h1 class="text-white">Customers</h1>
                    <button class="btn btn-success">Add new customer</button>

                </div>
                <div class="main_table_container">
                    <div class="row">
                        <div class=" col-md-4 col-12 mt-2">
                            <label for="" class="text-white">Search</label>
                            <input type="text" class="input_style" placeholder="Search:">
                        </div>
                    </div>

                    <div class="table_responsive">
                        <table class="table table-dark table-hover">
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
                                    <td>
                                        <input type="checkbox" data-style="android" data-toggle="toggle" data-size="lg"
                                            data-onstyle="success" data-width="70" data-height="30">

                                    </td>
                                    <td> <input type="checkbox" data-style="android" data-toggle="toggle" data-size="lg"
                                            data-onstyle="success"> </td>
                                    <td><span class="badge business">Business</span></td>
                                    <td>2024.10.31<br>09:00:24</td>
                                    <td><span class="badge business">Business</span></td>
                                    <td>2024.10.31<br>09:00:24</td>
                                    <td><span class="badge business">Business</span></td>
                                    <td>


                                        <input type="checkbox" id="myToggle" data-toggle="toggle" data-onstyle="success" data-offstyle = "danger" data-size = "sm">

                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="row my-5">
                    <div class="col-md-6 col-12 mt-3">
                        <input type="text" class="input_style" name="" id="">
                    </div>
                    <div class="col-md-6 col-12 mt-3">
                        <input type="text" class="input_style" name="" id="">
                    </div>
                    <div class="col-md-6 col-12 mt-3">
                        <input type="text" class="input_style" name="" id="">
                    </div>
                    <div class="col-md-6 col-12 mt-3">
                        <input type="text" class="input_style" name="" id="">
                    </div>
                    <div class="col-md-6 col-12 mt-3">
                        <input type="text" class="input_style" name="" id="">
                    </div>
                </div>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>

</html>