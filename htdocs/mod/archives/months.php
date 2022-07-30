<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = 1;
$param_year = param_int_required('year');

$page_name = $CFG->html->elements->nav->archives->text;
$page_dir = $CFG->html->elements->nav->archives->link;

$title = "$page_name: $param_year";
$page_title =
    "<a href=\"$page_dir\">$page_name</a>: " .
    "<a href=\"$page_dir$param_year\">$param_year</a>";

$page = new Page();

$page->set_title($title);
$page->set_nav($page_name);
$page->set_page_info($page_title);

$months = get_archive_months($param_year);

if (! $months)
{
    die_bad_request();
}

foreach ($months as $month)
{
    $month = (int)$month;

    $post = get_latest_archived_post($param_year, $month);

    $post_title = $post['title'];

    $title = pretty_month($month);
    $url = "$page_dir$param_year/$month";

    $page_post = new PostCategory($title, $url, $post);

    //$page_post->set_description($post_title);

    $page->add_post($page_post);
}

echo $page->dump();

?>
