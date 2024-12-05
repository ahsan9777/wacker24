<?php
$xml = simplexml_load_file("lagersortiment_standard.xml") or die("Error: Cannot create object");
if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'catalog':
            print('<pre>');
            print_r($xml->T_NEW_CATALOG->CATALOG_GROUP_SYSTEM);
            print('</pre>');
            break;
        case 'catalog_map':
            print('<pre>');
            print_r($xml->T_NEW_CATALOG->ARTICLE_TO_CATALOGGROUP_MAP);
            print('</pre>');
            break;
        case 'artical':
            print('<pre>');
            print_r($xml->T_NEW_CATALOG->ARTICLE);
            // $i = 0;
            //foreach ($xml->T_NEW_CATALOG->ARTICLE as $rl) {
              //  echo $i++." ".$rl->SUPPLIER_AID.PHP_EOL;
            //}
            print('</pre>');
            break;
    }
}
//$xml = simplexml_load_string($x); // assume XML in $x


