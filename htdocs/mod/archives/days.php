<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = 1;
$param_year = param_int_required('year');
$param_month = param_int_required('month');

$pretty_month = pretty_month($param_month);
$pretty_date = "$pretty_month, $param_year";

$page_name = $CFG->html->elements->nav->archives->text;
$page_dir = $CFG->html->elements->nav->archives->link;

$title = "$page_name: $pretty_month, $param_year";
$page_title =
    "<a href=\"$page_dir\">$page_name</a>: " .
    "<a href=\"$page_dir$param_year/$param_month\">$pretty_month</a>, " .
    "<a href=\"$page_dir$param_year\">$param_year</a>";

$page = new Page();

$page->set_title($title);
$page->set_nav($page_name);
$page->set_page_info($page_title);

$days = get_archive_days($param_year, $param_month);

if (! $days)
{
    die_bad_request();
}

foreach ($days as $day)
{
    $day = (int)$day;

    $post = get_latest_archived_post($param_year, $param_month, $day);

    $post_title = $post['title'];

    $title = pretty_day($day);
    $url = "$page_dir$param_year/$param_month/$day";

    $page_post = new PostCategory($title, $url, $post);

    //$page_post->set_description($post_title);

    $page->add_post($page_post);
}

echo $page->dump();

?>
