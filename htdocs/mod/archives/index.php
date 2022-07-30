<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = get_param_page();
$param_year = param_int_required('year');
$param_month = param_int_required('month');
$param_day = param_int_required('day');

$pretty_month = pretty_month($param_month);
$pretty_date = "$param_day $pretty_month, $param_year";

$page_name = $CFG->html->elements->nav->archives->text;
$page_dir = $CFG->html->elements->nav->archives->link;

$url_page = "$page_dir$param_year/$param_month/$param_day/page/";

$title = "$page_name: $param_day $pretty_month, $param_year";
$page_title =
    "<a href=\"$page_dir\">$page_name</a>: " .
    "<a href=\"$page_dir$param_year/$param_month/$param_day\">$param_day</a> " .
    "<a href=\"$page_dir$param_year/$param_month\">$pretty_month</a>, " .
    "<a href=\"$page_dir$param_year\">$param_year</a>";

$total_pages = get_pages_archives($param_year, $param_month, $param_day);

$page = new Page();

$page->set_title($title);
$page->set_nav($page_name);
$page->set_page_info($page_title);
$page->set_load_more($url_page . ($param_page + 1));
$page->set_pagination($url_page, $param_page, $total_pages);

$posts = get_archived_posts($param_page, $param_year, $param_month, $param_day);

foreach ($posts as $post)
{
    $page_post = new Post($post);

    $page->add_post($page_post);
}

echo $page->dump();

?>
