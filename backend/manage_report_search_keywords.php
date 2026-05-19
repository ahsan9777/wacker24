<?php
include("../lib/session_head.php");

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
                        <h1 class="text-white">Search Keyword Report</h1>
                        
                    </div>
                    <div class="d-grid gap-3" style="grid-template-columns: 1fr 1fr;">
                            <div class="main_table_container">
                                <?php
                                $date_from = "";
                                $date_to = "";
                                $searchQuery = "";

                                if (( isset($_REQUEST['date_from']) && !empty($_REQUEST['date_from'])) && (isset($_REQUEST['date_to']) && !empty($_REQUEST['date_to']))) {
                                        $date_from = $_REQUEST['date_from'];
                                        $date_to = $_REQUEST['date_to'];
                                        $qryStrURL = "date_from=".$_REQUEST['date_from']."&date_to=".$_REQUEST['date_to']."&";
                                        $searchQuery .= " AND DATE(sk_cdate) BETWEEN  '" .$date_from. "' AND '".$date_to."'";
                                } elseif (isset($_REQUEST['date_from']) && !empty($_REQUEST['date_from'])) {
                                        $date_from = $_REQUEST['date_from'];
                                        $qryStrURL = "date_from=".$_REQUEST['date_from']."&";
                                        $searchQuery .= " AND DATE(sk_cdate)  >= '" .$date_from. "' ";
                                } elseif (isset($_REQUEST['date_to']) && !empty($_REQUEST['date_to'])) {
                                        
                                        $date_to = $_REQUEST['date_to'];
                                        $qryStrURL = "date_to=".$_REQUEST['date_to']."&";
                                        $searchQuery .= " AND DATE(sk_cdate) <= '".$date_to."'";
                                }
                                ?>
                                <form class="row flex-row" name="frm_search" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                                <div class=" col-md-4 col-12 mt-2">
                                    <label for="" class="text-white">From</label>
                                    <input type="date" class="input_style" name="date_from" id="date_from" value="<?php print($date_from); ?>" autocomplete="off" onchange="javascript: frm_search.submit();">
                                </div>
                                <div class=" col-md-4 col-12 mt-2">
                                    <label for="" class="text-white">To</label>
                                    <input type="date" class="input_style" name="date_to" id="date_to" value="<?php print($date_to); ?>" autocomplete="off" onchange="javascript: frm_search.submit();">
                                </div>
                            </form>
                            <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                                <table>
                                    <thead>
                                        <tr>
                                            <th >Keyword</th>
                                            <th width="150">Keyword Volume</th>
                                            <th width="50">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $keyword_data = "";
                                        $kewword_count_data = "";
                                        $Query = "SELECT COUNT(sk_data) AS data_count, sk_data FROM search_keyword WHERE sk_data != '' ".$searchQuery." GROUP BY sk_data ORDER BY data_count DESC";
                                        //print($Query);
                                        $counter = 0;
                                        $limit = 13;
                                        $start = $p->findStart($limit);
                                        $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                        $pages = $p->findPages($count, $limit);
                                        $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                        if (mysqli_num_rows($rs) > 0) {
                                            while ($row = mysqli_fetch_object($rs)) {
                                                $counter++;
                                                $strClass = 'label  label-danger';
                                                $keyword_data .=  "'".$row->sk_data."', ";
                                                $kewword_count_data .=  $row->data_count.", ";
                                        ?>
                                                <tr>
                                                    <td><?php print($row->sk_data); ?></td>
                                                    <td><?php print($row->data_count); ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-xs btn-success btn-style-light w-auto" target="_blank" title="View" onClick="javascript: window.open ('<?php print($GLOBALS['siteURL'] ."search_result.php?level_one=0&supplier_id=0&search_keyword=".urlencode($row->sk_data)); ?>');"><span class="material-icons icon material-xs">visibility</span></button>
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
                         <div class="main_table_container chart-container">
                            <canvas id="myChart"></canvas>
                         </div>
                    </div>
                    
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>
<style>
.chart-container{
    position: relative;
}

#myChart{
    width: 100% !important;
    height: 100% !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const DATA_COUNT = 10;

  const labels = [<?php print(rtrim($keyword_data, ', ')); ?>];

  const data = {
    labels: labels,
    datasets: [
      {
        label: 'Keyword Volume',
        data: [<?php print(rtrim($kewword_count_data, ', ')); ?>],
        borderColor: 'rgba(99, 255, 133, 0.5)',
        backgroundColor: 'rgba(99, 255, 133, 0.5)',
        borderWidth: 1
      }
    ]
  };

  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: data,
    options: {
      responsive: true,
       indexAxis: 'y',
       maintainAspectRatio: false, // important
        scales: {
            x: {
                beginAtZero: true
            }
        },
         plugins: {
            legend: {
                labels: {
                    color: 'white' // legend label color
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    color: 'white' // x-axis label color
                },
                grid: {
                    color: 'rgba(255,255,255,0.1)'
                }
            },
            y: {
                ticks: {
                    color: 'white' // y-axis label color
                },
                grid: {
                    color: 'rgba(255,255,255,0.1)'
                }
            }
        }
    }
  });
</script>
</html>