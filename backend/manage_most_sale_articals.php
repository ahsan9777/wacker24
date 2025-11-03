<?php
include("../lib/session_head.php");
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
                <div class="table-controls">
                        <h1 class="text-white">Most Sale Articles</h1>
                    </div>
                    <div class="main_table_container">
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="100">Image</th>
                                        <th>Title</th>
                                        <th width="100">Sales Count</th>
                                        <th width="50">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $Query = "SELECT oi.*, pro.pro_description_short, pg.pg_mime_source_url, pro.pro_custom_add, COUNT(oi.supplier_id) AS sales_count FROM order_items AS oi LEFT OUTER JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = oi.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_order ASC LIMIT 1) WHERE oi.supplier_id != '' GROUP BY oi.supplier_id ORDER BY sales_count DESC ";
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
                                            $image_path = $GLOBALS['siteURL'] . "files/no_img_1.jpg";
                                            if ($row->pro_custom_add > 0) {
                                                $image_path = $GLOBALS['siteURL'] . $row->pg_mime_source_url;
                                            } elseif (!empty($row->pg_mime_source_url)) {
                                                $image_path = get_image_link(160, $row->pg_mime_source_url);
                                            }
                                    ?>
                                            <tr>
                                                <td>
                                                    <div class="popup_container" style="width:100px">
                                                        <div class="container__img-holder">
                                                            <img src="<?php print($image_path); ?>">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php print($row->pro_description_short); ?></td>
                                                <td><?php print($row->sales_count); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-success btn-style-light w-auto" target="_blank" title="View" onClick="javascript: window.open ('<?php print(product_detail_url($row->supplier_id)); ?>');"><span class="material-icons icon material-xs">visibility</span></button>
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
                        </form>

                    </div>
               
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>
<script>
    $('input.fp_title_de').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=fp_title_de',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);

                }
            });
        },
        minLength: 1,
        select: function(event, ui) {
            var fp_id = $("#fp_id");
            var fp_title_de = $("#fp_title_de");
            $(fp_id).val(ui.item.fp_id);
            $(fp_title_de).val(ui.item.value);
            frm_search.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " fp_id " + ui.item.fp_id );
        }
    });
    $(document).ready(function() {
        // Listen for toggle changes
        $('.manf_showhome').change(function() {
            let id = $(this).attr('data-id');
            let set_field_data = 0;
            //console.log("cat_id: "+cat_id)
            if ($(this).prop('checked')) {
                set_field_data = 1;
            }
            //console.log("set_field_data: "+set_field_data);
            $.ajax({
                url: 'ajax_calls.php?action=btn_toggle',
                method: 'POST',
                data: {
                    table: "free_product",
                    set_field: "manf_showhome",
                    set_field_data: set_field_data,
                    where_field: "fp_id",
                    id: id
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    console.log(obj);
                    if (obj.status == 1 && set_field_data == 1) {
                        $.toast({
                            heading: 'Success',
                            text: 'Toggle is ON',
                            icon: 'success',
                            position: 'top-right'
                        });
                    } else if (obj.status == 1 && set_field_data == 0) {
                        $.toast({
                            heading: 'Warning',
                            text: 'Toggle is OFF',
                            icon: 'warning',
                            position: 'top-right'
                        });
                    }
                }
            });
        });
    });
</script>

</html>