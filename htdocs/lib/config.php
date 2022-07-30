<?php

$server_addr = $_SERVER['SERVER_ADDR'] ?? $_SERVER['HTTP_HOST'] ?? gethostname();
$document_root = $_SERVER["DOCUMENT_ROOT"];

$site_root = dirname($_SERVER["DOCUMENT_ROOT"]);
$server_root = dirname(dirname(dirname($_SERVER["DOCUMENT_ROOT"])));

global $CFG;

$CFG = new stdClass();
$CFG->site = new stdClass();
$CFG->server = new stdClass();
$CFG->site->auth = new stdClass();
$CFG->phpliteadmin = new stdClass();
$CFG->html = new stdClass();
$CFG->dir = new stdClass();
$CFG->params = new stdClass();
$CFG->logs = new stdClass();
$CFG->databases = new stdClass();
$CFG->html->meta = new stdClass();
$CFG->html->behaviour = new stdClass();
$CFG->html->behaviour->nav = new stdClass();
$CFG->html->behaviour->sub_menu = new stdClass();
$CFG->html->assets = new stdClass();
$CFG->html->assets->fonts = new stdClass();
$CFG->html->assets->packages = new stdClass();
$CFG->html->assets->packages->video_js = new stdClass();
$CFG->html->assets->css = new stdClass();
$CFG->html->elements = new stdClass();
$CFG->html->elements->search = new stdClass();
$CFG->html->elements->post = new stdClass();
$CFG->html->elements->post->meta = new stdClass();
$CFG->html->elements->post->meta->categories = new stdClass();
$CFG->html->elements->nav = new stdClass();
$CFG->html->elements->nav->archives = new stdClass();
$CFG->html->elements->nav->categories = new stdClass();
$CFG->html->elements->nav->category = new stdClass();
$CFG->html->elements->nav->post = new stdClass();
$CFG->html->elements->nav->producer = new stdClass();
$CFG->html->elements->nav->producers = new stdClass();
$CFG->html->elements->nav->home = new stdClass();
$CFG->html->elements->nav->search = new stdClass();
$CFG->html->elements->nav->shuffle = new stdClass();
$CFG->html->elements->pagination = new stdClass();
$CFG->html->elements->pagination->load_more = new stdClass();

$CFG->server->apache = false;

$CFG->phpliteadmin->password = 'password';
$CFG->phpliteadmin->directory = "$site_root/db/";

$CFG->logs->php_error_log = $_SERVER["DOCUMENT_ROOT"] . "/../logs/php_error_log.log";

$CFG->site->auth->username = 'admin';
$CFG->site->auth->password = 'password';
$CFG->site->auth->realm = 'Login';
$CFG->site->auth->retries = true;

$CFG->dir->dlips = "$site_root/files/";

$CFG->databases->core = "$site_root/db/core.db";

$CFG->site->title = 'Site Title';
$CFG->site->description = 'Site Description';
$CFG->site->page_size = 12;
$CFG->site->footer = "Site Footer";
$CFG->site->disclaimer_text = 'DISCLAIMER    |    CONTACT US';
$CFG->site->disclaimer_url = '/disclaimer.php';

$CFG->html->behaviour->nav->sticky = false;
$CFG->html->behaviour->sub_menu->visible = true;

$CFG->html->lang = 'en-US';
$CFG->html->title = $CFG->site->title . " - " . $CFG->site->description;

$CFG->html->meta->charset = 'UTF-8';
$CFG->html->meta->viewport = 'width=device-width, initial-scale=1';
$CFG->html->meta->description = '';
$CFG->html->meta->keywords = '';

$CFG->html->assets->fonts->arimo = '/assets/fonts/arimo/arimo.css';
$CFG->html->assets->fonts->dashicons = '/assets/fonts/dashicons/icon-font/css/dashicons.css';
$CFG->html->assets->fonts->fontawesome = '/assets/fonts/fontawesome/css/all.css';

$CFG->html->assets->packages->video_js->js_min = '/assets/packages/video-js/video-js.min.js';
$CFG->html->assets->packages->video_js->css_custom = '/assets/packages/video-js/video-js-custom.css';
$CFG->html->assets->packages->video_js->css_new = '/assets/packages/video-js/video-js-new.css';
$CFG->html->assets->packages->video_js->css_min = '/assets/packages/video-js/video-js.min.css';
$CFG->html->assets->packages->video_js->js_flv_min = '/assets/packages/video-js/flv.min.js';
$CFG->html->assets->packages->video_js->js_flvjs_min = '/assets/packages/video-js/flv-js.min.js';

$CFG->html->assets->css->main = '/assets/css/main.css.php';

$CFG->html->elements->nav->archives->text = 'Archives';
$CFG->html->elements->nav->archives->link = '/archives/';

$CFG->html->elements->nav->home->text = 'Home';
$CFG->html->elements->nav->home->link = '/';

$CFG->html->elements->nav->categories->text = 'Categories';
$CFG->html->elements->nav->categories->link = '/categories/';

$CFG->html->elements->nav->category->text = 'Category';
$CFG->html->elements->nav->category->link = '/category/';

$CFG->html->elements->nav->search->text = 'Search';
$CFG->html->elements->nav->search->link = '/search/';

$CFG->html->elements->nav->shuffle->text = 'Shuffle';
$CFG->html->elements->nav->shuffle->link = '/shuffle/';

$CFG->html->elements->nav->post->text = 'Post';
$CFG->html->elements->nav->post->link = '/post/';

$CFG->html->elements->nav->producer->text = 'Producer';
$CFG->html->elements->nav->producer->link = '/producer/';

$CFG->html->elements->nav->producers->text = 'Producers';
$CFG->html->elements->nav->producers->link = '/producers/';

$CFG->html->elements->search->action = '/search/';
$CFG->html->elements->search->placeholder = 'Search..';

$CFG->html->elements->post->meta->separator = '/';

$CFG->html->elements->post->meta->categories->separator = ',';

$CFG->html->elements->pagination->text = 'PAGES';

$CFG->html->elements->pagination->load_more->text = 'Load more';

$CFG->params->page = 'page';

// Log errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Set location
setlocale(LC_ALL, "UK");

?>
