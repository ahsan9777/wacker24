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
                <?php if ($class != "") { ?>
                    <div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
                <?php } ?>
                <style>
                    .tab_container {
                        text-decoration: none;
                        display: flex;
                        flex-direction: row;
                        gap: 10px;
                        align-items: center;
                        color: #000;
                    }

                    .tab_img {
                        width: 40px;

                    }

                    .tab_img img {
                        width: 100%;
                    }

                    .contact_detail {
                        display: flex;
                        flex-direction: column;
                        gap: 30px;
                        top: 10px;

                    }

                    .contact_detail .contact_user_info {
                        display: flex;
                        flex-direction: column;
                        gap: 15px;
                    }

                    .contact_detail_user_info {
                        display: flex;
                        flex-direction: row;
                        gap: 10px;
                        align-items: center;
                    }

                    .contact_detail_user_info .user_info_detail {
                        display: flex;
                        flex-direction: column;
                        gap: 10px;
                    }
                </style>
                <div class="container text-start">
                    <h2 class="text-white">
                        Kontakt Formular
                    </h2>
                    <div class="row mt-3 position-relative">
                        <div class="col-md-3 col-12 bg-secondary rounded-3">
                            <?php
                            $Query1 = "SELECT * FROM contact_us_request ORDER BY cu_date DESC";
                            $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
                            if (mysqli_num_rows($rs1)) {
                                while ($row1 = mysqli_fetch_object($rs1)) {
                            ?>
                                <a href="javascript:void(0);" class="tab_container p-3 border-bottom">
                                    <div class="tab_img rounded">
                                        <img src="../images/user_img.png" alt="">
                                    </div>
                                    <div class="tab_detail">
                                        <div class="user_detail">
                                            <?php 
                                            print($row1->cu_name);
                                            if($row1->cu_is_viewed > 0){
                                                print('<span class="ms-2 p-2 mb-3 text-bg-success rounded-3"> Close</span>');
                                            } else{
                                                print('<span class="ms-2 p-2 mb-3 text-bg-danger rounded-3"> Open</span>');
                                            }
                                            ?> 
                                        </div>
                                        <div class="contact_date">
                                            <?php print($row1->cu_date); ?>
                                        </div>
                                    </div>
                                </a>
                            <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="col-md-9 col-12 bg-dark rounded-3 p-3">
                            <div class="contact_detail position-sticky">
                                <div class="contact_user_info border-bottom pb-3">
                                    <div class="contact_date">
                                        Mon 07, 2025 09:33
                                    </div>
                                    <h3 class="from text-white">
                                        From: Test data
                                    </h3>
                                    <div class="contact_detail_user_info">
                                        <div class="tab_img rounded">
                                            <img src="../images/user_img.png" alt="">
                                        </div>
                                        <div class="user_info_detail">
                                            <div class="user_detail">
                                                ahsannawaz9777@gmail.com
                                            </div>
                                            <div class="contact_date">
                                                To: <span class="ms-1 p-1 mb-3 text-bg-info rounded-3 text-white"> Wacker</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="contact_message border-bottom pb-3">
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Enim illum minus, laudantium ea ullam deserunt quo possimus quos reiciendis? Maxime, voluptatibus, commodi saepe odio incidunt placeat sapiente corrupti repellendus ab magnam aperiam quibusdam rem cupiditate dignissimos perspiciatis dolorem voluptatem maiores. Ea hic ullam aperiam aspernatur rem minima officia in magnam sint minus, similique corporis officiis! Eligendi hic tempora doloremque? Culpa repellat dolorum commodi ut voluptatum eius saepe voluptatem iste nisi rem. Praesentium illum esse corporis, quas rem, velit suscipit expedita harum blanditiis eligendi, impedit ad vel consequuntur alias! Est deserunt accusamus libero commodi perferendis at, ad tempore officia eius iste.
                                </div>
                                <div class="contact_name border-bottom pb-3 fs-5 text-white">
                                    Name: Hafiz Ahsan Nawaz
                                </div>
                                <div class="contact_email border-bottom pb-3 fs-5 text-white">
                                    Email: ahsannawaz9777@gmail.com
                                </div>
                                <div class="contact_phone border-bottom pb-3 fs-5 text-white">
                                    Phone: 03347474009
                                </div>
                                <div class="contact_topic border-bottom pb-3 fs-5 text-white">
                                    Topic: Bitte auswählen
                                </div>
                                <div class="bottom_btndelete text-end">
                                    <a href="" class="btn btn-danger btn-style-light w-auto">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>
<script>
    $('input.brand_name').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=brand_name',
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
            var brand_id = $("#brand_id");
            var brand_name = $("#brand_name");
            $(brand_id).val(ui.item.brand_id);
            $(brand_name).val(ui.item.value);
            //frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>