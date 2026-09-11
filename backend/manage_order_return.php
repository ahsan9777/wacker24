<?php
include("../lib/session_head.php");
include("../lib/dhl_api.php");

$reason_for_rejection = [
    1 => [
        'title' => 'Rückgabefrist abgelaufen',
        'message' => 'Die für diesen Artikel geltende Rückgabe- beziehungsweise Widerrufsfrist ist bereits abgelaufen. Eine Rücknahme über das reguläre Rücksendeverfahren ist daher leider nicht mehr möglich. Ihre gesetzlichen Rechte bei einem möglichen Mangel bleiben hiervon unberührt.'
    ],

    2 => [
        'title' => 'Kundenspezifisch angefertigter Artikel',
        'message' => 'Der Artikel wurde nach Ihren individuellen Vorgaben angefertigt oder personalisiert und ist deshalb von der regulären Rückgabe ausgeschlossen. Sollte der Artikel mangelhaft oder nicht wie vereinbart geliefert worden sein, wenden Sie sich bitte an unseren Kundenservice.'
    ],

    3 => [
        'title' => 'Versiegelung wurde entfernt',
        'message' => 'Bei diesem Artikel wurde die für eine Rückgabe erforderliche Versiegelung nach der Lieferung entfernt. Aufgrund der Art des Artikels ist eine Rückgabe deshalb leider ausgeschlossen. Ihre gesetzlichen Rechte bei einem möglichen Mangel bleiben hiervon unberührt.'
    ],

    4 => [
        'title' => 'Voraussetzungen der freiwilligen Rücknahme nicht erfüllt',
        'message' => 'Die Voraussetzungen unserer freiwilligen Rücknahme sind in diesem Fall leider nicht erfüllt. Ihre gesetzlichen Rechte, insbesondere bei einem bereits bei der Lieferung vorhandenen Mangel, bleiben hiervon unberührt.'
    ],

    5 => [
        'title' => 'Artikelzustand erfüllt die Rücknahmebedingungen nicht',
        'message' => 'Nach den vorliegenden Angaben entspricht der Zustand des Artikels nicht den Voraussetzungen unserer freiwilligen Rücknahme. Eine Rücknahme können wir daher leider nicht bestätigen. Sollte ein bereits bei der Lieferung vorhandener Mangel vorliegen, wenden Sie sich bitte an unseren Kundenservice.'
    ],

    6 => [
        'title' => 'Rücksendemenge kann nicht bestätigt werden',
        'message' => 'Die angegebene Rücksendemenge stimmt nicht mit der gelieferten beziehungsweise noch für eine Rücksendung verfügbaren Menge überein. Bitte prüfen Sie die Mengenangabe und stellen Sie gegebenenfalls eine neue Rücksendeanfrage.'
    ],

    7 => [
        'title' => 'Rücksendeanfrage bereits vorhanden',
        'message' => 'Für diesen Artikel liegt bereits eine Rücksendeanfrage vor. Eine weitere Anfrage ist daher nicht erforderlich. Den aktuellen Bearbeitungsstand finden Sie in Ihrem Kundenkonto unter „Meine Bestellungen“.'
    ],

    8 => [
        'title' => 'Rücksendung bereits abgeschlossen',
        'message' => 'Die Rücksendung dieses Artikels wurde bereits bearbeitet und abgeschlossen. Eine erneute Rücksendeanfrage für dieselbe Artikelmenge können wir daher leider nicht bestätigen.'
    ],

    9 => [
        'title' => 'Angaben nicht ausreichend oder widersprüchlich',
        'message' => 'Auf Grundlage der übermittelten Angaben können wir die Rücksendeanfrage leider nicht eindeutig prüfen. Bitte kontrollieren Sie Ihre Angaben und stellen Sie anschließend eine neue Rücksendeanfrage. Alternativ unterstützt Sie unser Kundenservice gerne bei der Klärung.'
    ],

    10 => [
        'title' => 'Individueller Ablehnungsgrund',
        'message' => '[Der Mitarbeiter muss hier eine konkrete, sachliche und für den Kunden verständliche Begründung eintragen.]'
    ],

    11 => [
        'title' => 'Artikel kann der Bestellung nicht zugeordnet werden',
        'message' => 'Der in Ihrer Rücksendeanfrage angegebene Artikel kann der genannten Bestellung beziehungsweise Lieferung nicht eindeutig zugeordnet werden. Bitte überprüfen Sie die Bestell- und Artikelangaben und stellen Sie gegebenenfalls eine neue Rücksendeanfrage. Unser Kundenservice unterstützt Sie gerne bei der Zuordnung.'
    ],

    12 => [
        'title' => 'Set oder Artikelpaket nicht vollständig ausgewählt',
        'message' => 'Der Artikel wurde als zusammengehöriges Set beziehungsweise Artikelpaket geliefert. Die von Ihnen ausgewählte Teilmenge erfüllt die Voraussetzungen unserer freiwilligen Rücknahme leider nicht. Bitte stellen Sie eine neue Rücksendeanfrage für das vollständige Set beziehungsweise Artikelpaket.'
    ],

    13 => [
        'title' => 'Speziell beschaffter Artikel – nur für freiwillige Rücknahme',
        'message' => 'Der Artikel wurde speziell für Ihre Bestellung beschafft und ist von unserer freiwilligen Rücknahme ausgeschlossen. Ihre gesetzlichen Rechte, insbesondere bei einem Mangel oder einer Falschlieferung, bleiben hiervon unberührt.'
    ],

    14 => [
        'title' => 'Hygiene- oder Gesundheitsartikel entsiegelt',
        'message' => 'Bei diesem versiegelten Artikel wurde die Versiegelung nach der Lieferung entfernt. Da der Artikel aus Gründen des Gesundheitsschutzes oder der Hygiene anschließend nicht mehr zur Rückgabe geeignet ist, können wir die Rücksendeanfrage leider nicht bestätigen. Ihre gesetzlichen Rechte bei einem möglichen Mangel bleiben hiervon unberührt.'
    ],

    15 => [
        'title' => 'Versiegelte Software oder Datenträger geöffnet',
        'message' => 'Die Versiegelung der gelieferten Software beziehungsweise des Datenträgers wurde nach der Lieferung entfernt. Eine reguläre Rückgabe ist deshalb leider ausgeschlossen. Sollte ein Mangel vorliegen, wenden Sie sich bitte an unseren Kundenservice.'
    ],

    16 => [
        'title' => 'Artikel untrennbar mit anderen Produkten verbunden oder vermischt',
        'message' => 'Nach den übermittelten Angaben wurde der Artikel nach der Lieferung untrennbar mit anderen Produkten verbunden oder vermischt. Eine Rückgabe des ursprünglichen Artikels ist daher leider nicht mehr möglich. Ihre gesetzlichen Rechte bei einem bereits bei der Lieferung vorhandenen Mangel bleiben hiervon unberührt.'
    ],

    17 => [
        'title' => 'Schnell verderbliche Ware oder überschrittenes Verfallsdatum',
        'message' => 'Der Artikel ist aufgrund seiner Beschaffenheit beziehungsweise seines begrenzten Verfallsdatums von der regulären Rückgabe ausgeschlossen. Eine Rücksendeanfrage können wir daher leider nicht bestätigen. Ihre gesetzlichen Rechte bei einem möglichen Mangel bleiben hiervon unberührt.'
    ],
];


$reason_for_return_rejection = [
    1 => [
        'title' => 'Falscher beziehungsweise nicht zugehöriger Artikel zurückgesendet',
        'message' => 'Der zurückgesendete Artikel stimmt nicht mit dem Artikel überein, der im Rahmen der angegebenen Bestellung geliefert wurde.'
    ],

    2 => [
        'title' => 'Artikelidentifikation stimmt nicht überein',
        'message' => 'Die Artikel-, Chargen- beziehungsweise Seriennummer des zurückgesendeten Artikels stimmt nicht mit dem ursprünglich gelieferten Artikel überein.'
    ],

    3 => [
        'title' => 'Angemeldeter Artikel nicht in der Rücksendung enthalten',
        'message' => 'Der zur Rücksendung angemeldete Artikel beziehungsweise die angegebene Menge war in der bei uns eingegangenen Sendung nicht enthalten.'
    ],

    4 => [
        'title' => 'Artikel oder Menge bereits bearbeitet',
        'message' => 'Der zurückgesendete Artikel beziehungsweise die zurückgesendete Menge wurde bereits im Rahmen eines früheren Rücksendevorgangs bearbeitet. Eine erneute Gutschrift oder Ersatzlieferung ist daher nicht möglich.'
    ],

    5 => [
        'title' => 'Beanstandeter Fehler nicht feststellbar',
        'message' => 'Der von Ihnen beschriebene Fehler konnte bei unserer Prüfung nicht festgestellt beziehungsweise reproduziert werden. Der Artikel funktionierte bei den durchgeführten Prüfungen entsprechend den vorgesehenen Eigenschaften.'
    ],

    6 => [
        'title' => 'Keine Abweichung von Bestellung oder Produktbeschreibung festgestellt',
        'message' => 'Der zurückgesendete Artikel entspricht der bestellten Ausführung und den angegebenen Produkteigenschaften. Die beanstandete Abweichung konnte bei unserer Prüfung nicht festgestellt werden.'
    ],

    7 => [
        'title' => 'Beschädigung nach der Lieferung entstanden',
        'message' => 'Das Prüfergebnis weist darauf hin, dass die festgestellte Beschädigung erst nach der Lieferung durch äußere Einwirkung, unsachgemäße Verwendung oder nicht sachgerechte Handhabung entstanden ist. Feststellung: [konkrete Feststellung]'
    ],

    8 => [
        'title' => 'Normaler Verschleiß oder Verbrauch',
        'message' => 'Der festgestellte Zustand ist auf eine gewöhnliche Abnutzung beziehungsweise den bestimmungsgemäßen Verbrauch des Artikels zurückzuführen. Ein Material- oder Herstellungsfehler konnte nicht festgestellt werden.'
    ],

    9 => [
        'title' => 'Artikel nachträglich verändert oder unsachgemäß bearbeitet',
        'message' => 'Der Artikel wurde nach der Lieferung verändert, geöffnet, bearbeitet oder repariert. Das Prüfergebnis weist darauf hin, dass der beanstandete Zustand auf diesen Eingriff zurückzuführen ist. Feststellung: [konkrete Feststellung]'
    ],

    10 => [
        'title' => 'Wesentliche Bestandteile oder Zubehör fehlen',
        'message' => 'Die Rücksendung ist nicht vollständig. Folgende zum ursprünglichen Lieferumfang gehörende Bestandteile fehlen: [fehlende Bestandteile]. Die Voraussetzungen für eine vollständige freiwillige Rücknahme sind daher nicht erfüllt.'
    ],

    11 => [
        'title' => 'Artikelzustand erfüllt die Bedingungen der freiwilligen Rücknahme nicht',
        'message' => 'Der Artikel weist erhebliche Gebrauchsspuren oder Beschädigungen auf, die nicht den Bedingungen unserer freiwilligen Rücknahme entsprechen. Feststellung: [konkrete Feststellung]'
    ],

    12 => [
        'title' => 'Verbrauchsartikel geöffnet oder bereits verwendet',
        'message' => 'Der zurückgesendete Verbrauchsartikel wurde bereits geöffnet beziehungsweise verwendet und erfüllt deshalb nicht die Bedingungen unserer freiwilligen Rücknahme.'
    ],

    13 => [
        'title' => 'Versiegelung eines Hygiene- oder Gesundheitsartikels entfernt',
        'message' => 'Die Versiegelung des Artikels wurde nach der Lieferung entfernt. Da der versiegelte Artikel aus Gründen des Gesundheitsschutzes oder der Hygiene anschließend nicht mehr zur Rückgabe geeignet ist, kann die Rücknahme nicht akzeptiert werden.'
    ],

    14 => [
        'title' => 'Versiegelte Software oder Datenträger geöffnet',
        'message' => 'Die Versiegelung der gelieferten Software beziehungsweise des Datenträgers wurde nach der Lieferung entfernt. Die Voraussetzungen für eine Rücknahme sind daher nicht erfüllt.'
    ],

    15 => [
        'title' => 'Sonstiger Ablehnungsgrund',
        'message' => '[Individuelle, konkrete und sachliche Begründung]'
    ],
];

if (isset($_REQUEST['or_status']) || isset($_REQUEST['btnAdd'])) {
    //print_r($_REQUEST);die();
    for($i = 0; $i < count($_REQUEST['or_id']); $i++){
        if($_REQUEST['or_status'][$i] > 0){

            $location = array();
            $dinfo_full_name = $dinfo_phone = $dinfo_email = $dinfo_street = $dinfo_house_no = "";
            $Query = "SELECT * FROM `delivery_info` WHERE ord_id = '".$_REQUEST['ord_id'][$i]."'";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if(mysqli_num_rows($rs)){
                $row = mysqli_fetch_object($rs);
                $dinfo_full_name = $row->dinfo_fname." ".$row->dinfo_lname;
                $dinfo_phone = $row->dinfo_phone;
                $dinfo_email = $row->dinfo_email;
                $dinfo_street = $row->dinfo_street;
                $dinfo_house_no = $row->dinfo_house_no;
                $dinfo_usa_zipcode = $row->dinfo_usa_zipcode;
                $location = getPostalCodeAndCity($dinfo_usa_zipcode);
                /*echo $location['postalCode'];
                echo "<pre>";
                print_r($location);
                echo "</pre>";die();*/
            }
            $or_lable_file = "";
            $or_shipmentNo = "";
            if($_REQUEST['or_status'][$i] == 1 && $_REQUEST['orid_courier_type'] == 2){
                $data = [
                        "receiverId" => config_site_dhl_receiverid,

                        "customerReference" => $_REQUEST['ord_id'][$i],
                        "creationSoftware" => "Wacker24",

                        "shipper" => [
                            "name1" => $dinfo_full_name,
                            "addressStreet" => $dinfo_street,
                            "addressHouse" => $dinfo_house_no,
                            "postalCode" => $location['postalCode'],
                            "city" => $location['city'],
                            "email" => $dinfo_email,
                            "phone" => $dinfo_phone
                        ],

                        "itemWeight" => [
                            "uom" => "g",
                            "value" => 500
                        ],

                        "services" => [
                            "goGreenPlus" => true
                        ]
                    ];
                    $response = createDHLReturnOrder($data);
                    
                    /*echo "<pre>";
                    print_r($response);
                    echo "</pre>";die();*/
                    /*"user-valid",
                    "SandboxPasswort2023!",
                    "G39JpBHSJtdGv0FoGT4OtKbLArUZohSs"*/
                    if ($response['status'] == 201) {

                        $or_shipmentNo = $response['response']['shipmentNo'];
                        $or_lable_file = $response['response']['shipmentNo'];
                        $labelBase64 = $response['response']['label']['b64'];
                        $qrLabelBase64 = $response['response']['qrLabel']['b64'];
                        //echo '<img src="data:image/png;base64,' . $qrLabelBase64 . '" />';die();

                        $pdf = base64_decode($labelBase64);

                        file_put_contents("../files/return_labels/{$or_lable_file}.pdf", $pdf);
                        $or_lable_file = $or_lable_file.".pdf";

                        //echo "Return created successfully.<br>";
                        //echo "Shipment No: {$shipmentNo}<br>";
                        //echo "PDF saved: ../files/return_labels/{$shipmentNo}.pdf";
                    }
            }
            $udata = "";
            if(!empty($or_shipmentNo)){
                $udata .= ", or_shipmentNo ='".$or_shipmentNo."'";
            }
            if(isset($_REQUEST['or_note']) && !empty($_REQUEST['or_note'])){
                $udata .= ", orid_remarks = '".dbStr($_REQUEST['or_note'])."'";
            }


            mysqli_query($GLOBALS['conn'], "UPDATE order_return SET  or_lable_file = '".$or_lable_file."', or_status = '".$_REQUEST['or_status'][$i]."', or_note = '".dbStr($_REQUEST['or_note'])."' WHERE or_id = '".$_REQUEST['or_id'][$i]."'") or die(mysqli_error($GLOBALS['conn']));
            mysqli_query($GLOBALS['conn'], "UPDATE order_return_item_detail SET orid_status = '".$_REQUEST['or_status'][$i]."', orid_lable = '".$or_lable_file."', orid_qr_lable = '".dbStr(trim($qrLabelBase64))."', orid_reason_for_rejection = '".$_REQUEST['orid_reason_for_rejection']."' ".$udata." WHERE or_id = '".$_REQUEST['or_id'][$i]."'") or die(mysqli_error($GLOBALS['conn']));
            if($_REQUEST['or_status'][$i] == 1){
                $mailer->order_item_return_approvrd($_REQUEST['orid_id'][$i]);
            } elseif($_REQUEST['or_status'][$i] == 2){
                $mailer->order_item_return_canceled($_REQUEST['orid_id'][$i], $reason_for_rejection[$_REQUEST['orid_reason_for_rejection']]['title'], $reason_for_rejection[$_REQUEST['orid_reason_for_rejection']]['message']);
            }

                    /*echo "<pre>";
                    print_r($response);
                    echo "</pre>";die();*/
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
}

if((isset($_REQUEST['orid_return_item_received_status']) && $_REQUEST['orid_return_item_received_status'] > 1) || isset($_REQUEST['btnRejectionAdd'])){
    //print_r($_REQUEST);die();

    $udata = "";
    if(isset($_REQUEST['orid_reason_for_return_rejection']) && $_REQUEST['orid_reason_for_return_rejection'] > 0){
        $udata .= ", orid_reason_for_return_rejection = '".dbStr($_REQUEST['orid_reason_for_return_rejection'])."'";
    }
    mysqli_query($GLOBALS['conn'], "UPDATE order_return_item_detail SET orid_return_item_received_status = '".$_REQUEST['orid_return_item_received_status']."' ".$udata." WHERE orid_id = '".$_REQUEST['orid_id']."'") or die(mysqli_error($GLOBALS['conn']));
    if($_REQUEST['orid_return_item_received_status'] == 2){
        $mailer->order_item_return_completed($_REQUEST['orid_id']);
    } elseif($_REQUEST['orid_return_item_received_status'] == 3){
        $mailer->order_item_return_rejected_after_inspection($_REQUEST['orid_id'], $reason_for_return_rejection[$_REQUEST['orid_reason_for_return_rejection']]['title'], $reason_for_return_rejection[$_REQUEST['orid_reason_for_return_rejection']]['message']);
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
}

//$trackDHLShipment = trackDHLShipment('Qpvp0kET5wzxxvPWfqGuAAwGiCw34Jtb', 'fDi9sG4FB3HWAO9l', '610377600213');
/*echo '<pre>';
print_r($trackDHLShipment);
echo '</pre>';die();*/

/*$shipment = $trackDHLShipment['data']->data;
$shipmentAttr = $shipment->attributes();

$shipment['piece-code'];
echo $shipment['status'];
$shipment['short-status'];
$shipment['status-timestamp'];*/

/*$trackDHLShipment['tracking_number'];
$trackDHLShipment['status'];
$trackDHLShipment['short_status'];
$trackDHLShipment['status_timestamp'];
die();*/

/*$data = [
    'product' => 'V01PAK',

    'billingNumber' => '50181319730101',

    'reference' => 'ORDER-10001',

    'weight' => 1500,

    'weight_uom' => 'g',

    'sender' => [
        'name1'      => 'WACKER Bürocenter GmbH',
        'street'     => 'Chemnitzer Str.',
        'house'      => '1',
        'postalCode' => '67433',
        'city'       => 'Neustadt',
        'country'    => 'DEU'
    ],

    'receiver' => [
        'name1'      => 'Sayed Kamal',
        'street'     => 'Blockfield straße',
        'house'      => '26-30',
        'postalCode' => '67112',
        'city'       => 'Mutterstadt',
        'country'    => 'DEU'
    ]
];


$response = createDHLShipment('R4fHXjwRbavAA3h2robyBm8LKRXh7U78mA8K3dAFinCXAJXY', $data);
print('<pre>');
print_r($response);
print('</pre>');die();*/

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
                <?php if (isset($_REQUEST['show'])) { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Bestellrücksendung</h1>
                    </div>
                    <div class="main_table_container">
                        <form class="table_responsive" name="frmDetails" id="frmDetails" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <?php
                                $Query = "SELECT odr.*, orid.orid_id, orid.oi_id, orid.orid_send_item_tracking_id, orid.orid_return_item_received, orid.orid_reason_for_rejection, orid.orid_courier_type, orid.orid_status, orid.orid_return_item_received_status, orid.orid_reason_for_return_rejection, ord.user_id, ord.ord_payment_method, di.dinfo_additional_info, CONCAT(di.dinfo_fname, ' ', di.dinfo_lname) AS deliver_full_name, di.dinfo_phone, di.dinfo_email, di.dinfo_street, di.dinfo_address, di.dinfo_house_no, di.dinfo_usa_zipcode, di.dinfo_countries_id, u.utype_id, (SELECT ut.utype_name FROM user_type AS ut WHERE ut.utype_id = u.utype_id) utype_name, usp.usa_additional_info,  CONCAT(usp.usa_street, ' ', usp.usa_house_no) AS shipping_street_house, usp.usa_zipcode, usp.countries_id, usp.usa_address AS shipping_countrie_id FROM order_return AS odr LEFT OUTER JOIN order_return_item_detail AS orid ON orid.or_id = odr.or_id LEFT OUTER JOIN orders AS ord ON ord.ord_id = odr.ord_id LEFT OUTER JOIN users AS u ON u.user_id = ord.user_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = odr.ord_id LEFT OUTER JOIN user_shipping_address AS usp ON usp.user_id = ord.user_id AND usp.usa_type = '1' WHERE odr.or_id = '".$_REQUEST['or_id']."'";
                                    //print($Query);
                                    $or_note_display = 'style="display: none;"';
                                    $return_rejection_display = 'style="display: none;"';
                                    $or_note = "";
                                    $counter = 0;
                                    $limit = 25;
                                    $start = $p->findStart($limit);
                                    $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                    $pages = $p->findPages($count, $limit);
                                    $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                    if (mysqli_num_rows($rs) > 0) {
                                        $row = mysqli_fetch_object($rs);
                                ?>
                                <thead>
                                    <tr>
                                        <th width = "100">Bestellnummer</th>
                                        <th>Benutzerdaten</th>
                                        <th width="200">Versand</th>
                                        <th width="200">Lieferung</th>
                                        <th width="100">Betrag</th>
                                        <th width="147">Datum / Uhrzeit</th>
                                        <th width="170">Rücksendestatus</th>
                                        <?php  if ($row->orid_return_item_received == 1) { ?>
                                        <th width="420">Rücksendestatus</th>
                                         <?php  } if ($row->or_status == 1) { ?>
                                        <th width="150">Aktion</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        do {
                                            $counter++;
                                            $strClass = 'label  label-danger';

                                            $user_info = "";
                                            $or_note = $row->or_note;
                                            if($row->or_status == 2){
                                                $or_note_display = "";
                                            }
                                            if($row->orid_return_item_received_status == 3){
                                                $return_rejection_display = "";
                                            }
                                            $user_guest = "";
                                            $utype_id_as_guest = returnName("utype_id_as_guest", "users", "user_id", $row->user_id);
                                            if ($utype_id_as_guest > 0) {
                                                $user_guest = "Guest";
                                            }
                                            if ($row->utype_id == 3) {
                                                $user_info .= '<span class="btn btn-primary btn-style-light w-auto mb-2">' . rtrim($user_guest . " " . $row->utype_name, "Customer") . '</span><br>';
                                            } else {
                                                $user_info .= '<span class="btn btn-success btn-style-light w-auto mb-2">' . rtrim($user_guest . " " . $row->utype_name, "Customer") . '</span><br>';
                                            }
                                            $user_company_name = returnName("user_company_name", "users", "user_id", $row->user_id);
                                            if (!empty($user_company_name)) {
                                                $user_info .= $user_company_name . "<br>";
                                            }
                                            if (!empty($row->deliver_full_name)) {
                                                $user_info .= $row->deliver_full_name . "<br>";
                                            }
                                            if (!empty($row->dinfo_phone)) {
                                                $user_info .= $row->dinfo_phone . "<br>";
                                            }
                                            if (!empty($row->dinfo_email)) {
                                                $user_info .= $row->dinfo_email . "<br>";
                                            }

                                            $delivery_info = "";
                                            if (!empty($row->dinfo_additional_info)) {
                                                $delivery_info .= $row->dinfo_additional_info . "<br>";
                                            } elseif (!empty($user_company_name)) {
                                                $delivery_info .= $user_company_name . "<br>";
                                            }
                                            if (!empty($row->dinfo_street)) {
                                                $delivery_info .= $row->dinfo_street . " " . $row->dinfo_house_no . "<br>";
                                            }
                                            if (!empty($row->dinfo_usa_zipcode)) {
                                                $delivery_info .= $row->dinfo_usa_zipcode . "<br>";
                                            }
                                            if (!empty($row->countries_name)) {
                                                $delivery_info .= $row->countries_name . "<br>";
                                            }
                                            $shipping_info = "";
                                            if ($row->ord_payment_method == 1) {
                                                if (!empty($row->usa_additional_info)) {
                                                    $shipping_info .= $row->usa_additional_info . "<br>";
                                                }
                                                if (!empty($row->shipping_street_house)) {
                                                    $shipping_info .= $row->shipping_street_house . "<br>";
                                                }
                                                if (!empty($row->usa_zipcode)) {
                                                    $shipping_info .= $row->usa_zipcode . "<br>";
                                                }
                                                if (!empty($row->shipping_countrie_id)) {
                                                    $shipping_info .= returnName("countries_name", "countries", "countrie_id", $row->shipping_countrie_id) . "<br>";
                                                }
                                                if (!empty($row->usa_address)) {
                                                    $shipping_info .= $row->usa_address . "<br>";
                                                }
                                            }
                                            $orid_reason_for_rejection = $row->orid_reason_for_rejection;
                                            $orid_reason_for_return_rejection = $row->orid_reason_for_return_rejection;

                                    ?>
                                            <tr>
                                                <td><?php print($row->ord_id); ?></td>
                                                <td><?php print($user_info); ?></td>
                                                <td><?php print($shipping_info); ?></td>
                                                <td><?php print($delivery_info); ?></td>
                                                <td><?php print(price_format($row->or_amount)); ?></td>
                                                <td><?php print($row->or_cdate); ?></td>
                                                <td>
                                                    <?php if($row->or_status == 0){ ?>
                                                    <div class="table-box-body">
                                                        <div class="table-form-group">
                                                            <input type="hidden" name="orid_id[]" value="<?php print($row->orid_id); ?>">
                                                            <input type="hidden" name="ord_id[]" value="<?php print($row->ord_id); ?>">
                                                            <input type="hidden" name="or_id[]" value="<?php print($row->or_id); ?>">
                                                            <select name="or_status[]" class="input_style or_status" id="or_status">
                                                               <option value="0" <?php echo ($row->or_status == 0) ? 'selected' : '' ?> >Pending</option>
                                                               <option value="1" <?php echo ($row->or_status == 1) ? 'selected' : '' ?> >Approved</option>
                                                               <option value="2" <?php echo ($row->or_status == 2) ? 'selected' : '' ?> >canceled</option>
                                                            </select>
                                                            <span class="material-icons dropdown-icon">keyboard_arrow_down</span>
                                                        </div>
                                                    </div>
                                                    <?php } elseif ($row->or_status == 1) { ?>
                                                        <span class="btn btn-success btn-style-light w-auto"> Approved </span>
                                                    <?php } elseif ($row->or_status == 2) { ?>
                                                        <span class="btn btn-danger btn-style-light w-auto"> canceled </span>
                                                    <?php } ?>
                                                </td>
                                                <?php if($row->orid_return_item_received == 1 && $row->orid_return_item_received_status == 1){ ?>
                                                <td>
                                                    <div class="table-box-body">
                                                        <div class="table-form-group">
                                                            <input type="hidden" name="orid_id" value="<?php print($row->orid_id); ?>">
                                                            <input type="hidden" name="ord_id" value="<?php print($row->ord_id); ?>">
                                                            <input type="hidden" name="or_id" value="<?php print($row->or_id); ?>">
                                                            <select name="orid_return_item_received_status" class="input_style orid_return_item_received_status" id="orid_return_item_received_status">
                                                               <option value="1" <?php echo ($row->orid_return_item_received_status == 1) ? 'selected' : '' ?> >Return / Under Review</option>
                                                               <option value="2" <?php echo ($row->orid_return_item_received_status == 2) ? 'selected' : '' ?> >Completed</option>
                                                               <option value="3" <?php echo ($row->orid_return_item_received_status == 3) ? 'selected' : '' ?> >Rejected After Inspection</option>
                                                            </select>
                                                            <span class="material-icons dropdown-icon">keyboard_arrow_down</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                 <?php } elseif ($row->orid_return_item_received_status == 2) { ?>
                                                    <td>
                                                        <span class="btn btn-success btn-style-light w-auto"> Completed </span>
                                                    </td>
                                                <?php } elseif ($row->orid_return_item_received_status == 3) { ?>
                                                    <td>
                                                        <span class="btn btn-danger btn-style-light w-auto"> Rejected After Inspection </span>
                                                    </td>
                                                <?php } ?>
                                                <td>
                                                    <?php  if ($row->orid_courier_type == 1 && $row->orid_status == 1) { ?>
                                                    <button type="button" class="btn btn-xs btn-danger btn-style-light w-auto" target="_blank" title="View" onClick="javascript: window.open ('<?php print($GLOBALS['siteURL']."backend/manage_own_delivery_lable.php?or_id=" . $row->or_id."&oi_id=".$row->oi_id."&orid_id=".$row->orid_id); ?>');"><span class="material-icons icon material-xs">picture_as_pdf</span></button>
                                                    <?php } elseif (!empty($row->or_lable_file) && $row->or_status == 1) { ?>
                                                    <button type="button" class="btn btn-xs btn-danger btn-style-light w-auto" target="_blank" title="View" onClick="javascript: window.open ('<?php print($GLOBALS['siteURL']."files/return_labels/".$row->or_lable_file); ?>');"><span class="material-icons icon material-xs">picture_as_pdf</span></button>
                                                    <?php } if ($row->or_status == 1) { ?>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto return-item-btn" title="Return Item" data-orid-id="<?php print($row->orid_id); ?>"  data-or-id="<?php print($row->or_id); ?>" data-orid-send-item-tracking-id = "<?php print($row->orid_send_item_tracking_id); ?>" data-orid-return-item-received = "<?php print($row->orid_return_item_received); ?>" ><span class="material-icons icon material-xs">spatial_tracking</span></button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                    <?php
                                        } while ($row = mysqli_fetch_object($rs));
                                    } else {
                                        print('<tr><td colspan="100%" class="text-center">No record found!</td></tr>');
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="row" id="or_note_input" <?php echo $or_note_display ?> >
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Ablehnungsgrund</label>
                                    <div class="table-box-body">
                                        <div class="table-form-group">
                                            <select name="orid_reason_for_rejection" class="input_style orid_reason_for_rejection" id="orid_reason_for_rejection">
                                                <?php
													foreach ($reason_for_rejection as $key => $reason) {
														echo '<option value="' . $key . '" '.(($orid_reason_for_rejection == $key) ? 'selected' : '').'>' . htmlspecialchars($reason['title']) . '</option>';
													}
													?>
                                            </select>
                                            <span class="material-icons dropdown-icon">keyboard_arrow_down</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12 mt-3">
                                    <label for="">Bestellrückgabe – Anmerkungen zur Stornierung</label>
                                    <textarea rows="3" type="text" class="input_style" name="or_note" id="or_note"><?php echo $or_note ?></textarea>
                                </div>
                                <?php if($orid_reason_for_rejection == 0 || empty($orid_reason_for_rejection)) { ?>
                                <div class="col-md-12 col-12 mt-3 mb-3">
                                    <button class="btn btn-primary" type="submit" name="btnAdd">Aktualisierung</button>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="row" id="return_rejection_display" <?php echo $return_rejection_display ?> >
                                <div class="col-md-6 col-12 mt-3">
                                    <label for="">Ablehnungsgrund</label>
                                    <div class="table-box-body">
                                        <div class="table-form-group">
                                            <select name="orid_reason_for_return_rejection" class="input_style orid_reason_for_return_rejection" id="orid_reason_for_return_rejection">
                                                <option value="0">N/A</option>
                                                <?php
													foreach ($reason_for_return_rejection as $key => $reason) {
														echo '<option value="' . $key . '" '.(($orid_reason_for_return_rejection == $key) ? 'selected' : '').'>' . htmlspecialchars($reason['title']) . '</option>';
													}
													?>
                                            </select>
                                            <span class="material-icons dropdown-icon">keyboard_arrow_down</span>
                                        </div>
                                    </div>
                                </div>
                                <?php if($orid_reason_for_return_rejection == 0 || empty($orid_reason_for_return_rejection)) { ?>
                                <div class="col-md-12 col-12 mt-3 mb-3">
                                    <button class="btn btn-primary" type="submit" name="btnRejectionAdd">Aktualisierung</button>
                                </div>
                                <?php } ?>
                            </div>
                        </form>
                        <div class="table-controls mt-3">
                        <h1 class="text-white">Details zur Rücksendung</h1>
                    </div>
                    <div class="main_table_container">
                        <form class="table_responsive" name="frm_table_detail" id="frm_table_detail" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="100">Bild</th>
                                        <th>Lieferanten-ID </th>
                                        <th>Titel </th>
                                        <th width="250">Rücksendestatus</th>
                                        <th width="250">Versandarten</th>
                                        <th>Betrag</th>
                                        <th>Anzahl</th>
                                        <th>Bruttobetrag</th>
                                        <th>VAT</th>
                                        <th>Gesamtbetrag</th>
                                        <!--<th width="50">Action</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ord_gross_total = 0;
                                    $ord_gst = 0;
                                    $ord_discount = 0;
                                    $ord_shipping_charges = 0;
                                    $ord_amount = 0;
                                    $Query = "SELECT orid.*, pro.pro_custom_add, pro.pro_description_short, pg.pg_mime_source_url, odr.or_gross_total, odr.or_gst, odr.or_discount, odr.or_amount FROM order_return_item_detail AS orid LEFT OUTER JOIN order_return AS odr ON odr.or_id = orid.or_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = orid.supplier_id LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE orid.or_id =  '" . $_REQUEST['or_id'] . "' ORDER BY orid.orid_id ASC";
                                    //print($Query);
                                    $rs = mysqli_query($GLOBALS['conn'], $Query);
                                    if (mysqli_num_rows($rs) > 0) {
                                        while ($row = mysqli_fetch_object($rs)) {
                                            $strClass = 'label  label-danger';
                                            $ord_gross_total = price_format($row->or_gross_total);
                                            $ord_gst = price_format($row->or_gst);
                                            $ord_discount = price_format($row->or_discount);
                                            $ord_amount = price_format($row->or_amount);

                                            $pg_mime_source_url = $row->pg_mime_source_url;
                                            $pro_title = $row->pro_description_short;
                                            if(!empty($row->orid_files)){
                                                $pro_title .= '<br> <a href = "manage_return_item_files.php?orid_id='.$row->orid_id.'" target = "_blank" class = "d-flex align-items-center gap-1 mt-1 text-decoration-none cursor-pointer"><span class="material-icons icon">info</span> Attatchment Files</a>';
                                            }
                                            if ($row->pro_custom_add > 0) {
                                                $pg_mime_source_url = $GLOBALS['siteURL'] . $row->pg_mime_source_url;
                                            }

                                            $return_reasons = [
                                                1  => 'Irrtümlich bestellt',
                                                2  => 'Artikel wird nicht mehr benötigt',
                                                3  => 'Artikel ist für den vorgesehenen Einsatz ungeeignet',
                                                4  => 'Artikel entspricht nicht den Erwartungen',
                                                5  => 'Artikel ist fehlerhaft oder funktioniert nicht',
                                                6  => 'Artikel und Versandverpackung sind beschädigt',
                                                7  => 'Artikel ist beschädigt, die Versandverpackung ist jedoch unbeschädigt',
                                                8  => 'Falschen Artikel erhalten',
                                                9  => 'Teile oder Zubehör fehlen',
                                                10 => 'Gelieferte Menge weicht von der bestellten Menge ab',
                                                11 => 'Artikel entspricht nicht der Beschreibung auf der Website',
                                                12 => 'Artikel wurde zu spät geliefert',
                                                13 => 'Bestellung wurde nicht von mir oder uns aufgegeben'
                                            ];

                                            

                                    ?>
                                            <tr>
                                                <td>
                                                    <div class="popup_container">
                                                        <div class="container__img-holder">
                                                            <img src="<?php print(get_image_link(427, $pg_mime_source_url)); ?>">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php print($row->supplier_id); ?></td>
                                                <td><?php print($pro_title); ?></td>
                                                <td><?php print($return_reasons[$row->orid_type]); ?></td>
                                                <td><?php print(($row->orid_courier_type == 2) ? 'DHL' : 'ecodirect'); ?></td>
                                                <td>
                                                    <?php
                                                    if ($row->orid_discount_value > 0) {
                                                        print("<del class = 'text-danger fs-6'>" . price_format($row->pbp_price_amount * (1 + config_gst)) . "€</del><br> <span class = 'text-success'>" . str_replace(".", ",", $row->orid_amount) . "€ " . $row->orid_discount_value . (($row->orid_discount_type > 0) ? '€' : '%') . "</span>");
                                                    } else {
                                                        print(price_format($row->orid_amount) . "€");
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php print($row->orid_qty); ?></td>
                                                <td><?php print(price_format($row->orid_gross_total)); ?>€</td>
                                                <td><?php print(price_format($row->orid_gst)); ?>€</td>
                                                <td><?php print(price_format($row->orid_net_total)); ?>€</td>
                                                <!--<td>
                                                    <button type="button" class="btn btn-xs btn-success btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show&" . $qryStrURL . "ord_id=" . $row->ord_id); ?>';"><span class="material-icons icon material-xs">visibility</span></button>
                                                </td>-->
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <th colspan="9" class="text-end text-white fs-6">Nettobetrag:</th>
                                            <td class="text-white fs-6"><?php print($ord_gross_total); ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="9" class="text-end text-white fs-6">Mwstbetrag:</th>
                                            <td class="text-white fs-6"><?php print($ord_gst); ?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="9" class="text-end text-white fs-6">Rechnungsbetrag:</th>
                                            <td class="text-white fs-6"><?php print($ord_amount); ?></td>
                                        </tr>
                                    <?php } else {
                                        print('<tr><td colspan="100%" class="text-center">No record found!</td></tr>');
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </form>

                    </div>

                    </div>
                <?php } else { ?>
                    <div class="table-controls">
                        <h1 class="text-white">Verwaltung von Rücksendungen</h1>
                    </div>
                    <div class="main_table_container">
                        <?php
                        $orid_id = "";
                        $ord_id = "";
                        $orid_courier_type = 0;
                        $orid_status = 3;
                        $searchQuery = "WHERE 1 = 1";

                        if (isset($_REQUEST['orid_id']) && $_REQUEST['orid_id'] > 0) {
                            $orid_id = $_REQUEST['orid_id'];
                            $searchQuery .= " AND odr.orid_id = '" . $orid_id . "'";
                        }
                        if (isset($_REQUEST['ord_id']) && $_REQUEST['ord_id'] > 0) {
                            $ord_id = $_REQUEST['ord_id'];
                            $searchQuery .= " AND odr.ord_id = '" . $ord_id . "'";
                        }
                        if (isset($_REQUEST['orid_courier_type']) && $_REQUEST['orid_courier_type'] > 0) {
                            $orid_courier_type = $_REQUEST['orid_courier_type'];
                            $searchQuery .= " AND odr.orid_courier_type = '".$orid_courier_type."'";
                        }
                        if (isset($_REQUEST['orid_return_item_received_status_filter']) && $_REQUEST['orid_return_item_received_status_filter'] < 3) {
                            $orid_return_item_received_status_filter = $_REQUEST['orid_return_item_received_status_filter'];
                            $searchQuery .= " AND odr.orid_return_item_received_status = '".$orid_return_item_received_status_filter."'";
                        }
                        ?>
                        <form class="row flex-row" name="frm_search" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                            <div class=" col-md-2 col-12 mt-2">
                                <label for="" class="text-white">Rücksendungs-ID</label>
                                <input type="text" class="input_style orid_id" name="orid_id" id="orid_id" value="<?php print($orid_id); ?>" placeholder="Return ID" autocomplete="off" onchange="javascript: frm_search.submit();">
                            </div>
                            <div class=" col-md-2 col-12 mt-2">
                                <label for="" class="text-white">Auftragsnummer</label>
                                <input type="text" class="input_style ord_id" name="ord_id" id="ord_id" value="<?php print($ord_id); ?>" placeholder="Order ID" autocomplete="off" onchange="javascript: frm_search.submit();">
                            </div>
                            <div class=" col-md-3 col-12 mt-3">
                                <label for="" class="text-white">Versandarten</label>
                                <select name="orid_courier_type" id="orid_courier_type" class="input_style" onchange="javascript: frm_search.submit();">
                                    <option value="0" <?php print(($orid_courier_type == 0) ? 'selected' : ''); ?>>N/A</option>
                                    <option value="1" <?php print(($orid_courier_type == 1) ? 'selected' : ''); ?>>ecodirect</option>
                                    <option value="2" <?php print(($orid_courier_type == 2) ? 'selected' : ''); ?>>DHL</option>
                                </select>
                            </div>
                            <div class=" col-md-2 col-12 mt-3">
                                <label for="" class="text-white">Status</label>
                                <select name="orid_return_item_received_status_filter" id="orid_return_item_received_status_filter" class="input_style" onchange="javascript: frm_search.submit();">
                                    <option value="3" <?php print(($orid_return_item_received_status_filter == 3) ? 'selected' : ''); ?>>N/A</option>
                                    <option value="1" <?php print(($orid_return_item_received_status_filter == 1) ? 'selected' : ''); ?>>Open</option>
                                    <option value="2" <?php print(($orid_return_item_received_status_filter == 2) ? 'selected' : ''); ?>>Close</option>
                                </select>
                            </div>
                        </form>
                        <form class="table_responsive" name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" role="form" enctype="multipart/form-data">
                            <table>
                                <thead>
                                    <tr>
                                        <th width = "100">Rücksendungs-ID</th>
                                        <th width = "100">Auftragsnummer</th>
                                        <th>Benutzerinformationen</th>
                                        <th width="200">Versand</th>
                                        <th width="200">Lieferung</th>
                                        <th width="250">Versandarten</th>
                                        <th width="100">Betrag</th>
                                        <th width="147">Datum / Uhrzeit</th>
                                        <th width = "170">Rücksendestatus</th>
                                        <th width="110">Aktion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //$Query = "SELECT odr.*, ord.user_id, ord.ord_payment_method, di.dinfo_additional_info, CONCAT(di.dinfo_fname, ' ', di.dinfo_lname) AS deliver_full_name, di.dinfo_phone, di.dinfo_email, di.dinfo_street, di.dinfo_address, di.dinfo_house_no, di.dinfo_usa_zipcode, di.dinfo_countries_id, u.utype_id, (SELECT ut.utype_name FROM user_type AS ut WHERE ut.utype_id = u.utype_id) utype_name, usp.usa_additional_info,  CONCAT(usp.usa_street, ' ', usp.usa_house_no) AS shipping_street_house, usp.usa_zipcode, usp.countries_id, usp.usa_address AS shipping_countrie_id FROM order_return AS odr LEFT OUTER JOIN orders AS ord ON ord.ord_id = odr.ord_id LEFT OUTER JOIN users AS u ON u.user_id = ord.user_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = odr.ord_id LEFT OUTER JOIN user_shipping_address AS usp ON usp.user_id = ord.user_id AND usp.usa_type = '1' ORDER BY odr.or_id DESC";
                                    $Query = "SELECT odr.*, ord.user_id, ord.ord_payment_method, di.dinfo_additional_info, CONCAT(di.dinfo_fname, ' ', di.dinfo_lname) AS deliver_full_name, di.dinfo_phone, di.dinfo_email, di.dinfo_street, di.dinfo_address, di.dinfo_house_no, di.dinfo_usa_zipcode, di.dinfo_countries_id, u.utype_id, (SELECT ut.utype_name FROM user_type AS ut WHERE ut.utype_id = u.utype_id) utype_name, usp.usa_additional_info,  CONCAT(usp.usa_street, ' ', usp.usa_house_no) AS shipping_street_house, usp.usa_zipcode, usp.countries_id, usp.usa_address AS shipping_countrie_id FROM order_return_item_detail AS odr LEFT OUTER JOIN orders AS ord ON ord.ord_id = odr.ord_id LEFT OUTER JOIN users AS u ON u.user_id = ord.user_id LEFT OUTER JOIN delivery_info AS di ON di.ord_id = odr.ord_id LEFT OUTER JOIN user_shipping_address AS usp ON usp.user_id = ord.user_id AND usp.usa_type = '1' ".$searchQuery." ORDER BY odr.or_id DESC";
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

                                            $user_info = "";
                                            $user_guest = "";
                                            $utype_id_as_guest = returnName("utype_id_as_guest", "users", "user_id", $row->user_id);
                                            if ($utype_id_as_guest > 0) {
                                                $user_guest = "Guest";
                                            }
                                            if ($row->utype_id == 3) {
                                                $user_info .= '<span class="btn btn-primary btn-style-light w-auto mb-2">' . rtrim($user_guest . " " . $row->utype_name, "Customer") . '</span><br>';
                                            } else {
                                                $user_info .= '<span class="btn btn-success btn-style-light w-auto mb-2">' . rtrim($user_guest . " " . $row->utype_name, "Customer") . '</span><br>';
                                            }
                                            $user_company_name = returnName("user_company_name", "users", "user_id", $row->user_id);
                                            if (!empty($user_company_name)) {
                                                $user_info .= $user_company_name . "<br>";
                                            }
                                            if (!empty($row->deliver_full_name)) {
                                                $user_info .= $row->deliver_full_name . "<br>";
                                            }
                                            if (!empty($row->dinfo_phone)) {
                                                $user_info .= $row->dinfo_phone . "<br>";
                                            }
                                            if (!empty($row->dinfo_email)) {
                                                $user_info .= $row->dinfo_email . "<br>";
                                            }

                                            $delivery_info = "";
                                            if (!empty($row->dinfo_additional_info)) {
                                                $delivery_info .= $row->dinfo_additional_info . "<br>";
                                            } elseif (!empty($user_company_name)) {
                                                $delivery_info .= $user_company_name . "<br>";
                                            }
                                            if (!empty($row->dinfo_street)) {
                                                $delivery_info .= $row->dinfo_street . " " . $row->dinfo_house_no . "<br>";
                                            }
                                            if (!empty($row->dinfo_usa_zipcode)) {
                                                $delivery_info .= $row->dinfo_usa_zipcode . "<br>";
                                            }
                                            if (!empty($row->countries_name)) {
                                                $delivery_info .= $row->countries_name . "<br>";
                                            }
                                            $shipping_info = "";
                                            if ($row->ord_payment_method == 1) {
                                                if (!empty($row->usa_additional_info)) {
                                                    $shipping_info .= $row->usa_additional_info . "<br>";
                                                }
                                                if (!empty($row->shipping_street_house)) {
                                                    $shipping_info .= $row->shipping_street_house . "<br>";
                                                }
                                                if (!empty($row->usa_zipcode)) {
                                                    $shipping_info .= $row->usa_zipcode . "<br>";
                                                }
                                                if (!empty($row->shipping_countrie_id)) {
                                                    $shipping_info .= returnName("countries_name", "countries", "countrie_id", $row->shipping_countrie_id) . "<br>";
                                                }
                                                if (!empty($row->usa_address)) {
                                                    $shipping_info .= $row->usa_address . "<br>";
                                                }
                                            }
                                    ?>
                                            <tr>
                                                <td><?php print($row->orid_id); ?></td>
                                                <td><?php print($row->ord_id); ?></td>
                                                <td><?php print($user_info); ?></td>
                                                <td><?php print($shipping_info); ?></td>
                                                <td><?php print($delivery_info); ?></td>
                                                <td><?php print(($row->orid_courier_type == 2) ? 'DHL' : 'ecodirect'); ?></td>
                                                <td><?php print(price_format($row->orid_net_total)); ?></td>
                                                <td><?php print($row->orid_cdate); ?></td>
                                                <td>
                                                    <?php if($row->orid_return_item_received_status <= 1){ ?>
                                                    <span class="btn btn-warning btn-style-light w-auto"> Open </span>
                                                    <?php } elseif ($row->orid_return_item_received_status > 1) { ?>
                                                        <span class="btn btn-success btn-style-light w-auto"> Close </span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php  if ($row->orid_courier_type == 1 && $row->orid_status == 1) { ?>
                                                    <button type="button" class="btn btn-xs btn-danger btn-style-light w-auto" target="_blank" title="View" onClick="javascript: window.open ('<?php print($GLOBALS['siteURL']."backend/manage_own_delivery_lable.php?or_id=" . $row->or_id."&oi_id=".$row->oi_id."&orid_id=".$row->orid_id); ?>');"><span class="material-icons icon material-xs">picture_as_pdf</span></button>
                                                    <?php } elseif (!empty($row->orid_lable) && $row->orid_status == 1) { ?>
                                                    <button type="button" class="btn btn-xs btn-danger btn-style-light w-auto" target="_blank" title="View" onClick="javascript: window.open ('<?php print($GLOBALS['siteURL']."files/return_labels/".$row->orid_lable); ?>');"><span class="material-icons icon material-xs">picture_as_pdf</span></button>
                                                    <?php }  ?>
                                                    <button type="button" class="btn btn-xs btn-primary btn-style-light w-auto" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show&" . $qryStrURL . "orid_courier_type=".$row->orid_courier_type."&or_id=" . $row->or_id); ?>';"><span class="material-icons icon material-xs">visibility</span></button>
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
                                    <input type="submit" name="btnActive" value="Active" class="btn btn-primary btn-style-light w-auto">
                                </div>
                                <div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnInactive" value="In Active" class="btn btn-warning btn-style-light w-auto">
                                </div>
                                <!--<div class=" col-md-1 col-12 mt-2">
                                    <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-style-light w-100" onclick="return confirm('Are you sure you want to delete selected item(s)?');">
                                </div>-->
                            </div>
                        </form>

                    </div>
                <?php } ?>
            </section>
        </div>
    </div>

    <div class="modal fade" id="returnItemModal" tabindex="-1" aria-labelledby="returnItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="returnItemModalLabel">
                        Artikel zurücksenden
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="returnItemForm" data-parsley-validate>

                    <div class="modal-body">

                        <input type="hidden" name="orid_id" id="orid_id" value="">

                        <!-- Tracking ID 
                        <div class="mb-3">
                            <label for="orid_send_item_tracking_id" class="form-label">
                                Tracking ID <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="orid_send_item_tracking_id"
                                id="orid_send_item_tracking_id"
                                placeholder="Enter tracking ID"
                                data-parsley-required="false"
                                data-parsley-required-message="Please enter the tracking ID."
                            >
                        </div>-->

                        <!-- Received -->
                        <div class="mb-3">
                            <label class="form-label d-block">
                                Ist die Rücksendung bei uns eingegangen?
                            </label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="orid_return_item_received" id="return_received_yes" value="1" data-parsley-required="true">
                                <label class="form-check-label" for="return_received_yes">&nbsp;&nbsp; Ja</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="orid_return_item_received" id="return_received_no" value="0" >
                                <label class="form-check-label" for="return_received_no">&nbsp;&nbsp; Nein </label>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Abbrechen
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Speichern
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <?php include("includes/bottom_js.php"); ?>
</body>

<!-- Parsley.js -->
<script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.2/dist/parsley.min.js"></script>
<script>
    $('.or_status').on('change', function(){
        let or_status = $("#or_status").val();
        //console.log("or_status", or_status);
        if(or_status == 2){
            $("#orid_reason_for_rejection").prop("required", true);
            $("#or_note_input").show();
        } else {
            $("#orid_reason_for_rejection").prop("required", false);
            $("#or_note").val('');
            $("#or_note_input").hide();
            frmDetails.submit();
        }
    });
    
    $('.orid_return_item_received_status').on('change', function(){
        let orid_return_item_received_status = $("#orid_return_item_received_status").val();
        //console.log("or_status", or_status);
        if(orid_return_item_received_status == 3){
            $("#orid_reason_for_return_rejection").prop("required", true);
            $("#return_rejection_display").show();
        } else {
            $("#orid_reason_for_return_rejection").prop("required", false);
            $("#return_rejection_display").hide();
            frmDetails.submit();
        }
    });

    $('input.orid_id').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=order_return_id',
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
            var orid_id = $("#orid_id");
            $(orid_id).val(ui.item.value);
            frm_search.submit();
            //return false;
        }
    });
    $('input.ord_id').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=return_order_id',
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
            var ord_id = $("#ord_id");
            $(ord_id).val(ui.item.value);
            frm_search.submit();
            //return false;
        }
    });

    $(document).on('click', '.return-item-btn', function () {
        $('#returnItemForm')[0].reset();
        let orid_id = $(this).attr('data-orid-id');
        //let orid_send_item_tracking_id = $(this).attr('data-orid-send-item-tracking-id');
        let orid_return_item_received = $(this).attr('data-orid-return-item-received');
        
        $('#orid_id').val(orid_id);
       // $('#orid_send_item_tracking_id').val(orid_send_item_tracking_id);

        $('input[name="orid_return_item_received"]').prop('checked', false);

        if (orid_return_item_received !== undefined && orid_return_item_received !== '') {
            $('input[name="orid_return_item_received"][value="' + orid_return_item_received + '"]')
                .prop('checked', true);
        }


        $('#returnItemModal').modal('show');
    });
    

    $('#returnItemForm').parsley();

    $('#returnItemForm').on('submit', function (e) {

        e.preventDefault();

        let received = $('input[name="orid_return_item_received"]:checked').val();
        //let trackingId = $.trim($('#orid_send_item_tracking_id').val());

        if (received === '0') {

            $('#return_received_no')
                .parsley()
                .addError('returnError', {
                    message: 'Sie können das Formular nicht absenden, da die Rücksendung noch nicht eingegangen ist.'
                });

            return false;
        } else if (typeof received === 'undefined') {

            $('#return_received_yes')
                .parsley()
                .addError('returnError', {
                    message: 'Bitte wählen Sie „Ja“ aus.'
                });

            return false;
        } /*else if (received === '1' && trackingId === '') {

            $('#orid_send_item_tracking_id')
                .attr('data-parsley-required', 'true')
                .parsley()
                .validate();

            return false;
        }*/

        $.ajax({
            url: 'ajax_calls.php?action=return_received',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',

            beforeSend: function () {
                $('#returnItemForm button[type="submit"]')
                    .prop('disabled', true)
                    .text('Speichern...');
            },

            success: function (response) {
                //console.log(response);
                if (response.status == 1) {

                $('#returnItemModal').modal('hide');

                const url = new URL(window.location.href);
                url.searchParams.delete('op');
                url.searchParams.set('op', '2');
                window.location.href = url.toString();

                } else {

                    alert(response.message);
                }
            },

            error: function () {
                alert('Something went wrong. Please try again.');
            },

            complete: function () {
                $('#returnItemForm button[type="submit"]')
                    .prop('disabled', false)
                    .text('Save');
            }
        });

    });
</script>
</html>