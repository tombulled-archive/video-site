<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = get_param_page();
$param_search = param_all_ascii_required('search');

$page_name = $CFG->html->elements->nav->search->text;
$page_dir = $CFG->html->elements->nav->search->link;

$title = "$page_name: $param_search";
$url_page = "$page_dir$param_search/page/";

$total_pages = get_pages_search($param_search);

$page = new Page();

$page->set_title($title);
$page->set_nav($CFG->html->elements->nav->home->text);
$page->set_load_more($url_page . ($param_page + 1));
$page->set_pagination($url_page, $param_page, $total_pages);
$page->set_page_info($title);

$posts = search_posts($param_search, $param_page);

foreach ($posts as $post)
{
    $page_post = new Post($post);

    $page->add_post($page_post);
}

echo $page->dump();

?>
