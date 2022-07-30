<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = get_param_page();
$param_category = param_int_required('category');

$page_parent_name = $CFG->html->elements->nav->categories->text;
$page_parent_dir = $CFG->html->elements->nav->categories->link;

$page_name = $CFG->html->elements->nav->category->text;
$page_dir = $CFG->html->elements->nav->category->link;

$category_id = $param_category;

$category = get_category($category_id);

if (! $category)
{
    die_bad_request();
}

$category_name = $category['name'];
$category_description = $category['description'];

$title= "$page_name: $category_name";
$url_page = "$page_dir$param_category/page/";

$total_pages = get_pages_category($category_id);

$page = new Page();

$page->set_title($title);
$page->set_nav($page_parent_name);
$page->set_page_info($title, $category_description);
$page->set_load_more($url_page . ($param_page + 1));
$page->set_pagination($url_page, $param_page, $total_pages);

$posts = get_category_posts($category_id, $param_page);

foreach ($posts as $post)
{
    $page_post = new Post($post);

    $page->add_post($page_post);
}

echo $page->dump();

?>
