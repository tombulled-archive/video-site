<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = get_param_page();
$param_producer = param_int_required('producer');

$page_parent_name = $CFG->html->elements->nav->producers->text;
$page_parent_dir = $CFG->html->elements->nav->producers->link;

$page_name = $CFG->html->elements->nav->producer->text;
$page_dir = $CFG->html->elements->nav->producer->link;

$producer_id = $param_producer;

$producer = get_producer($producer_id);

if (! $producer)
{
    die_bad_request();
}

$producer_name = $producer['name'];
$producer_description = $producer['description'];

$total_pages = get_pages_producer($producer_id);

$page = new Page();

$title = "$page_name: $producer_name";
$url_page = "$page_dir$param_producer/page/";

$page->set_title($title);
$page->set_nav($page_parent_name);
$page->set_page_info($title, $producer_description);
$page->set_load_more($url_page . ($param_page + 1));
$page->set_pagination($url_page, $param_page, $total_pages);

$posts = get_producer_posts($producer_id, $param_page);

foreach ($posts as $post)
{
    $page_post = new Post($post);

    $page->add_post($page_post);
}

echo $page->dump();

?>
