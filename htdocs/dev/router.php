<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('core');

function http_basic_auth($realm)
{
    header("WWW-Authenticate: Basic realm=\"$realm\"");

    return false;
}

function die_auth_failed()
{
    http_response_code(401);
    exit;
}

function log_stderr($data)
{
    $stderr = fopen("php://stderr",'w+');

    fwrite($stderr, $data);
}

function colour($colour, $data)
{
    $prep = null;
    $end = "\e[0m";

    switch ($colour)
    {
        case 'red':
            $prep = "\e[0;31m";
            break;
        case 'blue':
            $prep = "\e[0;34m";
            break;
        case 'light_blue':
            $prep = "\e[1;34m";
            break;
        case 'cyan':
            $prep = "\e[0;36m";
            break;
        case 'green':
            $prep = "\e[0;32m";
            break;
    }

    if (! is_null($prep))
    {
        $data = "$prep$data$end";
    }

    return $data;
}

function log_http_server($uri=null, $user_agent=null, $method=null)
{
    if (is_null($uri))
    {
        $uri = $_SERVER['REQUEST_URI'];
    }

    if (is_null($user_agent))
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    }

    if (is_null($method))
    {
        $method = $_SERVER['REQUEST_METHOD'];
    }

    $data =
        strftime("[%a %b %d %Y %X %Z%z]") .
        " " .
        "\"" . colour('cyan', "$method $uri") . "\" " .
        "\"$user_agent\"" .
        "\n";

    log_stderr($data);
}

function log_php($uri=null, $ip=null, $port=null)
{
    if (is_null($uri))
    {
        $uri = $_SERVER['REQUEST_URI'];
    }

    if (is_null($ip))
    {
        $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
    }

    if (is_null($port))
    {
        $port = $_SERVER['REMOTE_PORT'];
    }

    $data =
        strftime("[%a %b %d %X %Y]") . " " .
        colour('green', "$ip:$port $uri") . "\n";

    log_stderr($data);
}

// Log request to console
log_php();

$USERNAME = $CFG->site->auth->username;
$PASSWORD = $CFG->site->auth->password;
$REALM = $CFG->site->auth->realm;

$allow_retries = $CFG->site->auth->retries;
$auth_failed = false;

$username = $_SERVER['PHP_AUTH_USER'] ?? null;
$password = $_SERVER['PHP_AUTH_PW'] ?? null;

if (is_null($username) || is_null($password))
{
    $auth_failed = ! http_basic_auth($REALM);
}
else if ($username != $USERNAME || $password != $PASSWORD)
{
    if ($allow_retries)
    {
        $auth_failed = ! http_basic_auth($REALM);
    }
    else
    {
        $auth_failed = true;
    }
}

if ($auth_failed)
{
    die_auth_failed();
}

$mod_url = $_SERVER['SCRIPT_NAME'];
$mod_query = $_SERVER['QUERY_STRING'] ?? null;
$redirect = false;

if (preg_match("/^(\/?\.)/", $_SERVER['SCRIPT_NAME'], $matches))
{
    die_bad_request();
}
else if (preg_match("/^(\/pla\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/phpliteadmin/phpliteadmin.php';
    $redirect = true;
}
else if (preg_match("/^(\/)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/recent/index.php';
    $mod_query = 'page=1';
}
else if (preg_match("/^(\/page\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/recent/index.php';
    $mod_query = 'page=1';
}
else if (preg_match("/^(\/page\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/recent/index.php';
    $mod_query = 'page=' . $matches[2];
}
else if (preg_match("/^(\/c(ategorie)?s\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/categories/index.php';
    $mod_query = 'page=1';
}
else if (preg_match("/^(\/shuffle\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/shuffle/index.php';
    $mod_query = 'page=1';
}
else if (preg_match("/^(\/s(earch)?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/search/index.php';
    $mod_query = 'page=1';

    if (array_key_exists('QUERY_STRING', $_SERVER) && preg_match("/search=(.*)/", $_SERVER['QUERY_STRING'], $matches_query))
    {
        $mod_query .= '&search=' . urldecode($matches_query[1]);
    }

    // if (preg_match("/search=(.*)/", $_SERVER['SCRIPT_NAME'], $matches_query))
    // {
    //     $mod_query .= '&search=' . $matches_query[1];
    // }

    // echo $_SERVER['SCRIPT_NAME'];
    // echo "<br>";
    // echo $_SERVER['QUERY_STRING'];
    // echo "<br>";
    // echo $mod_query;
    // die();
}
else if (preg_match("/^(\/s(earch)?\/([^\/]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/search/index.php';
    $mod_query = 'page=1&search=' . $matches[3];
}
else if (preg_match("/^(\/s(earch)?\/([^\/]+)\/p(age)?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/search/index.php';
    $mod_query = 'page=1&search=' . $matches[3];
}
else if (preg_match("/^(\/s(earch)?\/([^\/]+)\/p(age)?\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/search/index.php';
    $mod_query = 'page=' . $matches[5] . '&search=' . $matches[3];
}
else if (preg_match("/^(\/a(rchives)?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/archives/years.php';
    $mod_query = 'page=1';
}
else if (preg_match("/^(\/a(rchives)?\/([^\/]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/archives/months.php';
    $mod_query = 'page=1&year=' . $matches[3];
}
else if (preg_match("/^(\/a(rchives)?\/([^\/]+)\/p(age)?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/archives/months.php';
    $mod_query = 'page=1&year=' . $matches[3];
}
else if (preg_match("/^(\/a(rchives)?\/([^\/]+)\/p(age)?\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/archives/months.php';
    $mod_query = 'page=' . $matches[5] . '&year=' . $matches[3];
}
else if (preg_match("/^(\/a(rchives)?\/([^\/]+)\/([^\/]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/archives/days.php';
    $mod_query = 'page=1&year=' . $matches[3] . '&month=' . $matches[4];
}
else if (preg_match("/^(\/a(rchives)?\/([^\/]+)\/([^\/]+)\/p(age)?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/archives/days.php';
    $mod_query = 'page=1&year=' . $matches[3] . '&month=' . $matches[4];
}
else if (preg_match("/^(\/a(rchives)?\/([^\/]+)\/([^\/]+)\/p(age)?\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/archives/days.php';
    $mod_query = 'page=' . $matches[6] . '&year=' . $matches[3] . '&month=' . $matches[4];
}
else if (preg_match("/^(\/a(rchives)?\/([^\/]+)\/([^\/]+)\/([^\/]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/archives/index.php';
    $mod_query = 'page=1&year=' . $matches[3] . '&month=' . $matches[4] . '&day=' . $matches[5];
}
else if (preg_match("/^(\/a(rchives)?\/([^\/]+)\/([^\/]+)\/([^\/]+)\/p(age)?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/archives/index.php';
    $mod_query = 'page=1&year=' . $matches[3] . '&month=' . $matches[4] . '&day=' . $matches[5];
}
else if (preg_match("/^(\/a(rchives)?\/([^\/]+)\/([^\/]+)\/([^\/]+)\/p(age)?\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/archives/index.php';
    $mod_query = 'page=' . $matches[7] . '&year=' . $matches[3] . '&month=' . $matches[4] . '&day=' . $matches[5];
}
else if (preg_match("/^(\/r(ecent)?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/recent/index.php';
    $mod_query = 'page=1';
}
else if (preg_match("/^(\/r(ecent)?\/p(age)?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/recent/index.php';
    $mod_query = 'page=1';
}
else if (preg_match("/^(\/r(ecent)?\/p(age)?\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/recent/index.php';
    $mod_query = 'page=' . $matches[4];
}
else if (preg_match("/^(\/producers(\/([a-zA-Z0-9\.]))?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/producers/index.php';
    $mod_query = 'page=1';
    if (sizeof($matches) > 2)
    {
        $mod_query .= "&producers=" . $matches[3];
    }
}
else if (preg_match("/^(\/producers(\/([a-zA-Z0-9\.]))?\/page\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/producers/index.php';
    $mod_query = 'page=1';
    if (sizeof($matches) > 2)
    {
        $mod_query .= "&producers=" . $matches[3];
    }
}
else if (preg_match("/^(\/producers(\/([a-zA-Z0-9\.]))?\/page?\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/producers/index.php';
    if (sizeof($matches) > 3)
    {
        $mod_query = "page=" . $matches[4];
    }
    if (sizeof($matches) > 2)
    {
        $mod_query .= "&producers=" . $matches[3];
    }
}
else if (preg_match("/^(\/producer\/([^\/]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/producer/index.php';
    $mod_query = 'page=1&producer=' . $matches[2];
}
else if (preg_match("/^(\/producer\/([^\/]+)\/p(age)?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/producer/index.php';
    $mod_query = 'page=1&producer=' . $matches[2];
}
else if (preg_match("/^(\/producer\/([^\/]+)\/p(age)?\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/producer/index.php';
    $mod_query = 'page=' . $matches[4] . '&producer=' . $matches[2];
}
else if (preg_match("/^(\/f(ile)?\/([0-9]+)\/([^\/]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/file/index.php';
    $mod_query = 'post=' . $matches[3] . '&file=' . $matches[4];
}
else if (preg_match("/^(\/p(ost)?\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/post/index.php';
    $mod_query = 'page=1&post=' . $matches[3];
}
else if (preg_match("/^(\/c(ategory)?\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/category/index.php';
    $mod_query = 'page=1&category=' . $matches[3];
}
else if (preg_match("/^(\/c(ategory)?\/?([0-9]+)\/p(age)?\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/category/index.php';
    $mod_query = 'page=1&category=' . $matches[3];
}
else if (preg_match("/^(\/c(ategory)?\/?([0-9]+)\/p(age)?\/?([0-9]+)\/?)$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = '/mod/category/index.php';
    $mod_query = 'page=' . $matches[5] . '&category=' . $matches[3];
}
else if (preg_match("/(\/([^\/\.]+))$/", $_SERVER['SCRIPT_NAME'], $matches))
{
    $mod_url = $_SERVER['SCRIPT_NAME'] . '.php';
}

$mod_rewrite = $mod_url;

if ($mod_query)
{
    $mod_rewrite .= "?$mod_query";
}

$_SERVER['REQUEST_URI'] = $mod_rewrite;
$_SERVER['SCRIPT_NAME'] = $mod_url;
$_SERVER['QUERY_STRING'] = $mod_query;

parse_str($mod_query, $_GET);

$mod_path = $_SERVER['DOCUMENT_ROOT'] . $mod_url;

$path_parts = pathinfo($mod_path);
$extension = $path_parts['extension'];

$content_type = null;

if ($extension == 'css')
{
    $content_type = 'text/css';
}
else if ($extension == 'ico')
{
    $content_type = 'image/x-icon';
}
else if ($extension == 'js')
{
    $content_type = 'text/javascript';
}

if ($redirect)
{
    header('Location: ' . $mod_rewrite);

    die();
}

if (is_file($mod_path) && file_exists($mod_path))
{
    if ($content_type)
    {
        header("Content-type: $content_type");
    }

    require_once $mod_path;
}
else
{
    return false;
}

?>
