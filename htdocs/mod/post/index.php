<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = 1;
$param_post = param_int_required('post');

$page_name = $CFG->html->elements->nav->post->text;
$page_dir = $CFG->html->elements->nav->post->link;

$title = "$page_name: $param_post";

$page = new Page();

$page->set_title($title);
$page->set_nav($CFG->html->elements->nav->home->text);

$post = get_post($param_post);

if (! $post)
{
    die_bad_request();
}

$page_post = new Post($post, $individual=true);

$page->add_post($page_post);

echo $page->dump();

?>
