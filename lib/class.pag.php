<?php
class Paging {
	public function findStart($limit) {
		if ((!isset($_GET['page'])) || ($_GET['page'] == "1")) { 
			$start = 0; 
			$_GET['page'] = 1; 
		}
		else {
		$start = ($_GET['page']-1) * $limit; 
		} 
		return $start; 
	}
	public function findPages($count, $limit) {
		$pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
		return $pages;
	}
	public function pageList($curpage, $pages, $qryString) { 
		$page_list  = "";
		if (($curpage != 1) && ($curpage)) {
			//$page_list .= "  <a href=\"".$_SERVER['PHP_SELF']."?page=1 title=\"First Page\" class=\"numbr selected\"></a> "; 
		} 
		if (($curpage-1) > 0) {
			$page_list .= "<li><a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage-1).$qryString."\" title=\" Previous Page ".($curpage-1)."\">"."<img src='images/next_arrow_pnton.png' alt='' />"."</a></li>";	  
			//$page_list .= " <a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage-1)."\" title=\"Previous Page\"> < </a> "; 
		} 
		/* Print the numeric page list; make the current page unlinked and bold */ 
		for ($i=1; $i<=$pages; $i++) {
			if ($i == $curpage) {
				//$page_list .= "<b class=\"numbr selected\">".$i."</b>"; 
				$page_list .= "<li class='numbr active'><a href='' >".$i."</a></li>";
			} 
			else { 
				$page_list .= "<li><a href=\"".$_SERVER['PHP_SELF']."?page=".$i.$qryString."\" title=\"Page ".$i."\">".$i."</a></li>"; 
			} 
			$page_list .= " "; 
		} 
		if (($curpage+1) <= $pages) { 
			$page_list .= "<li class=\"arrowright\"><a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage+1).$qryString."\" title=\" Next Page ".$i."\">"."<img src='images/pre_arrow_pnton.png' alt='' />"."</a></li>";	  
			//$page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage+1)."\" title=\"Next Page\"> > </a> "; 
		} 
		if (($curpage != $pages) && ($pages != 0)) { 
			//$page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$pages."\" title=\"Last Page\"></a> "; 
		} 
		$page_list .= "</td>\n"; 
		return $page_list; 
	} 
	public function nextPrev($curpage, $pages, $qryString) { 
		$next_prev  = ""; 
		if (($curpage-1) <= 0) { 
			$next_prev .= "&nbsp;Previous&nbsp;&nbsp;|"; 
		} 
		else { 
			$next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage-1).$qryString."\">Previous</a>&nbsp;&nbsp;|"; 
		} 
		/*	
		for ($i=1; $i<=$pages; $i++) {
			if ($i == $curpage) {
				$next_prev .= "<b>".$i."</b>"; 
			} 
			else { 
				$next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$i."&CatID=".$CatID."&ParentID=".$ParentID."\" title=\"Page ".$i."\">".$i."</a>";
			} 
			$next_prev .= " "; 
		} 
		//$next_prev .= " | "; 
		*/
		if (($curpage+1) > $pages) {
			$next_prev .= "&nbsp;&nbsp;Next&nbsp;&nbsp;";
		}
		else { 
			$next_prev .= "&nbsp;&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage+1).$qryString."\">Next</a>&nbsp;&nbsp;"; 
		} 
		return $next_prev; 
	} 
}
?>