<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = get_param_page();

$page_name = $CFG->html->elements->nav->shuffle->text;
$page_dir = $CFG->html->elements->nav->shuffle->link;

$title = $page_name;

$page = new Page();

$page->set_title($title);
$page->set_nav($page_name);
$page->set_load_more($page_dir);

$posts = get_random_posts();

foreach ($posts as $post)
{
    $page_post = new Post($post);

    $page->add_post($page_post);
}

echo $page->dump();

?>
