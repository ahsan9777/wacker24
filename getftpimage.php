<?php

$img = $_GET['img'];
header('Content-Type: image/jpeg');
echo  file_get_contents('ftp://lager:bA$1IDC1@ftpshop.soennecken.de/Mediendaten/Bilddaten_Lager_2000_Pixel/'.$img);


?>