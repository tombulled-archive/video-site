<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = 1;

$page_name = $CFG->html->elements->nav->archives->text;
$page_dir = $CFG->html->elements->nav->archives->link;

$title = $page_name;

$page = new Page();

$page->set_title($title);
$page->set_nav($page_name);
$page->set_page_info($title);

$years = get_archive_years();

foreach ($years as $year)
{
    $post = get_latest_archived_post($year);

    $post_title = $post['title'];

    $title = $year;
    $url = "$page_dir$year";

    $page_post = new PostCategory($title, $url, $post);

    //$page_post->set_description($post_title);

    $page->add_post($page_post);
}

echo $page->dump();

?>
