<?php
/**************************************************************************************
 * Class: Pager
 * Author: Tsigo <tsigo@tsiris.com>
 * Methods:
 *         findStart
 *         findPages
 *         pageList
 *         nextPrev
 * Redistribute as you see fit.
 **************************************************************************************/
class Pager1 {
    /***********************************************************************************
     * int findStart (int limit)
     * Returns the start offset based on $_GET['page'] and $limit
     ***********************************************************************************/
    function findStart($limit) {
        if ((!isset($_GET['page'])) || ($_GET['page'] == "1")) {
            $start = 0;
            $_GET['page'] = 1;
        }
        else {
            $start = ($_GET['page']-1) * $limit;
        }
        return $start;
    }
    /***********************************************************************************
     * int findPages (int count, int limit)
     * Returns the number of pages needed based on a count and a limit
     ***********************************************************************************/
    function findPages($count, $limit) {
        $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
        return $pages;
    }
    /***********************************************************************************
     * string pageList (int curpage, int pages)
     * Returns a list of pages in the format of " < [pages] > "
     ***********************************************************************************/
    function pageList($curpage, $pages, $qryString) {
        $page_list  = "";

        /* Print the first and previous page links if necessary */
        if (($curpage != 1) && ($curpage)) {
            $page_list .= " <li><a href=\"".$_SERVER['PHP_SELF']. "?page=1".$qryString."\" title=\"First Page\" class=\"numbr selected\"><<</a></li>";
        }

        if (($curpage-1) > 0) {
            $page_list .= "<li><a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage-1).$qryString."\" title=\" Previous Page ".($curpage-1)."\">". "<i class=\"fa fa-caret-left\"></i> Previous"."</a></li>";
            //$page_list .= " <a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage-1)."\" title=\"Previous Page\"> < </a> ";
        }

        /* Print the numeric page list; make the current page unlinked and bold */
        $showPages = $curpage+9;
        $startShow = 1;
        if($curpage > 2){
            $startShow = $curpage-2;
            $page_list .= "<li><a href=\"#\">...</a></li>";
        }
        //for ($i=1; $i<=$pages; $i++){
        for ($i= $startShow; $i<= $showPages; $i++){
            if($i <= $pages){
                if ($i == $curpage) {
                    //$page_list .= "<b class=\"numbr selected\">".$i."</b>";
                    $page_list .= "<li><a href='' class='numbr selected'><b>" . $i . "</b></a></li>";
                } else {
                    $page_list .= "<li><a href=\"" . $_SERVER['PHP_SELF'] . "?page=" . $i . $qryString . "\" title=\"Page " . $i . "\">" . $i . "</a></li>";
                }
                $page_list .= " ";
            }
        }

        /* Print the Next and Last page links if necessary */
        if($showPages < $pages) {
            $page_list .= "<li><a href=\"#\">...</a></li>";
        }

        if (($curpage+1) <= $pages) {
            $page_list .= "<li class=\"arrowright\"><a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage+1).$qryString."\" title=\" Next Page ".$i."\">"."Next <i class=\"fa fa-caret-right\"></i>"."</a></li>";
            //$page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage+1)."\" title=\"Next Page\"> > </a> ";
        }

        if (($curpage != $pages) && ($pages != 0)) {
            $page_list .= "<li><a href=\"".$_SERVER['PHP_SELF']."?page=".$pages.$qryString. "\" title=\"Last Page\">>></a></li>";
        }
        //$page_list .= "</td>\n";

        return $page_list;
    }
    /***********************************************************************************
     * string nextPrev (int curpage, int pages)
     * Returns "Previous | Next" string for individual pagination (it's a word!)
     ***********************************************************************************/
    function nextPrev($curpage, $pages, $qryString) {
        $next_prev  = "";

        if (($curpage-1) <= 0)
        {
            $next_prev .= "&nbsp;Previous&nbsp;&nbsp;|";
        }
        else
        {
            $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage-1).$qryString."\">Previous</a>&nbsp;&nbsp;|";
        }
        /*
        for ($i=1; $i<=$pages; $i++)
          {
           if ($i == $curpage)
            {
             $next_prev .= "<b>".$i."</b>";
            }
           else
            {
             $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$i."&CatID=".$CatID."&ParentID=".$ParentID."\" title=\"Page ".$i."\">".$i."</a>";
            }
           $next_prev .= " ";
          }
         //$next_prev .= " | ";
        */
        if (($curpage+1) > $pages)
        {
            $next_prev .= "&nbsp;&nbsp;Next&nbsp;&nbsp;";
        }
        else
        {
            $next_prev .= "&nbsp;&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage+1).$qryString."\">Next</a>&nbsp;&nbsp;";
        }

        return $next_prev;
    }

    function nextPrevImg($curpage, $pages, $qryString) {
        $next_prev  = "";

        if (($curpage-1) <= 0)
        {
            $next_prev .= "&nbsp;<img src='images/pagination_left_arrow.jpg' alt=''>";
        }
        else
        {
            $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage-1).$qryString."\"><img src='images/pagination_left_arrow.jpg' alt=''></a>&nbsp;&nbsp;";
        }
        /*
        for ($i=1; $i<=$pages; $i++)
          {
           if ($i == $curpage)
            {
             $next_prev .= "<b>".$i."</b>";
            }
           else
            {
             $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$i."&CatID=".$CatID."&ParentID=".$ParentID."\" title=\"Page ".$i."\">".$i."</a>";
            }
           $next_prev .= " ";
          }
         //$next_prev .= " | ";
        */
        if (($curpage+1) > $pages)
        {
            $next_prev .= "&nbsp;&nbsp;<img src='images/pagination_right_arrow.jpg' alt=''>&nbsp;&nbsp;";
        }
        else
        {
            $next_prev .= "&nbsp;&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?page=".($curpage+1).$qryString."\"><img src='images/pagination_right_arrow.jpg' alt=''></a>&nbsp;&nbsp;";
        }

        return $next_prev;
    }

    function nextPrevImg_URLRW($curpage, $pages, $qryString) {
        $next_prev  = "";

        if (($curpage-1) <= 0)
        {
            $next_prev .= "&nbsp;<img src='images/pagination_left_arrow.jpg' alt=''>";
        }
        else
        {
            $next_prev .= "<a href=\"".strtok($_SERVER["REQUEST_URI"],'?')."?page=".($curpage-1).$qryString."\"><img src='images/pagination_left_arrow.jpg' alt=''></a>&nbsp;&nbsp;";
        }
        /*
        for ($i=1; $i<=$pages; $i++)
          {
           if ($i == $curpage)
            {
             $next_prev .= "<b>".$i."</b>";
            }
           else
            {
             $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=".$i."&CatID=".$CatID."&ParentID=".$ParentID."\" title=\"Page ".$i."\">".$i."</a>";
            }
           $next_prev .= " ";
          }
         //$next_prev .= " | ";
        */
        if (($curpage+1) > $pages)
        {
            $next_prev .= "&nbsp;&nbsp;<img src='images/pagination_right_arrow.jpg' alt=''>&nbsp;&nbsp;";
        }
        else
        {
            $next_prev .= "&nbsp;&nbsp;<a href=\"".strtok($_SERVER["REQUEST_URI"],'?')."?page=".($curpage+1).$qryString."\"><img src='images/pagination_right_arrow.jpg' alt=''></a>&nbsp;&nbsp;";
        }

        return $next_prev;
    }

    function nextPrevImgList_URLRW($curpage, $pages, $qryString) {
        if($curpage > 2){
            $lpStart = $curpage-2;
        }
        else{
            $lpStart = 1;
        }
        $lpEnd = $lpStart + 4;
        if($lpEnd > $pages){
            $lpEnd = $pages;
        }
        $next_prev  = "";
        if (($curpage-1) <= 0) {
            $next_prev .= '<a href="javascript: void(0);">&lt;&lt;</a>';
        }
        else {
            $next_prev .= '<a href="'.strtok($_SERVER["REQUEST_URI"],"?").'?page=1'.$qryString.'"> &lt;&lt; </a>';
        }

        if (($curpage-1) <= 0) {
            //$next_prev .= "&nbsp;<img src='images/pagination_left_arrow.jpg' alt=''>";
            $next_prev .= '<a href="javascript: void(0);">&lt;</a>';
        }
        else {
            //$next_prev .= "<a href=\"".strtok($_SERVER["REQUEST_URI"],'?')."?page=".($curpage-1).$qryString."\"><img src='images/pagination_left_arrow.jpg' alt=''></a>&nbsp;&nbsp;";
            $next_prev .= '<a href="'.strtok($_SERVER["REQUEST_URI"],"?").'?page='.($curpage-1).$qryString.'"> &lt; </a>';
        }

        for ($i=$lpStart; $i<=$lpEnd; $i++) {
            if ($i == $curpage) {
                $next_prev .= '<a href="javascript: void(0);" class="active">'.$i.'</a>';
            }
            else {
                $next_prev .= '<a href="'.strtok($_SERVER["REQUEST_URI"],"?").'?page='.($i).$qryString.'" title="Page '.$i.'">'.$i.'</a>';
            }
            //$next_prev .= " ";
        }
        //$next_prev .= " | ";

        if (($curpage+1) > $pages) {
            //$next_prev .= "&nbsp;&nbsp;<img src='images/pagination_right_arrow.jpg' alt=''>&nbsp;&nbsp;";
            $next_prev .= '<a href="javascript: void(0);">&gt;</a>';
        }
        else {
            //$next_prev .= "&nbsp;&nbsp;<a href=\"".strtok($_SERVER["REQUEST_URI"],'?')."?page=".($curpage+1).$qryString."\"><img src='images/pagination_right_arrow.jpg' alt=''></a>&nbsp;&nbsp;";
            $next_prev .= '<a href="'.strtok($_SERVER["REQUEST_URI"],"?").'?page='.($curpage+1).$qryString.'"> &gt; </a>';
        }

        if ($curpage == $pages) {
            $next_prev .= '<a href="javascript: void(0);">&gt;&gt;</a>';
        }
        else {
            $next_prev .= '<a href="'.strtok($_SERVER["REQUEST_URI"],"?").'?page='.($pages).$qryString.'"> &gt;&gt; </a>';
        }

        return $next_prev;
    }

}
?> 