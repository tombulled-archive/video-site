<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = get_param_page();

$page_name = $CFG->html->elements->nav->home->text;
$page_dir = $CFG->html->elements->nav->home->link;

$url_page = "/page/";

$total_pages = get_pages_posts();

$page = new Page();

$page->set_title('Recent Posts');
$page->set_nav($page_name);
$page->set_load_more($url_page . ($param_page + 1));
$page->set_pagination($url_page, $param_page, $total_pages);

$posts = get_most_recent_posts($param_page);

foreach ($posts as $post)
{
    $page_post = new Post($post);

    $page->add_post($page_post);
}

echo $page->dump();

?>
