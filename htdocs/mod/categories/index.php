<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = 1;

$page_name = $CFG->html->elements->nav->categories->text;
$page_dir = $CFG->html->elements->nav->categories->link;

$page_singular_name = $CFG->html->elements->nav->category->text;
$page_singular_dir = $CFG->html->elements->nav->category->link;

$title = $page_name;

$page = new Page();

$page->set_title($title);
$page->set_nav($page_name);

$categories = get_all_categories();

foreach ($categories as $category)
{
    $category_name = $category['name'];
    $category_description = $category['description'];
    $category_id = $category['id'];

    if ($category_name == 'Admin' || $category_name == 'Uncategorized')
    {
        continue;
    }

    $post = get_most_recent_category_post($category_id);

    if (is_null($post['id']))
    {
        continue;
    }

    $post_title = $post['title'];

    $title = $category_name;
    $url = "$page_singular_dir$category_id";

    $page_post = new PostCategory($title, $url, $post);

    //$page_post->set_description($post_title);
    //$page_post->set_description($category_description);

    $page->add_post($page_post);
}

echo $page->dump();

?>
