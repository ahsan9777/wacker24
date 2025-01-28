<?php
include("../lib/session_head.php");


//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE appointments SET app_status='1' WHERE app_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}
//--------------Button InActive--------------------
if (isset($_REQUEST['btnInactive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE appointments SET app_status='0' WHERE app_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}

//--------------Button Delete--------------------
if (isset($_REQUEST['btnDelete'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {

            mysqli_query($GLOBALS['conn'], "DELETE FROM appointments WHERE app_id = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($_REQUEST['conn']));
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) deleted successfully";
    } else {
        $class = " alert alert-info ";
        $strMSG = "Please check atleast one checkbox";
    }
}

include("includes/messages.php");

?>
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
                <?php if ($class != "") { ?>
                    <div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
                <?php } ?>
                <div class="table-controls">
                    <h1 class="text-white">Appointment Management</h1>
                </div>
                <div class="main_table_container">
                    <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                        <table>
                            <thead>
                                <tr>
                                    <th width="50"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                    <th>Appointment</th>
                                    <th>Info</th>
                                    <th>Address</th>
                                    <th>Time</th>
                                    <th>Date</th>
                                    <th>Remarks</th>
                                    <th width="50">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Query = "SELECT app.*, asch.as_title_de AS as_title FROM appointments AS app LEFT OUTER JOIN appointment_schedule AS asch ON asch.as_id = app.as_id ORDER BY app.app_date DESC";
                                //print($Query);
                                $counter = 0;
                                $limit = 25;
                                $start = $p->findStart($limit);
                                $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                $pages = $p->findPages($count, $limit);
                                $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                if (mysqli_num_rows($rs) > 0) {
                                    while ($row = mysqli_fetch_object($rs)) {
                                        $counter++;
                                        $strClass = 'label  label-danger';
                                        $appointment_info = "";
                                        if ($row->app_gender == 1) {
                                            $appointment_info .= "Male <br>";
                                        } elseif ($row->app_gender == 2) {
                                            $appointment_info .= "Female<br>";
                                        } else {
                                            $appointment_info .= "Other<br>";
                                        }
                                        if (!empty($row->app_fname)) {
                                            $appointment_info .= $row->app_fname . " " . $row->app_lname . "<br>";
                                        }
                                        if (!empty($row->app_contactno)) {
                                            $appointment_info .= $row->app_contactno . "<br>";
                                        }
                                        if (!empty($row->app_email)) {
                                            $appointment_info .= $row->app_email . "<br>";
                                        }

                                        $appointment_address = "";
                                        if (!empty($row->app_zipcode)) {
                                            $appointment_address .= $row->app_zipcode . "<br>";
                                        }
                                        if (!empty($row->app_street)) {
                                            $appointment_address .= $row->app_street . "<br>";
                                        }
                                        if (!empty($row->app_place)) {
                                            $appointment_address .= $row->app_place . "<br>";
                                        }
                                ?>
                                        <tr>
                                            <td><input type="checkbox" name="chkstatus[]" value="<?php print($row->app_id); ?>"></td>
                                            <td><?php print($row->as_title); ?></td>
                                            <td><?php print($appointment_info); ?></td>
                                            <td><?php print($appointment_address); ?></td>
                                            <td><?php print($row->app_time); ?></td>
                                            <td><?php print(date('D F j, Y', strtotime($row->app_date))); ?></td>
                                            <td><?php print($row->app_remarks); ?></td>
                                            <td>
                                                <?php
                                                if ($row->app_status == 0) {
                                                    echo '<span class="btn btn-warning btn-style-light w-auto">Pending</span>';
                                                } else {
                                                    echo '<span class="btn btn-success btn-style-light w-auto">Confirmed</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    print('<tr><td colspan="100%" class="text-center">No record found!</td></tr>');
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php if ($counter > 0) { ?>
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td><?php print("Page <b>" . $_GET['page'] . "</b> of " . $pages); ?></td>
                                    <td style="float: right;">
                                        <ul class="pagination" style="margin: 0px;">
                                            <?php
                                            $pageList = $p->pageList($_GET['page'], $pages, '&' . $qryStrURL);
                                            print($pageList);
                                            ?>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>

                        <div class="row">
                            <div class=" col-md-1 col-12 mt-2">
                                <input type="submit" name="btnInactive" value="Pending" class="btn btn-warning btn-style-light w-auto">
                            </div>
                            <div class=" col-md-1 col-12 mt-2">
                                <input type="submit" name="btnActive" value="Confirmed" class="btn btn-success btn-style-light w-auto">
                            </div>
                            <div class=" col-md-1 col-12 mt-2">
                                <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-style-light w-100" onclick="return confirm('Are you sure you want to delete selected item(s)?');">
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>

</html>