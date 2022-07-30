<?php
if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');
require_once import('page');

$param_page = get_param_page();
$param_producers = param_ascii_char('producers');

$page_name = $CFG->html->elements->nav->producers->text;
$page_dir = $CFG->html->elements->nav->producers->link;

$page_singular_name = $CFG->html->elements->nav->producer->text;
$page_singular_dir = $CFG->html->elements->nav->producer->link;

$title = $page_name;

if (is_null($param_producers))
{
    $url_base = $page_dir;
    $url_page = $url_base . "page/";

    $producers = get_producers($param_page);

    $total_pages = get_pages_producers();
}
else
{
    $url_base = "$page_dir$param_producers/";
    $url_page = $url_base . "page/";

    $producers = get_producers($param_page, $param_producers);

    $total_pages = get_pages_producers($param_producers);
}

$page = new Page();

$page->set_title($title);
$page->set_nav($page_name);
$page->set_load_more($url_page . ($param_page + 1));
$page->set_pagination($url_page, $param_page, $total_pages);

$letters = str_split('abcdefghijklmnopqrstuvwxyz');

$page->additonal_head .= indent(2, "<style>nav.pagination-load-more {padding-top: 40px;}</style>\n");
$page->body_top .= indent(2, "<div class=\"selector-container\">\n");
foreach ($letters as $letter)
{
    $url_letter = $page_dir . $letter;
    $active = "";

    if ($param_producers and $param_producers == $letter)
    {
        $active = " active";
    }

    $page->body_top .= indent(3, "<a class=\"selector-selection" . $active . "\" href=\"$url_letter\">$letter</a>\n"); // use active class, also use hover colors
}
$page->body_top .= indent(2, "</div>\n");

foreach ($producers as $producer)
{
    $producer_name = $producer['name'];
    $producer_description = $producer['description'];
    $producer_id = $producer['id'];

    $post = get_latest_producer_post($producer_id);

    if (is_null($post) || is_null($post['id']))
    {
        continue;
    }

    $post_title = $post['title'];

    $title = $producer_name;
    $url = "$page_singular_dir$producer_id";

    $page_post = new PostCategory($title, $url, $post);

    //$page_post->set_description($post_title);

    $page->add_post($page_post);
}

echo $page->dump();

?>
