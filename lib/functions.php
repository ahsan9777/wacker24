<?php
function user_logs($user_id, $llog_id, $ulog_ip, $ulog_page, $ulog_action, $ulog_parameters)
{
	mysqli_query($GLOBALS['conn'], "INSERT INTO user_logs (`user_id`, `llog_id`, `ulog_ip`, `ulog_page`, `ulog_action`, `ulog_parameters`) VALUES ('" . dbStr($user_id) . "','" . dbStr($llog_id) . "','" . dbStr($ulog_ip) . "','" . dbStr($ulog_page) . "','" . dbStr($ulog_action) . "','" . dbStr($ulog_parameters) . "')") or die(mysqli_error($GLOBALS['conn']));
}
function safe($value)
{
	return mysql_real_escape_string($value);
}

function rmPageFromURL($url)
{
	/* $parsed = parse_url($url);
	$query = $parsed['query']; */
	$query = $url;
	parse_str($query, $params);
	unset($params['page']);
	$str = http_build_query($params);
	return $str . "&";
}

function get_contents($url)
{
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data = curl_exec($curl);
	print($data);
	curl_close($curl);
	return $data;
}

function retArray($qry)
{
	$age_ids = array();
	$rsAge = mysqli_query($GLOBALS['conn'], $qry);
	if (mysqli_num_rows($rsAge) > 0) {
		while ($row = mysqli_fetch_row($rsAge)) {
			$age_ids[] = $row[0];
		}
	} else {
		$age_ids[] = 0;
	}
	return $age_ids;
}

function genAppKey($mem_id, $app_id, $app_name)
{
	$str_gen = $mem_id . "_" . $app_id . "_" . $app_name;
	$appKey = base64_encode($str_gen);
	return $appKey;
}

function chkAppKey($apk_key)
{
	$app_id = 0;
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM mem_appkey WHERE apk_key='" . $apk_key . "'");
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		$app_id = $row->apk_id;
	}
	return $app_id;
}


/*function chkAppKey($apk_key, $app_id){
	$isValid = 0;
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM mem_appkey WHERE apk_id='".$app_id."' AND apk_key='".$apk_key."'");
	if(mysqli_num_rows($rs)){
		$isValid = 0;
	}
	return $isValid;
}*/

function ret_com_set_op($val, $id, $action = 0)
{
	$strQry = '';
	if ($action > 0) {
		$strQry = '&action=' . $action;
	}
	$ret = '';
	switch ($val) {
		case 1:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">YES &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnUnsets=1&id=' . $id . $strQry . '">NO</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		default:
			//$ret = '<span class="badge badge-orange">Stand By</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">NO &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnSets=1&id=' . $id . $strQry . '">YES</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
	}
	return $ret;
}
function ret_count_set_op($val, $id, $action, $qryStrURL)
{
	$strQry = '';
	if ($action > 0) {
		$strQry = '&action=' . $action;
	}
	$ret = '';
	switch ($val) {
		case 1:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">YES &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btncountUnsets=1&id=' . $id . $strQry . '&' . $qryStrURL . '">NO</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		default:
			//$ret = '<span class="badge badge-orange">Stand By</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">NO &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btncountSets=1&id=' . $id . $strQry . '&' . $qryStrURL . '">YES</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
	}
	return $ret;
}
function peroject_status($val, $proj_id, $action = 0)
{
	$strQry = '';
	if ($action > 0) {
		$strQry = '&action=' . $action;
	}
	$ret = '';
	switch ($val) {
		case 1:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">Current &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnPast=1&proj_id=' . $proj_id . $strQry . '">Past</a></li>';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnFuture=1&proj_id=' . $proj_id . $strQry . '">Future</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		case 2:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-warning dropdown-toggle" data-toggle="dropdown">Future &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnPast=1&proj_id=' . $proj_id . $strQry . '">Past</a></li>';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnCurrent=1&proj_id=' . $proj_id . $strQry . '">Current</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		default:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Past &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnCurrent=1&proj_id=' . $proj_id . $strQry . '">Current</a></li>';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnFuture=1&proj_id=' . $proj_id . $strQry . '">Future</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
	}
	return $ret;
}
function set_permissions($val, $id, $action = 0)
{
	$strQry = '';
	if ($action > 0) {
		$strQry = '&action=' . $action;
	}
	$ret = '';
	switch ($val) {
		case 1:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret .= '<a class = "btn btn-primary btn-style-light" href="' . $_SERVER['PHP_SELF'] . '?btnUnsets=1&id=' . $id . $strQry . '">Yes</a>';
			/*$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">YES &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="'.$_SERVER['PHP_SELF'].'?btnUnsets=1&type_id='.$id.$strQry.'">NO</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';*/
			break;
		default:
			//$ret = '<span class="badge badge-orange">Stand By</span>';
			$ret .= '<a class = "btn btn-danger btn-style-light" href="' . $_SERVER['PHP_SELF'] . '?btnSets=1&id=' . $id . $strQry . '">No</a>';
			/*$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">NO &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="'.$_SERVER['PHP_SELF'].'?btnSets=1&type_id='.$id.$strQry.'">YES</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';*/
			break;
	}
	return $ret;
}

function ret_com_show_op($val, $id, $action = 0)
{
	$strQry = '';
	if ($action > 0) {
		$strQry = '&action=' . $action;
	}
	$ret = '';
	switch ($val) {
		case 1:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-inverse dropdown-toggle" data-toggle="dropdown">SHOW &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnCNotShow=1&id=' . $id . $strQry . '">NOT SHOW</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		default:
			//$ret = '<span class="badge badge-orange">Stand By</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-light-grey dropdown-toggle" data-toggle="dropdown">NOT SHOW &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnCShow=1&id=' . $id . $strQry . '">SHOW</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
	}
	return $ret;
}

function getLnt($zip)
{
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=
	" . urlencode($zip) . "&sensor=false";
	$result_string = file_get_contents($url);
	$result = json_decode($result_string, true);
	$result1[] = $result['results'][0];
	$result2[] = $result1[0]['geometry'];
	$result3[] = $result2[0]['location'];
	return $result3[0];
}

function dbStr($str)
{
	$string = str_replace("'", "''", $str); // Converts ' to ' in database, but ' to '' in the static page
	//return stripslashes($string); // Removes any forward slashes from string
	//$string = $str;
	return $string;
}
function url_clean($string)
{
	/*$string = str_replace(" ", "-", strtolower(trim($string)));
	$string = str_replace(array(',','’'), "", $string);*/
	$string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
	$string = str_replace(" ", "-", strtolower(trim($string)));
	$string = str_replace(array(",", "’", "'", "&", ".", "%", ":", '"', "<", ">", "(", ")", "!", "*", "@", "~", "+", "×", "³", "²", "¹", "®", "™", "©", "|", "`"), "", $string);
	$string = str_replace(array("/", "+", "--"), "-", $string);
	return $string;
}
function cleanArray($array)
{
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			cleanArray($value);
		} else {
			$array[$key] = mysql_real_escape_string(urldecode($value));
		}
	}
	return $array;
}
function base_url($path = NULL)
{
	if ($path == NULL) {
		$dirArray = pathinfo($_SERVER['SCRIPT_NAME']);
	} else {
		$dirArray = pathinfo($path);
	}
	$_SERVER['HTTP_HOST'] = @str_replace("/", "", @$_SERVER['HTTP_HOST']);
	$serverAddress = "http://" . $_SERVER['HTTP_HOST'] . "/";
	$dirArray['dirname'] = @trim(@$dirArray['dirname']);
	if (!empty($dirArray['dirname']) && $dirArray['dirname'] != "/") {
		$ptn = "/^\//";  // Regex
		$rpltxt = "";  // Replacement string
		$dirArray['dirname'] = preg_replace($ptn, $rpltxt, $dirArray['dirname']);
		$serverAddress .= $dirArray['dirname'] . "/";
	}
	return $serverAddress;
}
function FillSelectedMul($Table, $IDField, $TextField, $active)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	$cou = 0;
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if (isset($active[$cou][$IDField]) && $row[0] == $active[$cou][$IDField]) {
				if ($row[0] == $active[$cou][$IDField]) {
					print("<option value=\"$row[0]\" selected>$row[1]</option>");
					$cou++;
				}
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function bread_crum2($page, $cat_id)
{
	$bCrum = "";
	if (isset($_GET['property_id'])) {
		$Query = "SELECT pd.property_name As childs, pc.pcat_value AS parent FROM property_details AS pd LEFT OUTER JOIN property_cat AS pc ON pd.property_id=pc.pcat_id WHERE pd.property_id=" . $_GET['property_id'] . " LIMIT 1";
		$brdCrm1 = mysqli_query($GLOBALS['conn'], $Query);
		if (mysqli_num_rows($brdCrm1) > 0) {
			while ($row = mysqli_fetch_object($brdCrm1)) {
				$bCrum .= "
					<a href='$page.php?cat_id=$cat_id'>" . returnName("pcat_value", "property_cat", "pcat_id", $cat_id) . "</a> / 
					" . ucwords($row->childs) . "
				";
			}
		}
	} else {
		$bCrum .= returnName("pcat_value", "property_cat", "pcat_id", $cat_id);
	}
	return $bCrum;
}

function testimonials($section_id)
{
	$result = "";
	$Query = "SELECT t.* FROM testimonials AS t WHERE t.section_id='" . $section_id . "' AND t.status_id=1 ORDER BY RAND() LIMIT 1";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) >= 1) {
		while ($row = mysqli_fetch_object($rs)) {
			$result .= "
			<div class='featured'>
				<div class='title'>
					<h4>TESTIMONIALS</h4>
				</div>
				<blockquote>
					<p> 
						$row->tm_details
						<span>$row->tm_signature</span>
					</p>
				</blockquote>
			</div>";
		}
	}
	return $result;
}

function latest_news($section_id)
{
	$result = "";
	$Query = "SELECT n.* FROM news AS n WHERE n.section_id='" . $section_id . "' AND n.status_id=1 ORDER BY n.news_date DESC LIMIT 3";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) >= 1) {
		$result .= '
		<div class="featured">
			<div class="title">
				<h4>LATEST NEWS</h4>
			</div>
			<div class="recent_posts classic">';
		while ($row = mysqli_fetch_object($rs)) {
			$date = "";
			if ($row->news_date != "") {
				$arrdate = explode("-", $row->news_date);
				$arrdate2 = explode(" ", $arrdate[2]);
				$date = date("M j", mktime(0, 0, 0, $arrdate[1], $arrdate2[0], $arrdate[0]));
			}
			if ($row->news_date == "0000-00-00") {
				$date = "";
			}
			$arrdate3 = explode(" ", $date);
			$result .= "
				<ul>
					<li class='date'><span class='day'>$arrdate3[1]</span>$arrdate3[0]</li>
					<li>
						<span class='title'>
							<a href='#'>";
			$result .= limit_text($row->news_title, 50);
			$result .= "
							</a>
						</span>";
			$result .= limit_text($row->news_title, 75);
			$result .= "
					</li>
				</ul>";
		}
		$result .= '	
			</div>
		</div>';
	}
	return $result;
}

function left_navigation($section_id, $page_name)
{
	$result = "";
	$strQuery = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid = 0 AND status_id=1 AND section_id='" . $section_id . "' ORDER BY cat_id";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQry = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid = $row[0] ORDER BY cat_id";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				$result .= '<ul class="sub-menu">';
				while ($row1 = mysqli_fetch_row($nRs)) {
					$result .= "<li><a href='" . $page_name . "?cid=$row1[0]'>$row1[1]</a>";
					$strQry3 = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid = $row1[0] ORDER BY cat_id";
					$nRs3 = mysqli_query($GLOBALS['conn'], $strQry3);
					if (mysqli_num_rows($nRs3) >= 1) {
						$result .= "<ul class='sub-menu'>";
						while ($row3 = mysqli_fetch_row($nRs3)) {
							$result .= "<li class='current_page_item first'><a href='" . $page_name . "?cid=$row1[0]&sid=$row3[0]'>$row3[1]</a></li>";
						}
						$result .= "</ul>";
					}
					$result .= '</li>';
				}
				$result .= '</ul>';
			}
		}
	}
	return $result;
}

function right_navigation($section_id, $page_name)
{
	$result = "";
	$result .= '
	<div class="box three last">
		<div class="accordion">					
	';
	$strQuery = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid=0 AND status_id=1 AND section_id='" . $section_id . "' ORDER BY cat_id";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQry = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid = $row[0] ORDER BY cat_id";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				while ($row1 = mysqli_fetch_row($nRs)) {
					$result .= "<div class='title'><span>$row1[1]</span></div>";
					$strQry3 = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid = $row1[0] ORDER BY cat_id";
					$nRs3 = mysqli_query($GLOBALS['conn'], $strQry3);
					if (mysqli_num_rows($nRs3) >= 1) {
						$result .= "
					<div class='pane'>
						<ul class='check'>";
						while ($row3 = mysqli_fetch_row($nRs3)) {
							$result .= "<li><a href='$page_name?cid=$row1[0]&sid=$row3[0]'>$row3[1]</a></li>";
						}
						$result .= "
						</ul>
						<div class='clear'></div>
					</div>
					";
					}
				}
			}
		}
	}
	$result .= '
		</div>
	</div>                    
	';
	return $result;
}

function left_featured($section_id, $page_name)
{
	$result = "";
	$result .= '
  <h4>FEATURED</h4>
  <div class="ppy" id="ppy3">
	<ul class="ppy-imglist">';
	$Query = "SELECT DISTINCT l.list_id, list_name, i.img_title, i.img_file FROM listings AS l LEFT OUTER JOIN listing_images AS i ON l.list_id=i.list_id  WHERE l.is_featured=1 AND l.status_id=1 AND l.section_id='" . $section_id . "' AND i.img_default=1 ORDER BY RAND()";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	while ($row = mysqli_fetch_object($rs)) {
		$result .= "<li>
						<a href='images/listings/$row->img_file'>
							<img src='images/listings/th/$row->img_file'  alt='$row->img_title'/>
						</a>
						<div class='ppy-extcaption'>
							<h5>
								<a href='$page_name$row->list_id' title='$row->list_name'>$row->list_name</a>
							</h5>
						</div>
					</li>";
	}
	$result .= '</ul>
	<div class="ppy-outer">
	  <div class="ppy-stage">
		<div class="ppy-nav ">
		  <div class="nav-wrap"> <a class="ppy-prev" title="Previous image">Previous Post</a> <a class="ppy-switch-enlarge" title="Enlarge">Enlarge</a> <a class="ppy-switch-compact" title="Close">Close</a> <a class="ppy-next" title="Next image">Next Post</a> </div>
		</div>
		<div class="ppy-counter"> <strong class="ppy-current"></strong> / <strong class="ppy-total"></strong> </div>
	  </div>
	  <div class="ppy-caption"><span class="ppy-text"></span></div>
	</div>
  </div>
  <div class="clear"></div>';
	return $result;
}

function banner_news()
{
	$result = "";
	$rs_c = mysqli_query($GLOBALS['conn'], "SELECT COUNT(*) as cou_b from banners");
	$row_c = mysqli_fetch_object($rs_c);
	$Query = "SELECT pd.*, lc.location_value, pt.ptype_value, pc.pcat_value, ps.pstatus_value, ps.pstatus_value_fr, st.status_name, bd.bedrom_value, ad.ad_value, au.au_value FROM property_details AS pd LEFT OUTER JOIN locations AS lc ON pd.location_id=lc.location_id LEFT OUTER JOIN property_type AS pt ON pd.ptype_id=pt.ptype_id LEFT OUTER JOIN property_cat AS pc ON pd.pcat_id=pc.pcat_id LEFT OUTER JOIN property_status AS ps ON pd.pstatus_id=ps.pstatus_id LEFT OUTER JOIN status AS st ON pd.status_id=st.status_id LEFT OUTER JOIN bedrooms AS bd ON pd.bedrom_id=bd.bedrom_id LEFT OUTER JOIN area_digit AS ad ON pd.ad_id=ad.ad_id LEFT OUTER JOIN area_unit AS au ON pd.au_id=au.au_id LIMIT " . $row_c->cou_b;
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	while ($row = mysqli_fetch_object($rs)) {
		if (@$_SESSION['french'] == 1) {
			$pro_d = $row->property_short_detail_fr;
			$pro_n = $row->property_name_fr;
			$pro_s = $row->pstatus_value_fr;
		} else {
			$pro_d = $row->property_short_detail;
			$pro_n = $row->property_name;
			$pro_s = $row->pstatus_value;
		}
		$result .= "
			<li>
				<h3 class='desc_title'><a href='details.php?property_id=$row->property_id&cat_id=$row->pcat_id'>$pro_n</a></h3>
				<p class='desc_sub_title'><a href='details.php?property_id=$row->property_id&cat_id=$row->pcat_id'>$row->date</a></p>
				<p class='desc_status'>$row->ad_value $row->au_value | <strong>" . STATUS . ":</strong> $pro_s</p>
				<p>$pro_d</p>
				<h4 class='desc_price font_harabara'>" . ((@$_SESSION['french'] == 1) ? "$row->property_price_EU EURO" : "$row->property_price USD") . "</h4>
				<p>" . BEDROOM . " : $row->bedrom_value</p>
				<p class='" . ((@$_SESSION['french'] == 1) ? "desc_readmore_fr" : "desc_readmore") . "'><a href='details.php?property_id=$row->property_id&cat_id=$row->pcat_id' class='png_fix'><span class='hide_this'>" . READ_MORE . "</span></a></p>
			</li>";
	}
	return $result;
}

function blog_news()
{
	$result = "";
	$Query = "SELECT pd.property_id, pd.pcat_id, pd.property_name_fr, pd.property_name, ps.pstatus_value, ps.pstatus_value_fr, ad.ad_value, au.au_value, pk.pkind_value, pk.pkind_value_fr FROM property_details AS pd LEFT OUTER JOIN property_kind AS pk ON pd.pkind_id=pk.pkind_id LEFT OUTER JOIN property_status AS ps ON pd.pstatus_id=ps.pstatus_id LEFT OUTER JOIN area_digit AS ad ON pd.ad_id=ad.ad_id LEFT OUTER JOIN area_unit AS au ON pd.au_id=au.au_id ORDER BY pd.property_id DESC LIMIT 4";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	while ($row = mysqli_fetch_object($rs)) {
		if (@$_SESSION['french'] == 1) {
			$pro_n = $row->property_name_fr;
			$pro_k = $row->pkind_value_fr;
			$pro_s = $row->pstatus_value_fr;
		} else {
			$pro_n = $row->property_name;
			$pro_k = $row->pkind_value;
			$pro_s = $row->pstatus_value;
		}
		$result .= "
			<li>
				<span>" . RECENT . ": </span>
				<a href='details.php?property_id=$row->property_id&cat_id=$row->pcat_id'>$pro_n</a>
				<span> <strong>" . KIND . ":</strong> $pro_k</span>
				<span>$row->ad_value $row->au_value | <strong>" . STATUS . ":</strong> $pro_s</span>
			</li>";
	}
	return $result;
}

function top_slider($section_id)
{
	$res = "";
	$res .= "<div id='slider_area' class='cycle'>";
	$Query = "SELECT * FROM banners WHERE status_id=1 AND section_id='" . $section_id . "' ORDER BY ban_id";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	while ($row = mysqli_fetch_object($rs)) {
		$res .= "<div class='slide'>			
					<div class='desc'>
						<span class='title'>
							<a href='#' title='" . $row->ban_name . "'>$row->ban_name</a>
						</span>
					</div>
					<img src='images/banners/$row->ban_file' alt='" . $row->ban_title . "' />
				 </div>";
	}
	$res .= "</div>";
	return $res;
}

function top_slider1()
{
	$res = "";
	$Query = "SELECT * FROM banners ORDER BY ban_id";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	while ($row = mysqli_fetch_object($rs)) {
		$res .= "
					<li><img src='images/banners/lrg/$row->ban_file' alt='$row->ban_title' height='235' width='535' /></li>";
	}
	return $res;
}

function menuTarget($val)
{
	$strReturn = '';
	if ($val == '_self') {
		$strReturn .= '<option value="_self" selected="selected">Self</option>';
	} else {
		$strReturn .= '<option value="_self">Self</option>';
	}
	if ($val == '_blank') {
		$strReturn .= '<option value="_blank" selected="selected">Blank</option>';
	} else {
		$strReturn .= '<option value="_blank">Blank</option>';
	}
	return $strReturn;
}

function showStars1($memID, $proID)
{
	$retValue = "";
	$avgRate = 0;
	$voteVal = 1;
	$qry = mysqli_query($GLOBALS['conn'], "SELECT rev_rate FROM reviews WHERE mem_id=" . $memID . " AND pro_id=" . $proID . " LIMIT 1");
	$qryFetch = mysqli_fetch_object($qry);
	/*if(mysqli_num_rows($qry) > 0){
		$row = mysqli_fetch_object($qry);
		if($row->total_records > 0){
			$avgRate = round($row->total / $row->total_records, 1);
		}
	}*/
	$avgRate = round($qryFetch->rev_rate);
	for ($i = 0; $i < 5; $i++) {
		if ($i < $avgRate) {
			$retValue .= '<img src="images/star.png" alt="' . $avgRate . '" title="' . $avgRate . '" />';
		} else {
			$retValue .= '<img src="images/star_rol.png" alt="' . $avgRate . '" title="' . $avgRate . '" />';
		}
		$voteVal++;
	}
	return $retValue;
}

function showStars($proID)
{
	$retValue = "";
	$avgRate = 0;
	$avg = 0;
	$voteVal = 1;
	$avrgeReviews = 0;
	$rslt = 0;
	$qry = mysqli_query($GLOBALS['conn'], "SELECT * FROM reviews WHERE pro_id='" . $proID . "' AND status_id=1");

	/*if(mysqli_num_rows($qry) > 0){
		$row = mysqli_fetch_object($qry);
		if($row->total_records > 0){
			$avgRate = round($row->total / $row->total_records, 1);
		}
	}*/
	if (mysqli_num_rows($qry) > 0) {
		while ($row = mysqli_fetch_object($qry)) {
			$avg = $avg + $row->rev_rate;
			$avgRate++;
		}
		$avrgeReviews = $avg / $avgRate;
		$rslt = round($avrgeReviews);
	}
	for ($i = 0; $i < 5; $i++) {
		if ($i < $rslt) {
			$retValue .= '<img src="images/star.png" alt="' . $avgRate . '" title="' . $avgRate . '" />';
		} else {
			$retValue .= '<img src="images/star_rol.png" alt="' . $avgRate . '" title="' . $avgRate . '" />';
		}
		$voteVal++;
	}
	return $retValue;
}
function chkImage($imgType)
{
	$isallowed = 0;
	$typesAllowed = array('image/jpeg', 'image/gif', 'image/png');
	if (in_array($imgType, $typesAllowed)) {
		$isallowed = 1;
	}
	return $isallowed;
}
function deleteDir($dirPath)
{
	if (! is_dir($dirPath)) {
		throw new InvalidArgumentException("$dirPath must be a directory");
	}
	if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
		$dirPath .= '/';
	}
	$files = glob($dirPath . '*', GLOB_MARK);
	foreach ($files as $file) {
		if (is_dir($file)) {
			$this->deleteDir($file);
		} else {
			unlink($file);
		}
	}
	rmdir($dirPath);
}
function avgRating($secID, $objID)
{
	$retValue = "";
	$qry = mysqli_query($GLOBALS['conn'], "SELECT COUNT( 1 ) AS total_records, SUM( rate ) AS total FROM votes WHERE section_id=" . $secID . " AND pro_id=" . $objID);
	if (mysqli_num_rows($qry) > 0) {
		$row = mysqli_fetch_object($qry);
		if ($row->total_records == 0) {
			$retValue = '<span id="serverResponse3">Average: 0 of </span><span id="serverResponse4">0 vote(s)</span>';
		} else {
			$avgRate = $row->total / $row->total_records;
			$retValue = '<span id="serverResponse3">Average: ' . $avgRate . ' of </span><span id="serverResponse4">' . $row->total_records . ' vote(s)</span>';
		}
	} else {
		$retValue = '<span id="serverResponse3">Average: 0 of </span><span id="serverResponse4">0 vote(s)</span>';
	}
	return $retValue;
}

function avgRatingDet($secID, $objID)
{
	$retValue = "";
	$qry = mysqli_query($GLOBALS['conn'], "SELECT COUNT( 1 ) AS total_records, SUM( rate ) AS total FROM votes WHERE section_id=" . $secID . " AND pro_id=" . $objID);
	if (mysqli_num_rows($qry) > 0) {
		$row = mysqli_fetch_object($qry);
		if ($row->total_records == 0) {
			$retValue = '<a href="#" class="Votes"><span id="serverResponse1">0 Vote(s) </span></a><span id="serverResponse2">Average: 0</span>';
		} else {
			$avgRate = $row->total / $row->total_records;
			$retValue = '<a href="#" class="Votes"><span id="serverResponse1">' . $row->total_records . ' Vote(s) </span></a><span id="serverResponse2">Average: ' . $avgRate . '</span>';
		}
	} else {
		$retValue = '<span id="serverResponse3">Average: 0 of </span><span id="serverResponse4">0 vote(s)</span>';
	}
	return $retValue;
}

function fillTimeCombo($val)
{
	$strMin = 0;
	$strHr = 0;
	for ($i = 0; $i < 48; $i++) {
		$strTime = date("H:i", mktime($strHr, $strMin, 0, 1, 1, 2012));
		$strTimeComp = $strTime . ":00";
		if ($val == $strTimeComp) {
			print('<option value="' . $strTime . '" selected="selected">' . $strTime . '</option>');
		} else {
			print('<option value="' . $strTime . '">' . $strTime . '</option>');
		}
		if ($strMin == 0) {
			$strMin = 30;
		} else {
			$strMin = 0;
			$strHr++;
		}
	}
}

function rename_image($source)
{
	$ext = pathinfo($source);
	$image = substr(md5(rand(8, 999999999999999)), 0, 12) . "_repair." . $ext['extension'];
	return $image;
}

function returnAuthor($ID)
{
	$retRes = "";
	if ($ID == 0) {
		$retRes = "Site Admin";
	} else {
		$strQry = "SELECT mem_fname, mem_lname FROM members WHERE mem_id=" . $ID;
		$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
		if (mysqli_num_rows($nResult) >= 1) {
			$row = mysqli_fetch_row($nResult);
			$retRes = $row[0] . " " . $row[1];
		}
	}
	return $retRes;
}

function blogTags($ID)
{
	$cnt = 0;
	$strReturn = "";
	$strQuery = "SELECT t.tag_name FROM bl_post_tags AS p, bl_tags AS t WHERE t.tag_id=p.tag_id AND p.post_id=" . $ID;
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if ($cnt > 0) {
				$strReturn .= ", ";
			}
			$strReturn .= $row->tag_name;
			$cnt++;
		}
	}
	return $strReturn;
}

function getMaxTags()
{
	$retResult = 0;
	$rsTag = mysqli_query($GLOBALS['conn'], "SELECT MAX(tag_total) AS MaxTag FROM bl_tags");
	if (mysqli_num_rows($rsTag) >= 1) {
		$rowTag = mysqli_fetch_object($rsTag);
		$retResult = $rowTag->MaxTag;
	}
	return $retResult;
}

function getMinTags()
{
	$retResult = 0;
	$rsTag = mysqli_query($GLOBALS['conn'], "SELECT MIN(tag_total) AS MinTag FROM bl_tags");
	if (mysqli_num_rows($rsTag) >= 1) {
		$rowTag = mysqli_fetch_object($rsTag);
		$retResult = $rowTag->MinTag;
	}
	return $retResult;
}

function limit_text($text, $limit)
{
	// figure out the total length of the string
	if (strlen($text) > $limit) {
		# cut the text
		$text = substr($text, 0, $limit);
		# lose any incomplete word at the end
		$text = substr($text, 0, - (strlen(strrchr($text, ' '))));
		$text .= " . . .";
	}
	// return the processed string
	return $text;
}

function insKeyGeneral($mmod_id)
{
	//$mk_general = chkExist("mk_api", "module_key", "WHERE mk_id=".$mk_id." AND mmod_id=".$mmod_id." AND mk_isext=0");
	$mk_general = chkExist("mk_api", "module_key", "WHERE mmod_id=" . $mmod_id . " AND mk_isext=0");
	if ($mk_general == '0') {
		$mk_id = getMaximum("module_key", "mk_id");
		$modName = returnName("mmod_name", "mem_modules", "mmod_id", $mmod_id);
		$str_gen = $mk_id . "_" . $mmod_id . "_" . $modName;
		$mk_general = base64_encode($str_gen);
		$mk_api = $mk_general;
		//$mk_api = $mk_general ."_". $mk_extension;
		$strQry = "INSERT INTO module_key (mk_id, mmod_id, mk_general, mk_extension, mk_api) VALUES(" . $mk_id . ", " . $mmod_id . ", '" . $mk_general . "', '" . $mk_extension . "', '" . $mk_api . "')";
		$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
	}
	return $mk_general;
}

/*function insKeyExtension($mk_id, $mmod_id, $mk_extension){
	$mk_id = getMaximum("module_key", "mk_id");
	$modName = returnName("mmod_name", "mem_modules", "mmod_id", $mmod_id);
	$str_gen = $mk_id."_".$mmod_id."_".$modName;
	$mk_general = base64_encode($str_gen);
	$mk_api = $mk_general ."_". $mk_extension;
	$strQry = "INSERT INTO module_key (mk_id, mmod_id, mk_general, mk_extension, mk_api) VALUES(".$mk_id.", ".$mmod_id.", '".$mk_general."', '".$mk_extension."', '".$mk_api."')";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
}*/

function returnSubsAmount($dur, $discount, $amount)
{
	$retAmount = round($amount, 2);
	if ($dur == 'y') {
		$savings = ($amount * $discount) / 100;
		$retAmount = round($amount - $savings, 2) * 12;
	}
	return $retAmount;
}

function dateDif($date1, $date2, $op)
{
	$retValue = "";
	$dt1 = explode("-", $date1);
	$dt2 = explode("-", $date2);
	$d1 = mktime(0, 0, 0, $dt1[1], $dt1[2], $dt1[0]);
	$d2 = mktime(0, 0, 0, $dt2[1], $dt2[2], $dt2[0]);
	switch ($op) {
		case 'hr':
			$retValue = floor(($d2 - $d1) / 3600);
			break;
		case 'min':
			$retValue = floor(($d2 - $d1) / 60);
			break;
		case 'sec':
			$retValue = ($d2 - $d1);
			break;
		case 'year':
			$retValue = floor(($d2 - $d1) / 31536000);
			break;
		case 'mon':
			$retValue = floor(($d2 - $d1) / 2628000);
			break;
		case 'day':
			$retValue = floor(($d2 - $d1) / 86400);
			break;
	}
	return $retValue;
}

function getEmbedCode($swfURL, $feedURL)
{
	$strEmbed = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="1000" height="600">
				<param name="movie" value="' . $swfURL . '" />
				<param name="quality" value="high" />
				<param name="menu" value="false" />
				<param name="bgcolor" value="#869ca7" />
				<param name="allowFullScreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="flashvars" value="feedURL=' . $feedURL . '" />
				<embed src="' . $swfURL . '" menu="false" bgcolor="#869ca7" allowscriptaccess="always" allowFullScreen="true" flashvars="feedURL=' . $feedURL . '" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="1000" height="600"></embed>
			</object>';
	return $strEmbed;
}

function getProEmbedCode($swfURL, $feedURL, $proVars)
{
	$strEmbed = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="1000" height="600">
				<param name="movie" value="' . $swfURL . '" />
				<param name="quality" value="high" />
				<param name="menu" value="false" />
				<param name="bgcolor" value="#869ca7" />
				<param name="allowFullScreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="flashvars" value="feedURL=' . $feedURL . $proVars . '" />
				<embed src="' . $swfURL . '" menu="false" bgcolor="#869ca7" allowscriptaccess="always" allowFullScreen="true" flashvars="feedURL=' . $feedURL . $proVars . '" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="1000" height="600"></embed>
			</object>';
	return $strEmbed;
}

function chk_in_string($string, $find)
{
	$pos = strpos($string, $find);
	if ($pos === false) {
		$retVal = 0;
	} else {
		$retVal = 1;
	}
	return $retVal;
}

function fileRead($myFile)
{
	$fh = fopen($myFile, 'r');
	$fileData = fread($fh, filesize($myFile));
	fclose($fh);
	return $fileData;
}

function fileWrite($myFile, $strData)
{
	$fh = fopen($myFile, 'w');
	fwrite($fh, $strData);
	fclose($fh);
}

function returnFeatureVal($pfsID, $pfsName, $fnpQty, $fPrice, $fnpTenure, $fDir, $pakID, $pkfID, $pkfName, $dur, $discount)
{
	//$fVal = "";
	$fVal = '<input type="hidden" name="pfsID_' . $pkfID . '_' . $pakID . '" id="pfsID_' . $pkfID . '_' . $pakID . '" value="' . $pfsID . '" />';
	switch ($pfsID) {
		case 0:
			$fVal .= '<img src="' . $fDir . 'images/cross.png" width="24" height="24" alt="' . $pfsName . '" style="margin-top:8px;">';
			$fVal .= '<input type="hidden" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="0" />';
			break;
		case 1:
			$fVal .= '<img src="' . $fDir . 'images/tick.png" width="24" height="24" alt="' . $pfsName . '" style="margin-top:8px;">';
			$fVal .= '<input type="hidden" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="0" />';
			break;
		case 2:
			$fVal .= '<span style="line-height:38px;">Limited to <b>' . $fnpQty . '</b></span>';
			$fVal .= '<input type="hidden" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="' . $fnpQty . '" />';
			$fVal .= '<input type="hidden" name="proLimit" id="proLimit" value="' . $fnpQty . '" />';
			break;
		case 3:
			$fVal .= '<span style="line-height:38px;"><b>' . $pfsName . '</b></span>';
			$fVal .= '<input type="hidden" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="0" />';
			break;
		case 4:
			if ($fnpQty > 0) {
				$fVal .= '<input type="checkbox" name="chk_' . $pakID . '[]" id="chk_' . $pakID . '[]" value="' . $pkfID . '" /> Add USD ' . returnSubsAmount($dur, $discount, $fPrice) . '/' . $dur;
				$fVal .= '<br />Quantity: <input type="textbox" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="1" style="width:30px; text-align:right;" class="inputsmallBorder" onFocus="this.className=\'inputsmallBorder2\';" onBlur="this.className=\'inputsmallBorder\';" />';
			} else {
				$fVal .= '<span style="line-height:38px;"><input type="checkbox" name="chk_' . $pakID . '[]" id="chk_' . $pakID . '[]" value="' . $pkfID . '" /> Add USD ' . returnSubsAmount($dur, $discount, $fPrice) . '/' . $dur . '</span> <input type="hidden" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="0" />';
			}
			$fVal .= '<input type="hidden" name="price_' . $pkfID . '_' . $pakID . '" id="price_' . $pkfID . '_' . $pakID . '" value="' . returnSubsAmount($dur, $discount, $fPrice) . '" /><input type="hidden" name="pfName_' . $pkfID . '_' . $pakID . '" id="pfName_' . $pkfID . '_' . $pakID . '" value="' . $pkfName . '" />';
			break;
		case 5:
			$fVal = '<span style="line-height:34px;"><b><a href="#" title="Contact Us">Contact Us</a></b></span>';
			break;
	}
	return $fVal;
}

function showStatus($val)
{
	switch ($val) {
		case 0:
			$varStatus = "Pending";
			break;
		case 1:
			$varStatus = "Completed";
			break;
		case 2:
			$varStatus = "Failed";
			break;
		case 3:
			$varStatus = "Denied";
			break;
		case 4:
			$varStatus = "INVALID";
			break;
		case 5:
			$varStatus = "Cancelled";
			break;
		case 6:
			$varStatus = "Rejected";
			break;
	}
	return $varStatus;
}

function copyDir($dir, $dest)
{
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$pos = strpos($file, ".");
				if ($pos > 0) {
					$strSource = $dir . "/" . $file;
					$strDest = $dest . "/" . $file;
					copy($strSource, $strDest);
				}
			}
		}
		closedir($handle);
	}
}

function showCardname($ID)
{
	$retRes = "";
	$strQry = "SELECT mcard_name FROM mem_cards WHERE mcard_id=$ID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
	} else {
		$retRes = "Card Removed";
	}
	return $retRes;
}

function FillSelected($Table, $IDField, $TextField, $ID, $Join = '')
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table $Join ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function FillSelected22($Table, $IDField, $TextField, $ID)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function FillSelectedJoin($Table, $IDField, $TextField, $ID, $Join = '')
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table $Join ORDER BY $IDField";
	//print($strQuery);die();
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	$returnStr = "";
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				$returnStr .= "<option value=" . $row[0] . " selected>" . $row[1] . "</option>";
			} else {
				$returnStr .= "<option value=" . $row[0] . ">" . $row[1] . "</option>";
			}
		}
		return print($returnStr);
	}
}
function FillSelectedJoin2($Table, $IDField, $TextField, $ID, $Join = '')
{
	$strQuery = "SELECT DISTINCT($IDField), $TextField FROM $Table $Join ORDER BY $IDField";
	//print($strQuery);die();
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=" . $row[0] . " selected>" . $row[1] . "</option>");
			} else {
				print("<option value=" . $row[0] . ">" . $row[1] . "</option>");
			}
		}
	}
}

// Display Just Parent Categories
function FillSelected2($Table, $IDField, $TextField, $ID, $WHERE)
{
	$strQuery = "SELECT DISTINCT $IDField, $TextField FROM $Table WHERE $WHERE ORDER BY $IDField ASC";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}
function FillSelected2join($Table, $join, $IDField, $TextField, $ID, $WHERE, $orderby)
{
	$strQuery = "SELECT DISTINCT $IDField, $TextField FROM $Table LEFT OUTER JOIN $join  WHERE $WHERE ORDER BY $orderby ASC";

	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function FillSelected2JS($Table, $IDField, $TextField, $ID, $WHERE)
{
	$strQuery = "SELECT DISTINCT $IDField, $TextField FROM $Table WHERE $WHERE ORDER BY $IDField ASC";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>" . str_replace(",", "\'", $row[1]) . "</option>");
			} else {
				print("<option value=\"$row[0]\">" . str_replace("'", "\'", $row[1]) . "</option>");
			}
		}
	}
}

function FillSelectedValue($Table, $IDField, $TextField, $ID)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[1]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[1]\">$row[1]</option>");
			}
		}
	}
}

function FillMultiple($Table, $IDField, $TextField, $SelTbl, $Field1, $Field2, $SelID)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQuery1 = "SELECT * FROM $SelTbl WHERE $Field1=$row[0] AND $Field2=$SelID";
			$nResult1 = mysqli_query($GLOBALS['conn'], $strQuery1);
			if (mysqli_num_rows($nResult1) >= 1) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function FillMultiple2($Table, $IDField, $TextField, $WHERE, $per_type)
{
	$arr = explode(",", $per_type);
	$Query = "SELECT $IDField,$TextField from $Table WHERE $WHERE";
	$nResult = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($nResult) > 0) {
		while ($row = mysqli_fetch_row($nResult)) {
			if (in_array($row[0], $arr)) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}
function FillSelected_Parent($Table, $IDField, $TextField, $ID, $parentField)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = 0";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQry = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = $row[0]";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				print("<optgroup label=\"$row[1]\">");
				while ($row1 = mysqli_fetch_row($nRs)) {
					if ($row1[0] == $ID) {
						print("<option value=\"$row1[0]\" selected>$row1[1]</option>");
					} else {
						print("<option value=\"$row1[0]\">$row1[1]</option>");
					}
				}
				print("</optgroup>");
			} else {
				if ($row[0] == $ID) {
					print("<option value=\"$row[0]\" selected>$row[1]</option>");
				} else {
					print("<option value=\"$row[0]\">$row[1]</option>");
				}
			}
		}
	}
}
function FillSelected_ParentLang($Table, $IDField, $TextField, $ID, $parentField, $langID)
{
	$strQuery = "SELECT a." . $IDField . ", l." . $TextField . ", a." . $parentField . " FROM " . $Table . " AS a LEFT OUTER JOIN " . $Table . "_ln AS l ON l." . $IDField . "=a." . $IDField . " AND lang_id=" . $langID . " WHERE a." . $parentField . " = 0";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			//$strQry="SELECT $IDField, $TextField, $parentField FROM $Table WHERE $parentField = $row[0]";
			$strQry = "SELECT a." . $IDField . ", l." . $TextField . ", a." . $parentField . " FROM " . $Table . " AS a LEFT OUTER JOIN " . $Table . "_ln AS l ON l." . $IDField . "=a." . $IDField . " AND lang_id=" . $langID . " WHERE a.$parentField = $row[0]";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				print("<optgroup label=\"$row[1]\">");
				while ($row1 = mysqli_fetch_row($nRs)) {
					if ($row1[0] == $ID) {
						print("<option value=\"$row1[0]\" selected>$row1[1]</option>");
					} else {
						print("<option value=\"$row1[0]\">$row1[1]</option>");
					}
				}
				print("</optgroup>");
			} else {
				if ($row[0] == $ID) {
					print("<option value=\"$row[0]\" selected>$row[1]</option>");
				} else {
					print("<option value=\"$row[0]\">$row[1]</option>");
				}
			}
		}
	}
}
function FillSelected_AgentLead($Table, $IDField, $TextField, $ID)
{
	$strQuery = "SELECT a." . $IDField . ", l." . $TextField . " FROM " . $Table . " AS a LEFT OUTER JOIN leads AS l ON l." . $IDField . "=a." . $IDField . " where a.user_id=" . $_SESSION['UserID'];

	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {

			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function TotalRecords($fiels, $Table, $condition)
{

	$strQuery = "SELECT COUNT($fiels) AS count FROM $Table $condition";
	//print($strQuery);
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	$row = mysqli_fetch_object($nResult);
	return $row->count;
}
function TotalRecords2($Table, $condition, $ID)
{
	$strQuery = "SELECT * FROM $Table $condition Where " . $ID . "";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	return mysqli_num_rows($nResult);
}
function TotalRecords3($Table, $condition, $ID, $ID2)
{
	$strQuery = "SELECT * FROM $Table $condition Where " . $ID . " And " . $ID2 . "";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	return mysqli_num_rows($nResult);
}

function TotalRecords1($condition)
{
	$strQuery = $condition;
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	return mysqli_num_rows($nResult);
}

function checkrecord($filde, $table, $where)
{
	$retRes = 0;
	$strQry = "SELECT " . $filde . " FROM " . $table . " WHERE " . $where . " ";
	//print($strQry);//die();
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) > 0) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
	}
	return $retRes;
}
function checkAdminOldPass($UserID, $Pass)
{
	$retRes = 0;
	$strQry = "SELECT admin_user, admin_pass FROM admin WHERE admin_id=$UserID AND admin_pass='$Pass'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$retRes = 1;
	}
	return $retRes;
}

function checkAdminLogin($Login, $Pass)
{
	$retRes = 0;
	$strQry = "SELECT user_id FROM user WHERE user_name='$Login' AND user_password='$Pass'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$retRes = 1;
	}
	return $retRes;
}

function checkSAdminLogin($Login, $Pass)
{
	$retRes = 0;
	$strQry = "SELECT sadmin_user FROM sec_admin WHERE sadmin_user='$Login' AND sadmin_pass='$Pass'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->sadmin_user)
				$retRes = 1;
		}
	}
	return $retRes;
}

function checkLogin($Login, $Pass)
{
	$retRes = 0;
	$strQry = "SELECT mem_login FROM members WHERE mem_login='$Login' AND mem_password='$Pass'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->mem_login)
				$retRes = 1;
		}
	}
	return $retRes;
}

function checkLogin2($Login, $Pass)
{
	$retRes = 0;
	$strQry = "SELECT mem_login FROM members WHERE mem_login='$Login' AND mem_password='$Pass' AND mem_deleted = 1";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->mem_login)
				$retRes = 1;
		}
	}
	return $retRes;
}

function checkSubscription($mID)
{
	$retRes = 0;
	$strQry = "SELECT sinfo_enddate, paystatus_id FROM subscription_info WHERE mem_id=$mID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_object($nResult);
		if ($row->paystatus_id > 1) {
			$retRes = 2;
		} elseif ($row->paystatus_id < 1) {
			$retRes = 3;
		} elseif ($row->sinfo_enddate < date("Y-m-d")) {
			$retRes = 1;
		} else {
			$retRes = 4;
		}
	}
	return $retRes;
}

function UpdateSignIn($MemberID, $MemberEmail)
{
	$MaxID = getMaximum("signin_counter", "signin_id");

	$strQry1 = "UPDATE members SET mem_last_login = NOW() WHERE mem_id=$MemberID";
	$nResult1 = mysqli_query($GLOBALS['conn'], $strQry1);

	$strQry2 = "INSERT INTO signin_counter(signin_id, mem_id, mem_email, signin_date) VALUES ($MaxID, $MemberID, '$MemberEmail', NOW())";
	$nResult2 = mysqli_query($GLOBALS['conn'], $strQry2);
}

function updateViews($cardID, $numViews)
{
	$totalViews = $numViews + 1;
	mysqli_query($GLOBALS['conn'], "UPDATE cards SET card_views=" . $totalViews . " WHERE card_id = " . $cardID) or die("Unable 2 Update Views");
}

function getRating($PhotoID)
{
	$Rating = 0;
	$strQry = "SELECT photo_totalrating, photo_rating FROM photos WHERE photo_id = $PhotoID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if ($row->photo_totalrating > 0 && $row->photo_rating > 0)
				$Rating = $row->photo_totalrating / $row->photo_rating;
			else
				$Rating = 0;
		}
	}
	return $Rating;
}
function getMinimum($Table, $Field)
{
	$maxID = 0;
	$strQry = "SELECT MIN(" . $Field . ")+1 as CID FROM " . $Table . " ";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->CID)
				$maxID = $row->CID;
			else
				$maxID = 1;
		}
	}
	return $maxID;
}
function getMaximumWhere($Table, $Field, $Where)
{
	$maxID = 0;
	$strQry = "SELECT MAX(" . $Field . ")+1 as CID FROM " . $Table . " " . $Where;
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->CID)
				$maxID = $row->CID;
			else
				$maxID = 1;
		}
	}
	return $maxID;
}
function getMaximum($Table, $Field)
{
	$maxID = 0;
	$strQry = "SELECT MAX(" . $Field . ")+1 as CID FROM " . $Table . " ";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->CID)
				$maxID = $row->CID;
			else
				$maxID = 1;
		}
	}
	return $maxID;
}

function getMaximumCatID($Table, $Field)
{
	$maxID = 0;
	$strQry = "SELECT MAX(" . $Field . ")+1 as CID FROM " . $Table . " ";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->CID)
				$maxID = $row->CID;
			else
				$maxID = 2;
		}
	}
	return $maxID;
}

function IsExist($Field, $Table, $TblField, $Value)
{
	$retRes = 0;
	$strQry = "SELECT $Field FROM $Table WHERE $TblField='$Value'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
	if (mysqli_num_rows($nResult) >= 1) {
		$retRes = 1;
	}
	return $retRes;
}
function IsExistConfrm($Field, $Table, $TblField, $Value)
{
	$retRes = 0;
	$strQry = "SELECT $Field FROM $Table WHERE $TblField='$Value' AND confirm=0";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
	if (mysqli_num_rows($nResult) >= 1) {
		$retRes = 1;
	}
	return $retRes;
}

function chkExist($Field, $Table, $WHERE)
{
	$retRes = 0;
	$strQry = "SELECT $Field FROM $Table $WHERE";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
		//$retRes=1;
	}
	return $retRes;
}

function returnMulCat($ID)
{
	$retRes = "";
	$numCnt = 0;
	$strQry = "SELECT c.cat_name FROM categories AS c, card_categories AS cc WHERE c.cat_id = cc.cat_id AND cc.card_id = $ID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($numCnt == 0) {
				$retRes .= $row[0];
			} else {
				$retRes .= ", " . $row[0];
			}
			$numCnt++;
		}
	}
	return $retRes;
}

function returnMultiName($Field, $Table, $IDField, $ID, $fieldcount, $AND = "")
{
	$retRes = array();
	if (strlen($ID) < 4) {
		//$strQry = "SELECT $Field FROM $Table WHERE FIND_IN_SET ('" . $ID . "', ".$IDField.") " . $AND . "";
		$strQry = "SELECT $Field FROM $Table WHERE " . $IDField . " = '" . $ID . "' " . $AND . "";
	} else {
		$strQry = "SELECT $Field FROM $Table WHERE $IDField= '" . $ID . "' " . $AND . "";
	}
	//print($strQry);die();
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		for ($i = 0; $i < $fieldcount; $i++) {
			$retRes['data_' . ($i + 1)] = $row[$i];
		}
	}
	return $retRes;
}
function returnName($Field, $Table, $IDField, $ID, $AND = "")
{
	$retRes = "";
	$strQry = "SELECT $Field FROM $Table WHERE $IDField= '" . $ID . "' " . $AND . " LIMIT 1";
	//print($strQry);die();
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
	}
	return $retRes;
}
function returnSum($Field, $Table, $IDField, $ID, $AND = "")
{
	$retRes = 0;
	$strQry = "SELECT SUM($Field) FROM $Table WHERE $IDField= '" . $ID . "' " . $AND . " LIMIT 1";
	//print($strQry);die();
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
	}
	return !empty($retRes) ? $retRes : 0;
}

function returnNameArray($Field, $Table, $IDField, $ID, $AND = "")
{
	$retRes = array();
	$strQry = "SELECT $Field FROM $Table WHERE $IDField= '" . $ID . "' " . $AND . " ";
	//print($strQry);die();
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$retRes[] = $row[0];
		}
	}
	return $retRes;
}

function datalenghtcheck($data, $level)
{
	$filtered = 0;
	foreach ($data as $item) {
		if (strlen((string)$item) == 3 && $level == 2) {
			$filtered = $item;
		} elseif (strlen((string)$item) == 5 && $level == 3) {
			$filtered = $item;
		}
	}

	return $filtered;
}

function returnImage($Field, $Table, $IDField, $ID)
{
	$retRes = "";
	$strQry = "SELECT $Field FROM $Table WHERE $IDField=$ID LIMIT 1";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$cnt_image = $GLOBALS['siteURL'] . "files/no_img_1.jpg";
		if (!empty($row[0])) {
			$cnt_image = $row[0];
		}
		$retRes = $row[0];
	}
	return $retRes;
}
function ftpImage($source)
{
	header('Content-Type: image/jpeg');
	$retRes = file_get_contents('ftp://lager:bA$1IDC1@ftpshop.soennecken.de/Mediendaten/Bilddaten_Lager_2000_Pixel/' . $source);
	//print($retRes);die();
	return $retRes;
}

function returnList($Fields, $Table, $Where)
{
	$strQry = "SELECT $Fields FROM $Table $Where LIMIT 1";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Return List");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		return $row;
	} else {
		return false;
	}
}

function returnID($Field, $Table, $NameField, $Name)
{
	$retRes = "";
	$strQry = "SELECT $Field FROM $Table WHERE $NameField='$Name' LIMIT 1";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
	}
	return $retRes;
}

function countCategories($Field, $qryText)
{
	$strQry = "SELECT CatID, CatName FROM Categories WHERE ParentID = $Field $qryText";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$count = mysqli_num_rows($nResult);
	} else {
		$count = 0;
	}
	return $count;
}

function countSubCategories($Field)
{
	$strQry = "SELECT CatID, CatName FROM Categories WHERE ParentID = $Field";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$count = mysqli_num_rows($nResult);
	} else {
		$count = 0;
	}
	return $count;
}

// Return Number of products in Category
function countProducts($CatID)
{
	$strQry = "SELECT C.CatID, C.ParentID, P.ProID, P.ItemNumber, P.ProName, P.Size, P.Price, P.ProPicture FROM Categories AS C, Products AS P, Products_Categories AS PC WHERE C.CatID = PC.CatID AND P.ProID = PC.ProID AND PC.CatID = $CatID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$count = mysqli_num_rows($nResult);
	} else {
		$count = 0;
	}
	return $count;
}

// Return Number of products in Category and its Sub Category
function countProducts1($CatID)
{
	//$strQry="SELECT C.CatID, C.ParentID, P.ProID, P.ItemNumber, P.ProName, P.Size, P.Price, P.ProPicture FROM Categories AS C, Products AS P, Products_Categories AS PC WHERE C.CatID = PC.CatID AND P.ProID = PC.ProID AND PC.CatID = $CatID AND C.ParentID = $CatID";
	$strQry = "SELECT C.CatID, C.ParentID, P.ProID, P.ItemNumber, P.ProName, P.Size, P.Price, P.ProPicture FROM Categories AS C, Products AS P, Products_Categories AS PC WHERE C.CatID = $CatID AND PC.CatID = C.CatID AND P.ProID = PC.ProID OR C.ParentID = $CatID AND PC.CatID = C.CatID AND P.ProID = PC.ProID";
	//print($strQry);
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$count = mysqli_num_rows($nResult);
	} else {
		$count = 0;
	}
	return $count;
}
// function for file deletion
function DeleteFile($Field, $Table, $IDField, $ID)
{
	$strQuery = "SELECT $Field FROM $Table WHERE $IDField=$ID";
	//	print($strQuery);
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_object($nResult);
		//print($row->$Field);
		$fPath = "../" . $row->$Field;
		@unlink($fPath);
	}
}
// function for file deletion
function DeleteFileWithThumb($Field, $Table, $IDField, $ID, $iPath, $tPath)
{
	$strQuery = "SELECT $Field FROM $Table WHERE $IDField=$ID";
	//	print($strQuery);
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_object($nResult);
		$fPath = $iPath . $row->$Field;
		@unlink($fPath);
		if ($tPath != "EMPTY") {
			$fPath = $tPath . $row->$Field;
			@unlink($fPath);
		}
	}
}
// function for file deletion
function DeleteFile2($Field, $Table, $IDField, $ID, $path)
{
	$strQuery = "SELECT $Field FROM $Table WHERE $IDField=$ID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_object($nResult);
		$iPath = $path . $row->$Field;
		@unlink($iPath);
		$tPath = $path . "th/" . $row->$Field;
		@unlink($tPath);
	}
}
function Fill($Table, $IDField, $TextField, $chkSelected)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($chkSelected == $row[0]) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function ImageSize($imagesource, $DisplayH, $DisplayW)
{
	list($width, $height, $type, $attr) = getimagesize($imagesource);
	$wid = $width;
	$hig = $height;

	if ($wid > $DisplayW || $hig > $DisplayH) {
		if ($wid <= $hig) {
			$img_ratio = $wid / $hig;
			$newHeight = $DisplayH;
			$temp = $newHeight * $img_ratio;
			$newWidth = round($temp);
		} else {
			$img_ratio = $hig / $wid;
			$newWidth = $DisplayW;
			$temp = $newWidth * $img_ratio;
			$newHeight = round($temp);
		}
	} else {
		$newHeight = $hig;
		$newWidth = $wid;
	}

	$showimage = "<img src=\"" . $imagesource . "\" height=\"" . $newHeight . "\" width=\"" . $newWidth . "\" class=\"img\">";
	return $showimage;
}

function IncreaseViews($Table, $CounterFeild, $IDField, $ID)
{
	$Query = "UPDATE $Table SET $CounterFeild = $CounterFeild+1 WHERE $IDField = $ID";
	$nRst = mysqli_query($GLOBALS['conn'], $Query) or die("Unable 2 Edit Record");
}

function GetViews($Field, $Table, $IDField, $ID)
{
	$strQry = "SELECT $Field FROM $Table WHERE $IDField=$ID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$rs = mysqli_fetch_object($nResult);
		print($rs->$Field);
	}
}

function SelectDate($emonth, $eday)
{
	print("<select name=\"month1\" class=\"inputsmallBorder\">");
	for ($i = 1; $i <= 12; $i++) {
		if ($emonth == $i) {
			print("<option value=\"$i\" selected>$i</option>");
		} else {
			print("<option value=\"$i\">$i</option>");
		}
	}
	print("</select>&nbsp;");

	print("<select name=\"day1\" class=\"inputsmallBorder\">");
	for ($i = 1; $i <= 31; $i++) {
		if ($eday == $i) {
			print("<option value=\"$i\" selected>$i</option>");
		} else {
			print("<option value=\"$i\">$i</option>");
		}
	}
	print("</select>");
}

function Display_Alphabets($char, $QryString)
{
	$count = 0;
	$linksHTML = "";
	$char_array = array();
	$char_array[0]	=	"A";
	$char_array[1]	=	"B";
	$char_array[2]	=	"C";
	$char_array[3]	=	"D";
	$char_array[4]	=	"E";
	$char_array[5]	=	"F";
	$char_array[6]	=	"G";
	$char_array[7]	=	"H";
	$char_array[8]	=	"I";
	$char_array[9]	=	"J";
	$char_array[10]	=	"K";
	$char_array[11]	=	"L";
	$char_array[12]	=	"M";
	$char_array[13]	=	"N";
	$char_array[14]	=	"O";
	$char_array[15]	=	"P";
	$char_array[16]	=	"Q";
	$char_array[17]	=	"R";
	$char_array[18]	=	"S";
	$char_array[19]	=	"T";
	$char_array[20]	=	"U";
	$char_array[21]	=	"V";
	$char_array[22]	=	"W";
	$char_array[23]	=	"X";
	$char_array[24]	=	"Y";
	$char_array[25]	=	"Z";

	$linksHTML = "<table width=\"98%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\"><tr>";
	while ($count < count($char_array)) {
		if ($char == $char_array[$count]) {
			$linksHTML .= "<td align=\"center\" class=\"charSelected\">" . $char_array[$count] . "</td>";
		} else {
			if ($QryString != "") {
				$linksHTML .= "<td align=\"center\" class=\"char\"><a href=\"" . $_SERVER['PHP_SELF'] . "?char=" . $char_array[$count] . "&" . $QryString . "\" title=\"" . $char_array[$count] . "\">" . $char_array[$count] . "</a></td>";
			} else {
				$linksHTML .= "<td align=\"center\" class=\"char\"><a href=\"" . $_SERVER['PHP_SELF'] . "?char=" . $char_array[$count] . "\" title=\"" . $char_array[$count] . "\">" . $char_array[$count] . "</a></td>";
			}
		}
		$count++;
	}


	$linksHTML .= "</tr></table>";
	print($linksHTML);
}

function showBanner($location, $showOne)
{
	// show random banner where status is 1
	if ($showOne == 0) {
		$stringQry = "SELECT * FROM banner WHERE status_id = 1 AND bloc_id = " . $location;
	} else {
		$stringQry = "SELECT * FROM banner WHERE status_id = 1 AND bloc_id = " . $location . " ORDER BY RAND()";
	}
	$nRst = mysqli_query($GLOBALS['conn'], $stringQry);
	if (mysqli_num_rows($nRst) >= 1) {
		print("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">");
		while ($rowb = mysqli_fetch_object($nRst)) {
			$totalView = $rowb->banner_display + 1;
			$banID = $rowb->banner_id;
			print("<tr>");
			print("<td>");
			if ($rowb->bformat_id == 2) {
				print("<a href=\"bannerclick.php?banid=" . $banID . "&url=" . $rowb->banner_url . "\" title=\"" . $rowb->banner_alttext . "\" target=\"_blank\">");
				print("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" border=\"0\">");
				print("<param name=\"movie\" value=\"" . $rowb->banner_source . "\">");
				print("<param name=\"quality\" value=\"high\">");
				print("<embed src=\"" . $rowb->banner_source . "\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed></object>");
				print("</a>");
			} else {
				print("<a href=\"bannerclick.php?banid=" . $banID . "&url=" . $rowb->banner_url . "\" title=\"" . $rowb->banner_alttext . "\" target=\"_blank\"><img src=\"" . $rowb->banner_source . "\" alt=\"" . $rowb->banner_alttext . "\" border=\"0\" align=\"absbottom\" class=\"img\"></a>");
			}
			print("		</td>");
			print("	</tr>");
			print("<tr><td height=\"10\"></td></tr>");
		}
		print("</table>");
		mysqli_query($GLOBALS['conn'], "UPDATE banner SET banner_display=" . $totalView . " WHERE banner_id = " . $banID);
	}
}

function showBanner2($btype)
{
	// show random banner where status is 1
	$stringQry = "SELECT * FROM banners WHERE status_id = 1 AND ban_start_date <= '" . date("Y-m-d") . "' AND ban_end_date >= '" . date("Y-m-d") . "' AND btype_id = " . $btype . " ORDER BY RAND()";
	$nRst = mysqli_query($GLOBALS['conn'], $stringQry);
	if (mysqli_num_rows($nRst) >= 1) {
		print("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">");
		while ($rowb = mysqli_fetch_object($nRst)) {
			$totalView = $rowb->ban_display + 1;
			$banid = $rowb->ban_id;
			print("<tr>");
			print("<td>");
			/*	
			if($rowb->bformat_id == 2){
				print("<a href=\"bannerclick.php?banid=".$banID."&url=".$rowb->banner_url."\" title=\"".$rowb->banner_alttext."\" target=\"_blank\">");
				print("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" border=\"0\">");
				print("<param name=\"movie\" value=\"".$rowb->banner_source."\">");
				print("<param name=\"quality\" value=\"high\">");
				print("<embed src=\"".$rowb->banner_source."\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed></object>");
				print("</a>");
			}
			else{
		*/
			print("<a href=\"bannerclick.php?banid=" . $banid . "&url=" . $rowb->ban_link . "\" target=\"_blank\"><img src=\"banner_files/" . $rowb->ban_image . "\" alt=\"" . $rowb->ban_alt_text . "\" border=\"0\" align=\"absbottom\" class=\"img\"></a>");
			//	}
			print("		</td>");
			print("	</tr>");
			print("<tr><td height=\"10\"></td></tr>");
		}
		print("</table>");
		mysqli_query($GLOBALS['conn'], "UPDATE banners SET ban_display=" . $totalView . " WHERE ban_id = " . $banid);
	}
}

function createThumbnail($imageDirectory, $imageName, $thumbDirectory, $thumbWidth)
{
	$srcImg = imagecreatefromjpeg("$imageDirectory/$imageName");
	$origWidth = imagesx($srcImg);
	$origHeight = imagesy($srcImg);

	$ratio = $origWidth / $thumbWidth;

	$thumbHeight = $origHeight / $ratio;
	/*echo $origHeight ."</br>";
echo $origWidth ."</br>";
echo $ratio ."</br>";
echo $thumbHeight ."</br>";
echo $thumbWidth;
die();*/
	$thumbImg = imagecreate($thumbWidth, $thumbHeight);
	imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, imagesx($thumbImg), imagesy($thumbImg));

	imagejpeg($thumbImg, "$thumbDirectory/$imageName");
}

function createThumbnail3($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $thumbHeight)
{
	$file_path = $imageDirectory . "/" . $imageName;

	$option['jpeg_quality'] = 75;
	$option['png_quality'] = 9;

	$new_img = imagecreatetruecolor($thumbWidth, $thumbHeight);
	switch (strtolower(substr(strrchr($imageName, '.'), 1))) {
		case 'jpg':
		case 'jpeg':
			$srcImg = imagecreatefromjpeg($file_path);
			$write_image = 'imagejpeg';
			$image_quality = isset($options['jpeg_quality']) ?
				$options['jpeg_quality'] : 75;
			break;
		case 'gif':
			@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
			$srcImg = @imagecreatefromgif($file_path);
			$write_image = 'imagegif';
			$image_quality = null;
			break;
		case 'png':
			imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
			imagealphablending($new_img, false);
			imagesavealpha($new_img, true);
			$srcImg = @imagecreatefrompng($file_path);
			$write_image = 'imagepng';
			$image_quality = isset($options['png_quality']) ?
				$options['png_quality'] : 9;
			break;
		default:
			$srcImg = null;
	}
	$sourceWidth = imagesx($srcImg);
	$sourceHeight = imagesy($srcImg);
	echo $thumbDirectory . $imageName . "</br>";
	$success = $srcImg && @imagecopyresampled(
		$new_img,
		$srcImg,
		0,
		0,
		0,
		0,
		$thumbWidth,
		$thumbHeight,
		$sourceWidth,
		$sourceHeight
	) && $write_image($new_img, $thumbDirectory . '/' . $imageName, $image_quality);
	return $success;
}

function createThumbnail2($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $thumbHeight)
{
	$success = "";
	$file_path = $imageDirectory . $imageName;
	$option['jpeg_quality'] = 75;
	$option['png_quality'] = 9;
	$createThumb = 1;
	// calculate thumbnail size
	switch (strtolower(substr(strrchr($imageName, '.'), 1))) {
		case 'jpg':
		case 'jpeg':
			$srcImg = @imagecreatefromjpeg($file_path);
			break;
		case 'gif':
			$srcImg = @imagecreatefromgif($file_path);
			break;
		case 'png':
			$srcImg = @imagecreatefrompng($file_path);
			break;
		default:
			$srcImg = null;
			$createThumb = 0;
			copy($imageDirectory . $imageName, $thumbDirectory . $imageName);
			break;
	}
	if ($createThumb) {
		try {
			if (empty($srcImg)) {
				throw new Exception("exception");
			}
			$srcWidth = imagesx($srcImg);
			$srcHeight = imagesy($srcImg);

			$new_width = $thumbWidth;
			$new_height = floor($srcHeight * ($thumbWidth / $srcWidth));
			if ($new_height > $thumbHeight) {
				$new_height = $thumbHeight;
				$new_width = floor($srcWidth * ($thumbWidth / $srcHeight));
			}
			$new_img = @imagecreatetruecolor($new_width, $new_height);
			switch (strtolower(substr(strrchr($imageName, '.'), 1))) {
				case 'jpg':
				case 'jpeg':
					$srcImg = @imagecreatefromjpeg($file_path);
					$write_image = 'imagejpeg';
					$image_quality = isset($options['jpeg_quality']) ?
						$options['jpeg_quality'] : 75;
					break;
				case 'gif':
					@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
					$srcImg = @imagecreatefromgif($file_path);
					$write_image = 'imagegif';
					$image_quality = null;
					break;
				case 'png':
					@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
					@imagealphablending($new_img, false);
					@imagesavealpha($new_img, true);
					$srcImg = @imagecreatefrompng($file_path);
					$write_image = 'imagepng';
					$image_quality = isset($options['png_quality']) ?
						$options['png_quality'] : 9;
					break;
				default:
					$srcImg = null;
			}
			echo $thumbDirectory . $imageName . "</br>";
			$success = $srcImg && @imagecopyresampled(
				$new_img,
				$srcImg,
				0,
				0,
				0,
				0,
				$new_width,
				$new_height,
				$srcWidth,
				$srcHeight
			) && @$write_image($new_img, $thumbDirectory . $imageName, $image_quality);
		} catch (Exception $e) {
			$success = $e->getMessage();
		}
	}

	return $success;
}

function left_side_menu($Table, $IDField, $TextField, $ID, $parentField, $section, $table2, $page)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = 0 AND section_id='" . $section . "' AND status_id=1 ";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQry = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = $row[0] AND status_id=1";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				echo "<a class='menuheader expandable'>$row[1]</a>";
				echo "<ul class='categoryitems'>";
				while ($row1 = mysqli_fetch_row($nRs)) {
					if ($row1[0] == $ID) {
						echo ("$row1[1]");
					} else {
						$total_sub_products = TotalRecords($table2, " WHERE cat_id='" . $row1[0] . "' AND status_id=1 ");
						echo "<li><a href='" . $page . "$row1[0]'>$row1[1] ($total_sub_products) </a></li>";
					}
				}
				echo "</ul>";
			} else {
				if ($row[0] == $ID) {
					echo ("$row[1]");
				} else {
					echo ("<a class='menuheader expandable'>$row[1]</a>");
				}
			}
		}
	}
}

// FOR 2 LEVEL CATEGORIES
function left_side_menu2($Table, $IDField, $TextField, $ID, $parentField, $section)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = 0 AND section_id='" . $section . "'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQry = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = $row[0]";
			echo "<option disabled='disabled' style='font-weight:bold'>$row[1]</option>";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				while ($row1 = mysqli_fetch_row($nRs)) {
					if ($row1[0] == $ID) {
						echo "<option value='$row1[0]' selected='selected'>$row1[1]</option>";
					} else {
						echo "<option value='$row1[0]'>$row1[1]</option>";
					}
				}
			}
		}
	}
}

// FOR 3 LEVEL CATEGORIES
function left_side_menu3($Table, $IDField, $TextField, $ID, $parentField, $section)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = 0 AND section_id='" . $section . "'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			echo "<option disabled='disabled' style='font-weight:bold'>$row[1]</option>";
			$strQry = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = $row[0]";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				while ($row1 = mysqli_fetch_row($nRs)) {
					echo "<option value='$row1[0]' style='font-weight:bold'> &nbsp; &nbsp; $row1[1]</option>";
					$strQry3 = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = $row1[0]";
					$nRs3 = mysqli_query($GLOBALS['conn'], $strQry3);
					if (mysqli_num_rows($nRs3) >= 1) {
						while ($row3 = mysqli_fetch_row($nRs3)) {
							if ($row3[0] == $ID) {
								echo "<option value='$row3[0]' selected='selected'> &nbsp; &nbsp; &nbsp; &nbsp; $row3[1]</option>";
							} else {
								echo "<option value='$row3[0]'> &nbsp; &nbsp; &nbsp; &nbsp; $row3[1]</option>";
							}
						}
					}
				}
			}
		}
	}
}

function dateTime($date, $displayTime)
{
	if ($date != "") {
		$arrtime = '';
		$time = '';
		$arrdate = @explode("-", $date);
		$arrdate2 = @explode(" ", $arrdate[2]);
		if (@sizeof($arrdate2) > 1) {
			$arrtime = @explode(":", $arrdate2[1]);
			$time = @date("g:i:s a", @mktime($arrtime[0], $arrtime[1], $arrtime[2]));
		}
		$date = @date("M j, Y", @mktime(0, 0, 0, $arrdate[1], $arrdate2[0], $arrdate[0]));
		if ($date == "0000-00-00" or $date == "0000-00-00 00:00:00") {
			$date = '';
		}
		if ($displayTime == 1) {
			return $date = $date . ' ' . $time;
		} else {
			return $date = $date;
		}
	}
}

function displayAllRecords($field, $from, $where)
{
	$counter = 0;
	$Query =  "SELECT $field FROM $from WHERE $where ";
	$pro = mysqli_query($GLOBALS['conn'], $Query);
	$total_rec = mysqli_num_rows($pro);
	while ($row = mysql_fetch_assoc($pro)) {
		$counter++;
		echo $row[$field];
		if ($total_rec != $counter) {
			echo ' , ';
		}
	}
}

function redirect($url)
{
	if (!headers_sent()) {
		//If headers not sent yet... then do php redirect
		header('Location: ' . $url);
		exit;
	} else {
		//If headers are sent... do javascript redirect... if javascript disabled, do html redirect.
		echo '<script type="text/javascript">';
		echo 'window.location.href="' . $url . '";';
		echo '</script>';
		echo '<noscript>';
		echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
		echo '</noscript>';
		exit;
	}
}

function displayDateTime($date, $displayTime)
{
	if ($date != "") {
		$arrtime = '';
		$time = '';
		$arrdate = explode("-", $date);
		$arrdate2 = explode(" ", $arrdate[2]);
		if (sizeof($arrdate2) > 1) {
			$arrtime = explode(":", $arrdate2[1]);
			$time = date("g:i:s a", mktime($arrtime[0], $arrtime[1], $arrtime[2]));
		}
		$date = date("F j", mktime(0, 0, 0, $arrdate[1], $arrdate2[0], $arrdate[0]));
		if ($date == "0000-00-00" or $date == "0000-00-00 00:00:00") {
			$date = '';
		}
		if ($displayTime == 1) {
			return $date = $date . ' ' . $time;
		} else {
			return $date = $date;
		}
	}
}

function Activity($lead_id, $message)
{
	$act_id = getMaximum("activity_logs", "act_id");
	$Query_act = "INSERT INTO activity_logs(act_id,user_id, lead_id,act_details,act_datetime )VALUES(" . $act_id . ", '" . $_SESSION['UserID'] . "','" . $lead_id . "', '" . $message . "',NOW())";

	mysqli_query($GLOBALS['conn'], $Query_act) or die(mysqli_error($GLOBALS['conn']));
}

function dateTimeDiff($datadiff,  $months, $days)
{
	$string = '';

	if ($months != 0) {
		$string = $months . " months ";
	}
	if ($days != 0) {
		$string = $days . " days ";
	}
	if ($days == 0) {
		$datadiff = explode(":", $datadiff);
		$hr  = $datadiff[0];
		$min = $datadiff[1];
		$sec = $datadiff[2];
		if ($hr != 0) {
			$string = $hr . " hr ";
		}

		if ($min == 0) {
			$string = $string . $sec . " Seconds ";
		}
		if ($hr == 0) {
			$string = $string . $min . " min ";
		}
	}
	$string = $string . " ago ";
	return $string;
}

function catDisp($catID)
{
	$rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM categories WHERE cat_id = " . $catID);
	$rsMem = mysqli_fetch_object($rsM);
	return $rsMem;
	/*$name= $rsMem->cat_name;
			$catdetail= $rsMem->cat_long_details;
			$catimg= $rsMem->cat_img;
			$catimg= $rsMem->cat_header_img;
			$together= $name."-".$catdetail."-".$catimg;
			return $together;*/
}

function create_password($len)
{
	$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$str = '';
	for ($i = 0; $i < $len; $i++) {
		$str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
	}
	return $str;
}
function get_contant($field, $cnt_id, $wid_id)
{
	$rs = mysqli_query($GLOBALS['conn'], "SELECT $field AS contant FROM `widgets` WHERE wid_id = '$wid_id' AND cnt_id = '$cnt_id'");
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		$contant = $row->contant;
	} else {
		$contant = "Record not found!";
	}
	return $contant;
}

function shipment_charges($user_countrie, $user_state, $cart_weight_total)
{
	if ($user_countrie == 'Pakistan') {
		if ($user_state == 'Punjab') {
			if ($cart_weight_total <= '3') {
				//shipping_charges calculaion
				if ($cart_weight_total == '0.50') {
					$shipping_charges = get_courier_price($cart_weight_total, "1", "0", " AND cr.cz_id = '2' AND cw.cw_actual_weight = $cart_weight_total");
				} else {
					$shipping_charges = get_courier_price($cart_weight_total, "2", "0", " AND cr.cz_id = '2' AND cw.cw_actual_weight = " . ceil($cart_weight_total) . "");
				}
			} elseif ($cart_weight_total < '5') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "1", " AND cr.cz_id = '2' AND cw.cw_actual_weight = '3.00'");
			} elseif ($cart_weight_total == '5') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "0", " AND cr.cz_id = '2' AND cw.cw_actual_weight = $cart_weight_total");
			} elseif ($cart_weight_total < '10') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "1", " AND cr.cz_id = '2' AND cw.cw_actual_weight = '5.00'");
			}
		} else {
			// other provinc calculation
			if ($cart_weight_total <= '3') {
				//shipping_charges calculaion
				if ($cart_weight_total == '0.50') {
					$shipping_charges = get_courier_price($cart_weight_total, "1", "0", " AND cr.cz_id = '3' AND cw.cw_actual_weight = $cart_weight_total");
				} else {
					$shipping_charges = get_courier_price($cart_weight_total, "2", "0", " AND cr.cz_id = '3' AND cw.cw_actual_weight = $cart_weight_total");
				}
			} elseif ($cart_weight_total < '5') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "1", " AND cr.cz_id = '3' AND cw.cw_actual_weight = '3.00'");
			} elseif ($cart_weight_total == '5') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "0", " AND cr.cz_id = '3' AND cw.cw_actual_weight = $cart_weight_total");
			} elseif ($cart_weight_total < '10') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "1", " AND cr.cz_id = '3' AND cw.cw_actual_weight = '5.00'");
			}
		}
	}
	return $shipping_charges;
}
function get_courier_price($cart_weight_total, $cr_type, $additional_charges, $cr_where)
{
	$courier_price = 0;
	$additional_weight = 0;
	$actual_price = 0;
	$additional_price = 0;
	//echo "SELECT cr.cr_price , cr.cr_additional_price, cw.cw_actual_weight FROM `courier_rate` AS cr LEFT OUTER JOIN courier_weight AS cw ON cw.cw_id=cr.cw_id WHERE cr.cn_id = '1' AND cr.cr_type = '".$cr_type."' ".$cr_where.""; die;
	$rs = mysqli_query($GLOBALS['conn'], "SELECT cr.cr_price , cr.cr_additional_price, cw.cw_actual_weight FROM `courier_rate` AS cr LEFT OUTER JOIN courier_weight AS cw ON cw.cw_id=cr.cw_id WHERE cr.cn_id = '1' AND cr.cr_type = '" . $cr_type . "' " . $cr_where . "");
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		if ($additional_charges == '0') {
			//echo "if";die;
			$courier_price = $row->cr_price;
		} else {
			//echo "else";die;
			$additional_weight = ceil($cart_weight_total) - $row->cw_actual_weight;
			$actual_price = $row->cr_price;
			$additional_price = $row->cr_additional_price * $additional_weight;
			$courier_price = $actual_price + $additional_price;
		}
	}
	return $courier_price;
}

function get_email_template($eml_id)
{
	$get_data = array();
	$Query = "SELECT * FROM `emails` WHERE `eml_id` = '" . $eml_id . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$rw = mysqli_fetch_object($rs);
		$get_data[] = array(
			"eml_subject" => $rw->eml_subject,
			"eml_contents" => $rw->eml_contents
		);
	}
	$jsonResults = json_encode($get_data);
	return $jsonResults;
}

function objectToArray($d)
{
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	} else {
		// Return array
		return $d;
	}
}

function moveimage($dirName, $id, $inputName)
{
	$mfileName = "";
	//$dirName = "../files/banners/";
	if (!file_exists($dirName . $id)) {
		mkdir($dirName . $id, 0777, true);
	}
	//$inputName = "proof-right-work";
	//echo $_FILES[$inputName]["name"];die();
	if (!empty($_FILES[$inputName]["name"])) {
		$mfileName = $id . "_" . $_FILES[$inputName]["name"];
		$mfileName = str_replace(" ", "_", strtolower($mfileName));
		move_uploaded_file($_FILES[$inputName]['tmp_name'], $dirName . $mfileName);
	}
	return $mfileName;
}

function PaypalRequest($entityId, $ord_id, $order_net_amount, $usa_id, $pm_id)
{
	header('Content-Type: text/plain; charset=utf-8');
	//$url = "https://test.vr-pay-ecommerce.de/v1/payments";
	//$url = "https://vr-pay-ecommerce.de/v1/payments";
	$url = "" . config_payment_url . "";
	//$data = "entityId=".PAYPAL."" .
	$data = "entityId=" . $entityId . "" .
		"&merchantTransactionId=" . $ord_id .
		"&amount=" . $order_net_amount .
		"&currency=EUR" .
		"&paymentBrand=PAYPAL" .
		"&paymentType=PA" .
		"&shopperResultUrl=" . $GLOBALS['siteURL'] . "bestellungen/" . $entityId . "/" . $usa_id . "/" . $pm_id;
	//print_r($data);die;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization:Bearer ' . config_authorization_bearer . ''
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);
	return $responseData;
}

function KlarnaRequest($entityId, $ord_id, $order_net_amount, $usa_id, $pm_id, $klarnaBrand = "KLARNA_PAYMENTS_PAYLATER")
{
    $paymentType = match ($klarnaBrand) {
        'KLARNA_PAYMENTS_PAYNOW'   => 'DB',
        'KLARNA_PAYMENTS_PAYLATER' => 'PA',
        'KLARNA_PAYMENTS_SLICEIT'  => 'PA',
        'KLARNA_PAYMENTS_ONE'      => 'PA',
        'KLARNA_INSTALLMENTS'      => 'PA',
        'KLARNA_CHECKOUT'          => 'PA',
        'KLARNA_INVOICE'           => 'PA',
        default => 'PA'
    };

    $url = "" . config_payment_url . "";

    $data =
        "entityId=" . $entityId .
        "&merchantTransactionId=" . $ord_id .
        "&amount=" . $order_net_amount .
        "&currency=EUR" .
        "&paymentBrand=" . $klarnaBrand .
        "&paymentType=" . $paymentType .
        "&merchant.country=DE" .
        "&shopperResultUrl=" . $GLOBALS['siteURL'] . "bestellungen/" . $entityId . "/" . $usa_id . "/" . $pm_id;
		$count = 0;
		$Query = "SELECT ci.*, pro.pro_udx_seo_epag_title, fp.fp_title_de AS fp_title FROM cart_items AS ci LEFT OUTER JOIN products AS pro ON pro.supplier_id = ci.supplier_id LEFT OUTER JOIN free_product AS fp ON fp.fp_id = ci.fp_id WHERE ci.cart_id = '".$_SESSION['cart_id']."' ORDER BY ci.ci_type ASC";
		$rs = mysqli_query($GLOBALS['conn'], $Query);
		if(mysqli_num_rows($rs) > 0){
			while($rw = mysqli_fetch_object($rs)){
				$pro_title = $rw->pro_udx_seo_epag_title;
				if($rw->ci_type == 2){
					$pro_title = $rw->fp_title;
				}
				$ci_amount = $rw->ci_amount;
				$gst = $ci_amount * $rw->ci_gst_value;
				$ci_qty = $rw->ci_qty;
				$ci_total = $rw->ci_total;
				$data .= 
				"&cart.items[".$count."].currency=EUR" .
				"&cart.items[".$count."].name=".urlencode($pro_title)."" .
				"&cart.items[".$count."].price=".number_format(($ci_amount + $gst), "2", ".", "")."" .
				"&cart.items[".$count."].quantity=".$ci_qty."".
				"&cart.items[".$count."].totalAmount=".$ci_total."";
				$count++;
			}
		}
        // CART ITEM
        /*"&cart.items[0].currency=EUR" .
        "&cart.items[0].name=Leitz Ordner DIN A4 80mm PP bl" .
        "&cart.items[0].price=15.69" .
        "&cart.items[0].quantity=1";*/
		//print($data);die();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization:Bearer ' . config_authorization_bearer
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $responseData = curl_exec($ch);

    if (curl_errno($ch)) {
        return curl_error($ch);
    }

    curl_close($ch);
    return $responseData;
}

function KlarnaRequest_bk($entityId, $ord_id, $order_net_amount, $usa_id, $pm_id, $klarnaBrand = "KLARNA_PAYMENTS_PAYLATER")
{
    $paymentType = match ($klarnaBrand) {
        'KLARNA_PAYMENTS_PAYNOW'   => 'DB',
        'KLARNA_PAYMENTS_PAYLATER' => 'PA',
        'KLARNA_PAYMENTS_SLICEIT'  => 'PA',
        'KLARNA_PAYMENTS_ONE'      => 'PA',
        'KLARNA_INSTALLMENTS'      => 'PA',
        'KLARNA_CHECKOUT'          => 'PA',
        'KLARNA_INVOICE'           => 'PA',
        default => 'PA'
    };

    $url = "" . config_payment_url . "";

    $data =
        "entityId=" . $entityId .                         // ✅ REQUIRED (gateway routing id)
        "&merchantTransactionId=" . $ord_id .             // ✅ REQUIRED (unique order reference)
        "&amount=" . $order_net_amount .                  // ✅ REQUIRED (transaction amount)
        "&currency=EUR" .                                 // ✅ REQUIRED (ISO currency)
        "&paymentBrand=" . $klarnaBrand .                 // ✅ REQUIRED (method selection)
        "&paymentType=" . $paymentType .                  // ✅ REQUIRED (PA/DB etc)
        "&merchant.country=DE" .                          // ✅ REQUIRED (purchase country context)
        "&shopperResultUrl=" . $GLOBALS['siteURL'] . "bestellungen/" . $entityId . "/" . $usa_id . "/" . $pm_id .  // ⚪ OPTIONAL (redirect after flow)

        // CUSTOMER
        "&customer.givenName=John" .                      // 🟡 TYPICALLY REQUIRED (identity)
        "&customer.surname=Doe" .                         // 🟡 TYPICALLY REQUIRED
        "&customer.birthDate=1970-02-17" .                // ⚪ OPTIONAL (risk scoring / country rules)
        "&customer.email=john@doe.com" .                  // 🟡 TYPICALLY REQUIRED

        // BILLING
        "&billing.city=Berlin" .                          // 🟡 TYPICALLY REQUIRED
        "&billing.country=DE" .                           // 🟡 TYPICALLY REQUIRED
        "&billing.postcode=10115" .                       // 🟡 TYPICALLY REQUIRED
        "&billing.state=Berlin" .                         // ⚪ OPTIONAL
        "&billing.street1=Teststrasse 1" .                // 🟡 TYPICALLY REQUIRED

        // SHIPPING
        "&shipping.city=Berlin" .                         // ⚪ OPTIONAL (required if physical goods & different)
        "&shipping.country=DE" .                          // ⚪ OPTIONAL
        "&shipping.customer.email=john@doe.com" .         // ⚪ OPTIONAL
        "&shipping.givenName=John" .                      // ⚪ OPTIONAL
        "&shipping.postcode=10115" .                      // ⚪ OPTIONAL
        "&shipping.state=Berlin" .                        // ⚪ OPTIONAL
        "&shipping.street1=Teststrasse 1" .               // ⚪ OPTIONAL
        "&shipping.surname=Doe" .                         // ⚪ OPTIONAL

        // CART ITEM (at least one item required)
        "&cart.items[0].currency=EUR" .                   // ✅ REQUIRED
        "&cart.items[0].merchantItemId=120098650" .       // ⚪ OPTIONAL
        "&cart.items[0].name=Leitz Ordner DIN A4 80mm PP bl" . // ✅ REQUIRED
        "&cart.items[0].price=15.69" .                    // ✅ REQUIRED
        "&cart.items[0].quantity=1" .                     // ✅ REQUIRED
        "&cart.items[0].totalAmount=15.69" .              // ✅ REQUIRED
        "&cart.items[0].tax=0.00" .                       // ⚪ OPTIONAL
        "&cart.items[0].totalTaxAmount=0.00" .            // ⚪ OPTIONAL
        "&cart.items[0].type=basic";                      // ⚪ OPTIONAL


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization:Bearer ' . config_authorization_bearer
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $responseData = curl_exec($ch);

    if (curl_errno($ch)) {
        return curl_error($ch);
    }

    curl_close($ch);
    return $responseData;
}




function check_payment_status($id, $entityId)
{
	//$id = "8acda4a78f395b8b018f4ceec5f44ce7";
	//$entityID = "8acda4ca8dcb3477018e0a852b7e26c3";
	//$url = "https://vr-pay-ecommerce.de/v1/payments/$id";
	//$url .= "?entityId=" . $entityID;
	$url = "" . config_payment_url . "/" . $id . "?entityId=" . $entityId;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization:Bearer ' . config_authorization_bearer . ''
	));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if (curl_errno($ch)) {
		return (curl_error($ch));
	}
	curl_close($ch);

	return $responseData;
}

function capturePayment($entityId, $paymentId, $amount)
{
	$url = config_payment_url . '/' . $paymentId;
	$data = "entityId=" . $entityId .
		"&paymentType=CP" .
		"&amount=" . $amount .
		"&currency=EUR"; // You can also pass currency dynamically if needed

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization:Bearer ' . config_authorization_bearer
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);

	return $responseData;
}
function RefundPayment($entityId, $paymentId, $amount)
{
	$url = config_payment_url . '/' . $paymentId;
	$data = "entityId=" . $entityId .
		"&paymentType=RF" .
		"&amount=" . $amount .
		"&currency=EUR"; // You can also pass currency dynamically if needed

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization:Bearer ' . config_authorization_bearer
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);

	return $responseData;
}


function cardrequest($ord_id, $order_net_amount, $request, $usa_id, $pm_id)
{
	header('Content-Type: text/plain; charset=utf-8');
	//$url = "https://vr-pay-ecommerce.de/v1/payments";
	$url = "" . config_payment_url . "";
	$data = "entityId=" . $request['entityId'] .
		"&merchantTransactionId=" . $ord_id .
		"&amount=" . $order_net_amount .
		"&currency=" . $request['currency'] .
		"&paymentBrand=" . $request['brand'] .
		"&paymentType=PA" .
		"&card.number=" . $request['cardnumber'] .
		"&card.holder=" . $request['cardholder'] .
		"&card.expiryMonth=" . $request['cardmonth'] .
		"&card.expiryYear=" . $request['cardyear'] .
		"&card.cvv=" . $request['cvv'] .
		"&shopperResultUrl=" . $GLOBALS['siteURL'] . "bestellungen/" . $request['entityId'] . "/" . $usa_id . "/" . $pm_id;
	//print($data);die();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization:Bearer ' . config_authorization_bearer . ''
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);
	return $responseData;
}

function SepaRequest($request)
{

	$url = "https://vr-pay-ecommerce.de/v1/payments";
	$data = "entityId=" . SEPA . "" .
		"&amount=" . number_format($request['amount'], 2) .
		"&currency=EUR" .
		"&paymentBrand=DIRECTDEBIT_SEPA" .
		"&paymentType=DB" .
		"&bankAccount.iban=" . $request['iban'] .
		"&bankAccount.country=" . $request['country'] .
		"&bankAccount.holder=" . $request['name'] .
		"&bankAccount.mandate.id=" . $request['madateID'] .
		"&bankAccount.mandate.dateOfSignature=" . $request['madateSignature'];
	// echo '<pre>';print_r($data);die;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization:Bearer OGFjZGE0Y2E4ZGNiMzQ3NzAxOGUwYTg1MWVkMjI2YzB8TnFiZEp0TVdnOUZSZldXbg=='
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);
	return $responseData;
}


function get_pro_price($pro_id, $supplier_id, $ci_qty)
{
	$retValue = array();
	$Query = "SELECT pbp.pbp_id, pbp.pbp_price_amount AS pbp_price_without_tax, pbp.pbp_special_price_amount AS pbp_special_price_without_tax, pbp.pbp_tax FROM products_bundle_price AS pbp WHERE pbp.pro_id = '" . dbStr(trim($pro_id)) . "' AND pbp.supplier_id = '" . dbStr(trim($supplier_id)) . "' AND pbp.pbp_lower_bound BETWEEN 0 AND " . dbStr(trim($ci_qty)) . " ORDER BY pbp.pbp_lower_bound ASC";
	//print($Query);die();
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		while ($rw = mysqli_fetch_object($rs)) {
			$retValue = array(
				"pbp_id" => strval($rw->pbp_id),
				"ci_amount" => strval(((config_site_special_price > 0 && $rw->pbp_special_price_without_tax > 0) ? $rw->pbp_special_price_without_tax : $rw->pbp_price_without_tax)),
				"ci_gst_value" => strval($rw->pbp_tax)
			);
		}
	}
	return $retValue;
}

function get_delivery_charges($total)
{
	//echo $total;die();
	$delivery_charges = array();
	if ($total <= config_condition_courier_amount) {
		if (isset($_SESSION['utype_id']) && $_SESSION['utype_id'] == 4) {
			$delivery_charges = array(
				"packing" => 4,
				"shipping" => 3.99,
				"tex" => 1.52,
				"total" => 7.99
			);
		} else {
			$delivery_charges = array(
				"packing" => 4.76,
				"shipping" => 4.75,
				"tex" => 0,
				"total" => 9.51
			);
		}
	} else {
		$delivery_charges = array(
			"packing" => 0,
			"shipping" => 0,
			"tex" => 0,
			"total" => 0
		);
	}
	return $delivery_charges;
}

function user_special_price($parameter, $value, $user_id = 0, $price_type = 0)
{
	$searchWhere = "";
	if ((isset($_SESSION["UID"]) && $_SESSION["UID"] > 0) && (isset($_SESSION["utype_id"]) && in_array($_SESSION["utype_id"], array(3, 4))) && $price_type == 0) {
		$user_id = $_SESSION["UID"];
		if ($parameter ==  "supplier_id") {
			$searchWhere = "AND supplier_id = '" . dbStr(trim($value)) . "'";
		} elseif ($parameter ==  "level_two") {
			$searchWhere = "AND supplier_id = '0' AND level_two_id = '" . dbStr(trim($value)) . "'";
		} elseif ($parameter ==  "level_one") {
			$searchWhere = "AND supplier_id = '0' AND level_two_id = '0' AND level_one_id = '" . dbStr(trim($value)) . "'";
		}
		$checkrecord = checkrecord("usp_id", "user_special_price", "user_id = '" . $user_id . "' " . $searchWhere . " ");
		if ($checkrecord == 0) {
			$user_id = 0;
		}
	}
	$searchWhere = "";
	$special_price = array();
	if ($parameter ==  "supplier_id") {
		$searchWhere = "AND supplier_id = '" . dbStr(trim($value)) . "'";
	} elseif ($parameter ==  "level_two") {
		$searchWhere = "AND supplier_id = '0' AND level_two_id = '" . dbStr(trim($value)) . "'";
	} elseif ($parameter ==  "level_one") {
		$searchWhere = "AND supplier_id = '0' AND level_two_id = '0' AND level_one_id = '" . dbStr(trim($value)) . "'";
	}
	if (!empty($searchWhere)) {
		$Query = "SELECT * FROM `user_special_price` WHERE usp_status = '1' AND user_id = '" . $user_id . "' " . $searchWhere . "";
		//print($Query);
		$rs = mysqli_query($GLOBALS['conn'], $Query);
		if (mysqli_num_rows($rs) > 0) {
			$row = mysqli_fetch_object($rs);
			$special_price = array(
				"usp_price_type" => $row->usp_price_type,
				"usp_discounted_value" => $row->usp_discounted_value
			);
		}
	}
	return $special_price;
}

function discounted_price($usp_price_type, $pbp_price_amount, $usp_discounted_value, $pbp_tax = 0, $cart_calculation = 0)
{
	$usp_discounted_price = 0;
	if ($pbp_tax > 0 && $cart_calculation != 1) {
		$pbp_price_amount = number_format(($pbp_price_amount / (1 + $pbp_tax)), "2", ".", "");
	}
	if ($usp_price_type > 0) {
		$usp_discounted_price = number_format(($pbp_price_amount - $usp_discounted_value), "2", ".", "");
	} else {
		$percentage_value = ($pbp_price_amount * $usp_discounted_value) / 100;
		$usp_discounted_price = number_format(($pbp_price_amount - $percentage_value), "2", ".", "");
	}
	if ($pbp_tax > 0 && $cart_calculation != 1) {
		$usp_discounted_price = number_format(($usp_discounted_price * (1 + $pbp_tax)), "2", ".", "");
	}

	return $usp_discounted_price;
}

function cat_min_pbp_price_amount($sub_group_ids)
{
	$pbp_price_amount = 0;
	//$Query = "SELECT cm.*, pbp.*, MIN(pbp.pbp_price_amount) AS cat_min_pbp_price_amount FROM category_map AS cm LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' WHERE FIND_IN_SET('".$sub_group_ids."', cm.sub_group_ids)";
	$Query = "SELECT  MIN(pbp.pbp_price_amount) AS cat_min_pbp_price_amount FROM category_map AS cm LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' WHERE FIND_IN_SET('" . $sub_group_ids . "', cm.sub_group_ids)";
	//print($Query);die();
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);

		$pbp_price_amount = $row->cat_min_pbp_price_amount;
	}

	return $pbp_price_amount;
}

function get_font_link($font, $path = "")
{
	$get_font_link = "";

	// Map each font to its corresponding stylesheet path
	$font_map = [
		// Sans-Serif Fonts
		"Arial, sans-serif" => "css/fonts/sans-serif-fonts/arial/stylesheet.css",
		"Helvetica, sans-serif" => "css/fonts/sans-serif-fonts/helvetica/stylesheet.css",
		"Verdana, sans-serif" => "css/fonts/sans-serif-fonts/verdana/stylesheet.css",
		"Tahoma, sans-serif" => "css/fonts/sans-serif-fonts/tahoma/stylesheet.css",
		"Trebuchet MS, sans-serif" => "css/fonts/sans-serif-fonts/trebuchet-ms/stylesheet.css",
		"Calibri, sans-serif" => "css/fonts/sans-serif-fonts/calibri/stylesheet.css",
		"Open Sans, sans-serif" => "css/fonts/sans-serif-fonts/open-sans/stylesheet.css",
		"Lato, sans-serif" => "css/fonts/sans-serif-fonts/lato/stylesheet.css",
		"Roboto, sans-serif" => "css/fonts/sans-serif-fonts/roboto/stylesheet.css",
		"Source Sans Pro, sans-serif" => "css/fonts/sans-serif-fonts/source-sans-pro/stylesheet.css",

		// Serif Fonts
		"Times New Roman, serif" => "css/fonts/serif-fonts/times-new-roman/stylesheet.css",
		"Georgia, serif" => "css/fonts/serif-fonts/georgia/stylesheet.css",
		"Palatino, serif" => "css/fonts/serif-fonts/palatino/stylesheet.css",
		"Baskerville, serif" => "css/fonts/serif-fonts/baskerville/stylesheet.css",
		"Garamond, serif" => "css/fonts/serif-fonts/garamond/stylesheet.css",
		"Didot, serif" => "css/fonts/serif-fonts/didot/stylesheet.css",
		"Cambria, serif" => "css/fonts/serif-fonts/cambria/stylesheet.css",
		"Playfair Display, serif" => "css/fonts/serif-fonts/playfair-display/stylesheet.css",
		"Merriweather, serif" => "css/fonts/serif-fonts/merriweather/stylesheet.css",
		"EB Garamond, serif" => "css/fonts/serif-fonts/eb-garamond/stylesheet.css",

		// Monospace Fonts
		"Courier New, monospace" => "css/fonts/monospace-fonts/courier-new/stylesheet.css",
		"Consolas, monospace" => "css/fonts/monospace-fonts/consolas/stylesheet.css",
		"Inconsolata, monospace" => "css/fonts/monospace-fonts/inconsolata/stylesheet.css",
		"Monaco, monospace" => "css/fonts/monospace-fonts/monaco/stylesheet.css",
		"Source Code Pro, monospace" => "css/fonts/monospace-fonts/source-code-pro/stylesheet.css",
		"Fira Code, monospace" => "css/fonts/monospace-fonts/fira-code/stylesheet.css",
		"Liberation Mono, monospace" => "css/fonts/monospace-fonts/liberation-mono/stylesheet.css",
		"Menlo, monospace" => "css/fonts/monospace-fonts/menlo/stylesheet.css",
		"JetBrains Mono, monospace" => "css/fonts/monospace-fonts/jetbrains-mono/stylesheet.css",
		"Hack, monospace" => "css/fonts/monospace-fonts/hack/stylesheet.css",

		// Cursive Fonts
		"Comic Sans MS, cursive" => "css/fonts/cursive-fonts/comic-sans-ms/stylesheet.css",
		"Brush Script MT, cursive" => "css/fonts/cursive-fonts/brush-script-mt/stylesheet.css",
		"Pacifico, cursive" => "css/fonts/cursive-fonts/pacifico/stylesheet.css",
		"Dancing Script, cursive" => "css/fonts/cursive-fonts/dancing-script/stylesheet.css",
		"Great Vibes, cursive" => "css/fonts/cursive-fonts/great-vibes/stylesheet.css",
		"Lobster, cursive" => "css/fonts/cursive-fonts/lobster/stylesheet.css",
		"Sacramento, cursive" => "css/fonts/cursive-fonts/sacramento/stylesheet.css",
		"Italianno, cursive" => "css/fonts/cursive-fonts/italianno/stylesheet.css",
		"Allura, cursive" => "css/fonts/cursive-fonts/allura/stylesheet.css",
		"Parisienne, cursive" => "css/fonts/cursive-fonts/parisienne/stylesheet.css",

		// Fantasy Fonts
		"Impact, fantasy" => "css/fonts/fantasy-fonts/impact/stylesheet.css",
		"Chiller, fantasy" => "css/fonts/fantasy-fonts/chiller/stylesheet.css",
		"Curlz, fantasy" => "css/fonts/fantasy-fonts/curlz/stylesheet.css",
		"Harrington, fantasy" => "css/fonts/fantasy-fonts/harrington/stylesheet.css",
		"Jokerman, fantasy" => "css/fonts/fantasy-fonts/jokerman/stylesheet.css",
		"Stencil, fantasy" => "css/fonts/fantasy-fonts/stencil/stylesheet.css",
		"Blippo, fantasy" => "css/fonts/fantasy-fonts/blippo/stylesheet.css",
		"Bangers, fantasy" => "css/fonts/fantasy-fonts/bangers/stylesheet.css",
		"Freckle Face, fantasy" => "css/fonts/fantasy-fonts/freckle-face/stylesheet.css",
		"Almendra Display, fantasy" => "css/fonts/fantasy-fonts/almendra-display/stylesheet.css"
	];

	if (isset($font_map[$font])) {
		$get_font_link = $path . $font_map[$font];
	}

	return $get_font_link;
}

function get_image_link($replace, $link)
{
	$get_image_link = $GLOBALS['siteURL'] . "files/no_img_1.jpg";
	if (!empty($link)) {
		$get_image_link = str_replace("/2000/", "/" . $replace . "/", $link);
	}
	return $get_image_link;
}

function getShippingTiming($plz)
{


	$order_date = date('Y-m-d');
	$order_day_num = date('N', strtotime($order_date)); // 1 (Monday) - 7 (Sunday)
	$order_time = date('H:i');

	// Fetch shipping details for the given postal code
	$sqlShipping = "SELECT * FROM `shipping_timing` WHERE `plz` = ?";
	$stmt = $GLOBALS['conn']->prepare($sqlShipping);
	$stmt->bind_param("s", $plz);
	$stmt->execute();
	$resultShipping = $stmt->get_result();

	if ($rowShipp = $resultShipping->fetch_assoc()) {
		$delivery_days = [$rowShipp['day_of_delivery1'], $rowShipp['day_of_delivery2']];
		$_SESSION['ort'] = $rowShipp['plz'] . " " . $rowShipp['ort'];
		$delivery_days = array_filter($delivery_days); // Remove empty values

		return calculateDeliveryDate($order_date, $order_day_num, $order_time, $delivery_days);
	}
	$_SESSION['ort'] = (isset($_SESSION["UID"])) ? returnName("usa_zipcode", "user_shipping_address", "user_id", $_SESSION["UID"], "AND usa_defualt = '1' AND usa_type = '0'") : '';
	//return "Lieferung nicht verfügbar"; // Return if no shipping info found
	return print("Lieferung " . date('d-m-Y', strtotime("+7 day", strtotime(date_time)))); // Return if no shipping info found
}

function calculateDeliveryDate($order_date, $order_day_num, $order_time, $delivery_days)
{
	sort($delivery_days); // Sort days for easier processing
	$delivery_days_map = ["Monday" => 1, "Tuesday" => 2, "Wednesday" => 3, "Thursday" => 4, "Friday" => 5, "Saturday" => 6, "Sunday" => 7];

	foreach ($delivery_days as $delivery_day) {
		$delivery_day_num = $delivery_days_map[$delivery_day];

		if ($order_day_num == $delivery_day_num && $order_time <= "09:00") {
			return "Lieferung heute";
		} elseif ($order_day_num <= $delivery_day_num) {
			return "Lieferung " . date('d-m-Y', strtotime("next $delivery_day", strtotime($order_date)));
		}
	}

	// If order day is past the delivery days in the week, pick the next week's first delivery day
	return "Lieferung " . date('d-m-Y', strtotime("next " . $delivery_days[0], strtotime($order_date)));
}

function price_format($price)
{
	
	return number_format($price, "2", ",", ".");
}

function autocorrectQueryUsingProductTerms_bk($query, $pdo)
{
	// Get all unique words from product descriptions
	$stmt = $pdo->query("SELECT DISTINCT pro_description_short FROM products");
	$allDescriptions = $stmt->fetchAll(PDO::FETCH_COLUMN);

	// Normalize words (remove umlauts, lowercase, trim special chars)
	function normalizeWord($word)
	{
		$map = ['ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss'];
		$word = mb_strtolower(trim($word), 'UTF-8');
		$word = strtr($word, $map);
		return preg_replace('/[^a-z0-9]/', '', $word); // remove non-alphanumerics
	}

	// Extract all unique words from descriptions
	$dictionaryWords = [];
	foreach ($allDescriptions as $desc) {
		$words = preg_split('/[\s\-_,\.\'\"]+/', $desc);
		foreach ($words as $word) {
			$word = normalizeWord($word);
			if (strlen($word) > 1) {
				$dictionaryWords[$word] = true;
			}
		}
	}
	$dictionaryWords = array_keys($dictionaryWords);

	// Break query into words
	$queryWords = preg_split('/\s+/', $query);
	$correctedWords = [];

	foreach ($queryWords as $queryWord) {
		$originalWord = $queryWord;
		$normalizedQueryWord = normalizeWord($queryWord);

		if (in_array($normalizedQueryWord, $dictionaryWords)) {
			$correctedWords[] = $originalWord;
			continue;
		}

		$bestMatch = null;
		$bestScore = 0;
		$bestDistance = PHP_INT_MAX;

		foreach ($dictionaryWords as $dictWord) {
			if (abs(strlen($normalizedQueryWord) - strlen($dictWord)) > 4) {
				continue;
			}

			similar_text($normalizedQueryWord, $dictWord, $similarity);
			$lev = levenshtein($normalizedQueryWord, $dictWord);

			// Combine score: prefer better similarity and shorter distance
			if (($similarity > 70 && $lev < $bestDistance) || $similarity > $bestScore) {
				$bestMatch = $dictWord;
				$bestScore = $similarity;
				$bestDistance = $lev;
			}
		}
		print("bestMatch: " . $bestMatch . "<br> bestScore: " . $bestScore . "<br> bestDistance: " . $bestDistance);
		die();
		if ($bestMatch !== null && $bestScore > 65) {
			// Try to restore proper case
			if (ctype_upper($originalWord)) {
				$correctedWords[] = strtoupper($bestMatch);
			} elseif (ucfirst($originalWord) === $originalWord) {
				$correctedWords[] = ucfirst($bestMatch);
			} else {
				$correctedWords[] = $bestMatch;
			}
		} else {
			$correctedWords[] = $originalWord;
		}
	}

	return [
		'original' => $query,
		'corrected' => implode(' ', $correctedWords)
	];
}

function autocorrectQueryUsingProductTerms_bk1($query, $pdo)
{
	// Get all unique short descriptions from the products
	$stmt = $pdo->query("SELECT DISTINCT pro_udx_seo_internetbezeichung FROM products");
	$allDescriptions = $stmt->fetchAll(PDO::FETCH_COLUMN);

	// Normalization function for words
	function normalizeWord($word)
	{
		$map = ['ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss', 'Ä'=>'ae','Ö'=>'oe','Ü'=>'ue'];
		$word = mb_strtolower(trim($word), 'UTF-8');
		$word = strtr($word, $map);
		return preg_replace('/[^a-z0-9]/', '', $word); // remove non-alphanumerics
	}

	// Build dictionary: normalized word => original word
	$dictionaryWords = [];
	foreach ($allDescriptions as $desc) {
		$words = preg_split('/[\s\-_,\.\'\"\/\(\)\[\]]+/', $desc);
		foreach ($words as $word) {
			$normalized = normalizeWord($word);
			if (strlen($normalized) > 1 && !isset($dictionaryWords[$normalized])) {
				$dictionaryWords[$normalized] = $word;
			}
		}
	}

	// Split the query into words
	$queryWords = preg_split('/\s+/', $query);
	$correctedWords = [];

	foreach ($queryWords as $queryWord) {
		$originalWord = $queryWord;
		$normalizedQueryWord = normalizeWord($queryWord);

		// ✅ Exact match
		if (isset($dictionaryWords[$normalizedQueryWord])) {
			$correctedWords[] = matchCase($originalWord, $dictionaryWords[$normalizedQueryWord]);
			continue;
		}

		// Fallback: fuzzy match
		$bestMatch = null;
		$bestOriginal = null;
		$bestScore = 0;
		$bestDistance = PHP_INT_MAX;

		foreach ($dictionaryWords as $dictNorm => $dictOriginal) {
			if (abs(strlen($normalizedQueryWord) - strlen($dictNorm)) > 4) {
				continue;
			}

			similar_text($normalizedQueryWord, $dictNorm, $similarity);
			$lev = levenshtein($normalizedQueryWord, $dictNorm);

			if (($similarity > 70 && $lev < $bestDistance) || $similarity > $bestScore) {
				$bestMatch = $dictNorm;
				$bestOriginal = $dictOriginal;
				$bestScore = $similarity;
				$bestDistance = $lev;
			}
		}

		if ($bestOriginal !== null && $bestScore > 65) {
			$correctedWords[] = matchCase($originalWord, $bestOriginal);
		} else {
			$correctedWords[] = $originalWord;
		}
	}

	return [
		'original' => $query,
		'corrected' => implode(' ', $correctedWords)
	];
}

function autocorrectQueryUsingProductTerms_bk2($query, $pdo)
{
	// Fetch product descriptions (order stabilized)
	$stmt = $pdo->query("
		SELECT DISTINCT pro_udx_seo_epag_title
		FROM products
		WHERE pro_udx_seo_epag_title IS NOT NULL
		AND pro_udx_seo_epag_title != ''
		ORDER BY pro_udx_seo_epag_title ASC
			");
	$allDescriptions = $stmt->fetchAll(PDO::FETCH_COLUMN);

	// Normalize words (German-safe)
	function normalizeWord($word)
	{
		$map = [
			'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss',
			'Ä' => 'ae', 'Ö' => 'oe', 'Ü' => 'ue'
		];

		$word = mb_strtolower(trim($word), 'UTF-8');
		$word = strtr($word, $map);

		return preg_replace('/[^a-z0-9]/', '', $word);
	}

	// Build dictionary: normalized => [originals]
	$dictionaryWords = [];

	foreach ($allDescriptions as $desc) {
		$words = preg_split('/[\s\-_,\.\'\"\/\(\)\[\]]+/', $desc);

		foreach ($words as $word) {
			$normalized = normalizeWord($word);

			if (strlen($normalized) > 1) {
				$dictionaryWords[$normalized][] = $word;
			}
		}
	}

	// Split search query
	$queryWords = preg_split('/\s+/', trim($query));
	$correctedWords = [];

	foreach ($queryWords as $queryWord) {
		$normalizedQueryWord = normalizeWord($queryWord);

		// 🔒 1. If exact normalized word exists → NEVER autocorrect
		if (isset($dictionaryWords[$normalizedQueryWord])) {
			$correctedWords[] = $queryWord;
			continue;
		}

		// 🔍 Fuzzy matching
		$bestWord = null;
		$bestScore = 0;
		$bestDistance = PHP_INT_MAX;

		foreach ($dictionaryWords as $dictNorm => $dictOriginals) {

			// Length sanity check
			if (abs(strlen($normalizedQueryWord) - strlen($dictNorm)) > 2) {
				continue;
			}

			similar_text($normalizedQueryWord, $dictNorm, $similarity);
			$lev = levenshtein($normalizedQueryWord, $dictNorm);

			if (
				$similarity > $bestScore ||
				($similarity == $bestScore && $lev < $bestDistance)
			) {
				$bestScore = $similarity;
				$bestDistance = $lev;
				$bestWord = $dictOriginals[0]; // safest original
			}
		}

		// 🎯 Confidence threshold (stricter for short words)
		$minSimilarity = strlen($normalizedQueryWord) <= 6 ? 85 : 70;

		if ($bestWord !== null && $bestScore >= $minSimilarity) {
			$correctedWords[] = $bestWord;
		} else {
			$correctedWords[] = $queryWord;
		}
	}

	return [
		'original'  => $query,
		'corrected' => implode(' ', $correctedWords)
	];
}

function autocorrectQueryUsingProductTerms_bk3_best($query, $pdo)
{
    // Fetch both SEO title and keywords
    $stmt = $pdo->query("
        SELECT DISTINCT pro_udx_seo_epag_title, pro_manufacture_aid
        FROM products
        WHERE 
            (pro_udx_seo_epag_title IS NOT NULL AND pro_udx_seo_epag_title != '')
			OR
			(pro_manufacture_aid IS NOT NULL AND pro_manufacture_aid != '')
    ");

	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Normalize words (German-safe)
    function normalizeWord($word)
    {
        $map = [
            'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss',
            'Ä' => 'ae', 'Ö' => 'oe', 'Ü' => 'ue'
        ];

        $word = mb_strtolower(trim($word), 'UTF-8');
        $word = strtr($word, $map);

        return preg_replace('/[^a-z0-9]/', '', $word);
    }

    // Build dictionary: normalized => [originals]
    $dictionaryWords = [];

    foreach ($rows as $row) {

        // Merge both fields into one string
        $combinedText = trim(
            ($row['pro_udx_seo_epag_title'] ?? '') . ' ' .
            ($row['pro_manufacture_aid'] ?? '')
        );

        if ($combinedText === '') {
            continue;
        }

        // Split words (supports spaces + commas)
        $words = preg_split('/[\s,\-_\.\'\"\/\(\)\[\]]+/', $combinedText);

        foreach ($words as $word) {
            $normalized = normalizeWord($word);

            if (strlen($normalized) > 1) {
                // prevent duplicate originals
                $dictionaryWords[$normalized][$word] = true;
            }
        }
    }

    // Split search query
    $queryWords = preg_split('/\s+/', trim($query));
    $correctedWords = [];

    foreach ($queryWords as $queryWord) {
        $normalizedQueryWord = normalizeWord($queryWord);

        // 🔒 Exact normalized word → never autocorrect
        if (isset($dictionaryWords[$normalizedQueryWord])) {
            $correctedWords[] = $queryWord;
            continue;
        }

        // 🔍 Fuzzy matching
        $bestWord = null;
        $bestScore = 0;
        $bestDistance = PHP_INT_MAX;

        foreach ($dictionaryWords as $dictNorm => $dictOriginals) {

            // Length sanity check
            if (abs(strlen($normalizedQueryWord) - strlen($dictNorm)) > 2) {
                continue;
            }

            similar_text($normalizedQueryWord, $dictNorm, $similarity);
            $lev = levenshtein($normalizedQueryWord, $dictNorm);

            if (
                $similarity > $bestScore ||
                ($similarity == $bestScore && $lev < $bestDistance)
            ) {
                $bestScore = $similarity;
                $bestDistance = $lev;
                // safest original
                $bestWord = array_key_first($dictOriginals);
            }
        }

        // 🎯 Confidence threshold
        $minSimilarity = strlen($normalizedQueryWord) <= 6 ? 85 : 70;

        if ($bestWord !== null && $bestScore >= $minSimilarity) {
            $correctedWords[] = $bestWord;
        } else {
            $correctedWords[] = $queryWord;
        }
    }

    return [
        'original'  => $query,
        'corrected' => implode(' ', $correctedWords)
    ];
}

function autocorrectQueryUsingProductTerms($query, $pdo)
{
    $stmt = $pdo->query("
        SELECT DISTINCT pro_udx_seo_epag_title, pro_manufacture_aid
        FROM products
        WHERE 
            (pro_udx_seo_epag_title IS NOT NULL AND pro_udx_seo_epag_title != '')
            OR
            (pro_manufacture_aid IS NOT NULL AND pro_manufacture_aid != '')
    ");

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /* ---------------- Normalization ---------------- */

    function normalizeWord($word)
    {
        $map = [
            'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss',
            'Ä' => 'ae', 'Ö' => 'oe', 'Ü' => 'ue'
        ];

        $word = mb_strtolower(trim($word), 'UTF-8');
        $word = strtr($word, $map);

        return preg_replace('/[^a-z0-9]/', '', $word);
    }

    function isProductCode($word)
    {
        // any digit → treat as manufacturer code
        return preg_match('/\d/', $word);
    }

    /* ---------------- Dictionaries ---------------- */

    $seoDictionary = [];
    $manufacturerDictionary = [];

    foreach ($rows as $row) {

        if (!empty($row['pro_udx_seo_epag_title'])) {
            $words = preg_split('/[\s,\-_\.\'\"\/\(\)\[\]]+/', $row['pro_udx_seo_epag_title']);
            foreach ($words as $word) {
                $n = normalizeWord($word);
                if (strlen($n) > 1) {
                    $seoDictionary[$n][$word] = true;
                }
            }
        }

        if (!empty($row['pro_manufacture_aid'])) {
            $words = preg_split('/[\s,\-_\.\'\"\/\(\)\[\]]+/', $row['pro_manufacture_aid']);
            foreach ($words as $word) {
                $n = normalizeWord($word);
                if (strlen($n) > 1) {
                    $manufacturerDictionary[$n][$word] = true;
                }
            }
        }
    }

    /* ---------------- Matcher ---------------- */

    function findBestMatch($queryWord, $dictionary)
    {
        $normalizedQueryWord = normalizeWord($queryWord);

        // ✅ Exact normalized match → return canonical DB value
        if (isset($dictionary[$normalizedQueryWord])) {
            return array_key_first($dictionary[$normalizedQueryWord]);
        }

        $bestWord = null;
        $bestScore = 0;
        $bestDistance = PHP_INT_MAX;

        foreach ($dictionary as $dictNorm => $dictOriginals) {

            if (abs(strlen($normalizedQueryWord) - strlen($dictNorm)) > 2) {
                continue;
            }

            similar_text($normalizedQueryWord, $dictNorm, $similarity);
            $lev = levenshtein($normalizedQueryWord, $dictNorm);

            if (
                $similarity > $bestScore ||
                ($similarity == $bestScore && $lev < $bestDistance)
            ) {
                $bestScore = $similarity;
                $bestDistance = $lev;
                $bestWord = array_key_first($dictOriginals);
            }
        }

        $minSimilarity = strlen($normalizedQueryWord) <= 6 ? 85 : 70;

        return ($bestWord !== null && $bestScore >= $minSimilarity)
            ? $bestWord
            : null;
    }

    /* ---------------- Query Processing ---------------- */

    // 🔥 FIX: break lc-121 → lc 121
    $queryWords = preg_split('/[\s\-_\.\'\"\/\(\)\[\]]+/', trim($query));
    $correctedWords = [];

    foreach ($queryWords as $queryWord) {

        if ($queryWord === '') {
            continue;
        }

        // 🔢 Codes → manufacturer FIRST
        if (isProductCode($queryWord)) {

            $match = findBestMatch($queryWord, $manufacturerDictionary)
                  ?? findBestMatch($queryWord, $seoDictionary);

        }
        // 🔤 Text → SEO FIRST
        else {

            $match = findBestMatch($queryWord, $seoDictionary)
                  ?? findBestMatch($queryWord, $manufacturerDictionary);
        }

        $correctedWords[] = $match ?? $queryWord;
    }

    return [
        'original'  => $query,
        'corrected' => implode(' ', $correctedWords)
    ];
}



// Helper to preserve casing style
function matchCase($input, $reference)
{
	if (ctype_upper($input)) {
		return strtoupper($reference);
	} elseif (ucfirst($input) === $input) {
		return ucfirst($reference);
	} else {
		return strtolower($reference);
	}
}


function cart_to_order($user_id, $usa_id, $pm_id, $entityId = null, $ord_payment_transaction_id = null)
{
	$order_success = false;

	$Query = "SELECT usa.*, u.user_name  FROM user_shipping_address AS usa LEFT OUTER JOIN users AS u ON u.user_id = usa.user_id WHERE usa.user_id = '" . $user_id . "' AND usa.usa_id ='" . $usa_id . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$rw = mysqli_fetch_object($rs);
		$usa_id = $rw->usa_id;
		$dinfo_fname = $rw->usa_fname;
		$dinfo_lname = $rw->usa_lname;
		$dinfo_email = $rw->user_name;
		$dinfo_phone = $rw->usa_contactno;
		$dinfo_street = $rw->usa_street;
		$dinfo_house_no = $rw->usa_house_no;
		$dinfo_address = $rw->usa_address;
		$dinfo_countries_id = $rw->countries_id;
		$dinfo_usa_zipcode = $rw->usa_zipcode;
		$dinfo_additional_info = !empty($rw->usa_additional_info) ? $rw->usa_additional_info : '';
	}

	$orders_table_check = 0;
	$order_items_table_check = 0;
	$Query1 = "SELECT * FROM `cart` WHERE `cart_id` = '" . $_SESSION['cart_id'] . "'";
	$rs1 = mysqli_query($GLOBALS['conn'], $Query1);
	if (mysqli_num_rows($rs1) > 0) {
		$row1 = mysqli_fetch_object($rs1);
		$ord_id = getMaximum("orders", "ord_id");
		$dinfo_id = getMaximum("delivery_info", "dinfo_id");
		$ord_shipping_charges = 0;
		if ($row1->cart_amount <= config_condition_courier_amount) {
			$ord_shipping_charges = config_courier_fix_charges;
		}
		$order_net_amount = number_format(($row1->cart_amount + $ord_shipping_charges), "2", ".", "");
		$ord_note = "";
		if (isset($_SESSION['ord_note']) && !empty($_SESSION['ord_note'])) {
			$ord_note = dbStr(trim($_SESSION['ord_note']));
		}
		$delivery_instruction = "";
		if (isset($_SESSION['delivery_instruction']) && !empty($_SESSION['delivery_instruction'])) {
			$delivery_instruction = dbStr(trim($_SESSION['delivery_instruction']));
		}
		mysqli_query($GLOBALS['conn'], "INSERT INTO orders (ord_id, user_id, guest_id, ord_gross_total, ord_gst, ord_discount, ord_amount, ord_shipping_charges, ord_payment_entity_id, ord_payment_transaction_id, ord_payment_method, ord_note, ord_datetime) VALUES ('" . $ord_id . "', '" . $user_id . "', '" . $_SESSION['sess_id'] . "', '" . $row1->cart_gross_total . "',  '" . $row1->cart_gst . "',  '" . $row1->cart_discount . "', '" . $row1->cart_amount . "', '" . $ord_shipping_charges . "', '" . $entityId . "', '" . $ord_payment_transaction_id . "', '" . $pm_id . "', '" . $ord_note . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
		mysqli_query($GLOBALS['conn'], "INSERT INTO delivery_info (dinfo_id, ord_id, user_id, usa_id, delivery_instruction, guest_id, dinfo_fname, dinfo_lname, dinfo_phone, dinfo_email, dinfo_street, dinfo_house_no, dinfo_address, dinfo_countries_id, dinfo_usa_zipcode, dinfo_additional_info) VALUES ('" . $dinfo_id . "', '" . $ord_id . "', '" . $user_id . "', '" . $usa_id . "', '" . $delivery_instruction . "', '" . $_SESSION['sess_id'] . "', '" . $dinfo_fname . "', '" . $dinfo_lname . "', '" . $dinfo_phone . "', '" . $dinfo_email . "', '" . $dinfo_street . "', '" . $dinfo_house_no . "', '" . $dinfo_address . "', '" . $dinfo_countries_id . "', '" . $dinfo_usa_zipcode . "', '" . $dinfo_additional_info . "')") or die(mysqli_error($GLOBALS['conn']));
		$orders_table_check = 1;
	}

	$Query2 = "SELECT * FROM `cart_items` WHERE `cart_id` = '" . $_SESSION['cart_id'] . "' ORDER BY `ci_id` ASC";
	$rs2 = mysqli_query($GLOBALS['conn'], $Query2);
	if (mysqli_num_rows($rs2) > 0) {
		while ($row2 = mysqli_fetch_object($rs2)) {
			$ci_id = $row2->ci_id;
			$oi_id = getMaximum("order_items", "oi_id");
			mysqli_query($GLOBALS['conn'], "INSERT INTO order_items (oi_id, ord_id, supplier_id, pro_id, pbp_id, oi_type, fp_id, pbp_price_amount, oi_amount, oi_discounted_amount, oi_qty, oi_qty_type, oi_gross_total, oi_gst_value, oi_gst, oi_discount_type, oi_discount_value, oi_discount, oi_net_total) VALUES ('" . $oi_id . "', '" . $ord_id . "', '" . $row2->supplier_id . "', '" . $row2->pro_id . "', '" . $row2->pbp_id . "', '" . $row2->ci_type . "', '" . $row2->fp_id . "', '" . $row2->pbp_price_amount . "', '" . $row2->ci_amount . "', '" . $row2->ci_discounted_amount . "','" . $row2->ci_qty . "', '" . $row2->ci_qty_type . "', '" . $row2->ci_gross_total . "','" . $row2->ci_gst_value . "', '" . $row2->ci_gst . "', '" . $row2->ci_discount_type . "', '" . $row2->ci_discount_value . "', '" . $row2->ci_discount . "', '" . $row2->ci_total . "')") or die(mysqli_error($GLOBALS['conn']));
			quantityUpdate("-", $row2->supplier_id, $row2->ci_qty, $row2->ci_qty_type, $row2->ci_type);
			$order_items_table_check = 1;
		}
	}

	if ($orders_table_check == 1 && $order_items_table_check == 1) {
		if ($pm_id == 7) {
			require_once("mailer.php");
			$mailer = new Mailer();
			$mailer->vorkasse($dinfo_email, $ord_id, $dinfo_fname . ' ' . $dinfo_lname);
		}
		mysqli_query($GLOBALS['conn'], "DELETE FROM cart WHERE cart_id = '" . $_SESSION['cart_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
		mysqli_query($GLOBALS['conn'], "DELETE FROM cart_items WHERE cart_id = '" . $_SESSION['cart_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
		unset($_SESSION['cart_id']);
		unset($_SESSION['sess_id']);
		unset($_SESSION['ci_id']);
		unset($_SESSION['header_quantity']);
		unset($_SESSION['ord_note']);
		unset($_SESSION['delivery_instruction']);
		if (isset($_SESSION["cart_check"])) {
			unset($_SESSION["cart_check"]);
		}
		$order_success = true;
	}

	return $order_success;
}

function orderquantityUpdate($ord_id)
{
	$Query = "SELECT * FROM `order_items` WHERE ord_id = '" . $ord_id . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		while ($rw = mysqli_fetch_object($rs)) {
			quantityUpdate("+", $rw->supplier_id, $rw->oi_qty, $rw->oi_qty_type, $rw->oi_type);
		}
	}
}
function quantityUpdate($opration, $supplier_id, $quantity, $quantity_type, $ci_type = 0)
{
	$field = "pq_quantity";
	if ($quantity_type > 0 && $ci_type == 0) {
		$field = "pq_upcomming_quantity";
	} elseif ($ci_type > 0) {
		$field = "pq_physical_quantity";
	}
	if ($opration == "+") {
		mysqli_query($GLOBALS['conn'], "UPDATE products_quantity SET " . $field . " = " . $field . " + '" . $quantity . "' WHERE supplier_id = '" . dbStr(trim($supplier_id)) . "'") or die(mysqli_error($GLOBALS['conn']));
	} else {
		mysqli_query($GLOBALS['conn'], "UPDATE products_quantity SET " . $field . " = " . $field . " - '" . $quantity . "' WHERE supplier_id = '" . dbStr(trim($supplier_id)) . "'") or die(mysqli_error($GLOBALS['conn']));
	}
}

function checkquantity($supplier_id, $ci_qty, $cart_quantity, $ci_qty_type, $ci_type)
{
	$return_quantity = 0;
	$cart_quantity_total = $ci_qty + $cart_quantity;
	$field = "pq_quantity";
	if ($ci_qty_type > 0 && $ci_type == 0) {
		$field = "pq_upcomming_quantity";
	} elseif ($ci_type > 0) {
		$field = "pq_physical_quantity";
	}
	$Query = "SELECT * FROM `products_quantity` WHERE supplier_id = '" . $supplier_id . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$rw = mysqli_fetch_object($rs);
		if ($cart_quantity_total >= $rw->$field) {
			$return_quantity = $rw->$field;
		} else {
			$return_quantity = $cart_quantity_total;
		}
	}
	return $return_quantity;
}

function delivery_instruction($usa_id, $usa_delivery_instructions_tab_active)
{
	$delivery_instruction = "";
	$property_type = array("Haus", "Wohnung", "Unternehmen", "Sonstiges");
	$Query = "SELECT * FROM user_shipping_address AS usa WHERE usa.usa_id = '" . $usa_id . "' ";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		if ($usa_delivery_instructions_tab_active == 1) {

			$delivery_instruction = "<br><b>Grundstückstyp: " . $property_type[$row->usa_delivery_instructions_tab_active - 1] . ":</b> " . $row->usa_house_check . "";
			if ($row->usa_house_check == "Bei einem Nachbarn") {
				$delivery_instruction = "<br><b>Grundstückstyp: " . $property_type[$row->usa_delivery_instructions_tab_active - 1] . ":</b> " . $row->usa_house_check . "
				<br> <b>Name des Nachbarn: </b>" . $row->usa_house_neighbor_name . "
				<br> <b>Adresse des Nachbarn/der Nachbarin: </b>" . $row->usa_house_neighbor_address . "";
			}
		} elseif ($usa_delivery_instructions_tab_active == 2) {

			$apartment_data = "";
			if (!empty($row->usa_apartment_security_code)) {
				$apartment_data .= "<br><b>Sicherheitscode:</b> " . $row->usa_apartment_security_code;
			}
			if (!empty($row->usa_appartment_call_box)) {
				$apartment_data .= "<br><b>Gegensprechanlage:</b> " . $row->usa_appartment_call_box;
			}
			if (!empty($row->usa_appartment_check)) {
				$apartment_data .= "<br>Schlüssel oder Token benötigt";
			}
			$delivery_instruction = "<br><b>Grundstückstyp: " . $property_type[$row->usa_delivery_instructions_tab_active - 1] . "</b>" . $apartment_data;
		} elseif ($usa_delivery_instructions_tab_active == 3) {
			$business_data = "";
			if ($row->usa_business_mf_24h_check > 0) {
				$business_data .= "<br><b>Montag - Freitag:</b> 24 Stunden geöffnet";
			} else {
				$business_data .= "<br><b>Montag - Freitag:</b> " . $row->usa_business_mf_status . "; <b>Gruppierung aufheben:</b> " . $row->usa_business_mf_uw_status;
			}
			if ($row->usa_business_24h_check == 0 && $row->usa_business_close_check == 0) {
				$business_data .= "<br><b>Samstag - Sonntag:</b> " . $row->usa_business_ss_status . "; <b>Gruppierung aufheben:</b> " . $row->usa_business_ss_uw_status;
			}

			if ($row->usa_business_24h_check > 0) {
				$business_data .= "<br><b>Samstag - Sonntag:</b> 24 Stunden geöffnet";
			}
			if ($row->usa_business_close_check > 0) {
				$business_data .= "<br><b>Samstag - Sonntag:</b> Für Lieferungen geschlossen";
			}


			if ($row->usa_business_mf_type > 0 || $row->usa_business_ss_type > 0) {
				$business_data = "";
			}
			if ($row->usa_business_mf_type > 0) {
				$Query = "SELECT * FROM shipping_business_ungroup_days WHERE sbugd_type = '0' AND usa_id = '" . $usa_id . "' ORDER BY sbugd_orderby ASC";
				$rs = mysqli_query($GLOBALS['conn'], $Query);
				if (mysqli_num_rows($rs) > 0) {
					while ($rw = mysqli_fetch_object($rs)) {
						if ($rw->sbugd_24hour_open > 0) {
							$business_data .= "<br><b>" . $rw->sbugd_day . ":</b> 24 Stunden geöffnet";
						} else {
							$business_data .= "<br><b>" . $rw->sbugd_day . ":</b> " . $rw->sbugd_open . "; <b>Gruppierung aufheben:</b> " . $rw->sbugd_close;
						}
					}
				}
			}
			if ($row->usa_business_ss_type > 0) {
				$Query = "SELECT * FROM shipping_business_ungroup_days WHERE sbugd_type = '1' AND usa_id = '" . $usa_id . "' ORDER BY sbugd_orderby ASC";
				$rs = mysqli_query($GLOBALS['conn'], $Query);
				if (mysqli_num_rows($rs) > 0) {
					while ($rw = mysqli_fetch_object($rs)) {
						if ($rw->sbugd_24hour_open == 0 && $rw->sbugd_close_delivery == 0) {
							$business_data .= "<br><b>" . $rw->sbugd_day . ":</b> " . $rw->sbugd_open . "; <b>Gruppierung aufheben:</b> " . $rw->sbugd_close;
						}
						if ($rw->sbugd_24hour_open > 0) {
							$business_data .= "<br><b>" . $rw->sbugd_day . ":</b> 24 Stunden geöffnet";
						}
						if ($rw->sbugd_close_delivery > 0) {
							$business_data .= "<br><b>" . $rw->sbugd_day . ":</b> Für Lieferungen geschlossen";
						}
					}
				}
			}

			$delivery_instruction = "<br><b>Grundstückstyp: " . $property_type[$row->usa_delivery_instructions_tab_active - 1] . "</b>" . $business_data;
		} elseif ($usa_delivery_instructions_tab_active == 4) {

			$delivery_instruction = "<br><b>Grundstückstyp: " . $property_type[$row->usa_delivery_instructions_tab_active - 1] . "</b> <br> " . $row->usa_other_check . "";
		}
	}
	return $delivery_instruction;
}

function formatDateGerman($datetime, $format = 'j F, Y')
{
	$timestamp = strtotime($datetime);

	$en = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	$de = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So', 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];

	$formatted = date($format, $timestamp);
	return str_replace($en, $de, $formatted);
}

function convertGermanChars($string)
{
	$map = [
		'ä' => 'ae',
		'ö' => 'oe',
		'ü' => 'ue',
		'Ä' => 'Ae',
		'Ö' => 'Oe',
		'Ü' => 'Ue',
		'ß' => 'ss'
	];
	return strtr($string, $map);
}

function product_detail_url($supplier_id, $ci_type = 0)
{
	$product_detail_url = "javascript: void();";
	$Query = "SELECT pro_id, supplier_id, pro_ean, pro_udx_seo_epag_title_params_de, pro_url FROM products WHERE supplier_id = '" . $supplier_id . "' ORDER BY pro_id ASC";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		//$pro_udx_seo_internetbezeichung_params_de = "product/".$row->pro_udx_seo_internetbezeichung_params_de;
		//$product_detail_url = $row->pro_udx_seo_epag_title_params_de . "-" . $row->pro_ean;
		$product_detail_url = $row->pro_url;
		if ($ci_type > 0) {
			//$pro_udx_seo_internetbezeichung_params_de = "product/1/".$row->pro_udx_seo_internetbezeichung_params_de;
			//$product_detail_url = "1/" . $row->pro_udx_seo_epag_title_params_de . "-" . $row->pro_ean;
			$product_detail_url = "1/" . $row->pro_url;
		}
	}
	return $product_detail_url;
}

function ci_max_quentity()
{
	$cart_amount = returnName("cart_amount", "cart", "cart_id", $_SESSION['cart_id']);
	$ci_total_free = returnSum("ci_total", "cart_items", "cart_id", $_SESSION['cart_id'], " AND ci_discount_value > 0");
	if ($ci_total_free > 0) {
		$cart_amount = $cart_amount - $ci_total_free;
	}
	if ($cart_amount > 0) {
		$Query = "SELECT ci_id, pbp_price_amount AS fp_price, ci_qty FROM cart_items WHERE ci_type = '2' AND cart_id = '" . $_SESSION['cart_id'] . "' ORDER BY pbp_price_amount ASC";
		$rs = mysqli_query($GLOBALS['conn'], $Query);
		if (mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_object($rs)) {
				$fp_price = $row->fp_price * $row->ci_qty;
				if ($cart_amount > $fp_price) {
					$max_quentity = floor($cart_amount / $row->fp_price);
					$cart_amount = $cart_amount - $fp_price;
					mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET ci_max_quentity = '" . $max_quentity . "' WHERE ci_type = '2' AND ci_id = '" . $row->ci_id . "' ") or die(mysqli_error($GLOBALS['conn']));
				} else {
					if ($cart_amount > $row->fp_price) {
						$ci_qty = floor($cart_amount / $row->fp_price);
						mysqli_query($GLOBALS['conn'], "UPDATE cart_items SET ci_qty = '" . $ci_qty . "', ci_max_quentity = '0' WHERE ci_type = '2' AND ci_id = '" . $row->ci_id . "' ") or die(mysqli_error($GLOBALS['conn']));
					} else {
						mysqli_query($GLOBALS['conn'], "DELETE FROM cart_items WHERE ci_type = '2' AND ci_id = '" . $row->ci_id . "' ") or die(mysqli_error($GLOBALS['conn']));
					}
				}
			}
		}
	} else {
		mysqli_query($GLOBALS['conn'], "DELETE FROM cart_items WHERE ci_type = '2' AND cart_id = '" . $_SESSION['cart_id'] . "'") or die(mysqli_error($GLOBALS['conn']));
	}
}
