<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');

$local_filename = param_ascii_required('file', "\.");
$post_id = param_int_required('post');

if (substr_count($local_filename, '.') != 1)
{
    die_bad_request();
}

$exploded_local_filename = explode('.', $local_filename);
$filename = $exploded_local_filename[0];
$extension = $exploded_local_filename[1];

$whitelisted_filenames = array('poster', 'advert', 'video', 'image');
$whitelisted_extensions = array('jpg', 'gif', 'mp4', 'flv', 'png', 'mov', 'wmv');

if (! in_array($filename, $whitelisted_filenames) || ! in_array($extension, $whitelisted_extensions))
{
    die_bad_request();
}

$files_path = $CFG->dir->dlips;

$path = "$files_path$post_id/$filename.$extension";

// Reference: http://www.tuxxin.com/php-mp4-streaming/

$file = @fopen($path, 'rb');

$content_type = 'video/mp4';

$size   = filesize($path); // File size
$length = $size;           // Content length
$start  = 0;               // Start byte
$end    = $size - 1;       // End byte

header('Content-type: $content_type');
header("Accept-Ranges: bytes");

if (isset($_SERVER['HTTP_RANGE']))
{
    $c_start = $start;
    $c_end   = $end;

    list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);

    if (strpos($range, ',') !== false)
    {
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        header("Content-Range: bytes $start-$end/$size");

        exit;
    }

    if ($range == '-')
    {
        $c_start = $size - substr($range, 1);
    }
    else
    {
        $range   = explode('-', $range);
        $c_start = $range[0];
        $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
    }

    $c_end = ($c_end > $end) ? $end : $c_end;

    if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size)
    {
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        header("Content-Range: bytes $start-$end/$size");

        exit;
    }

    $start  = $c_start;
    $end    = $c_end;
    $length = $end - $start + 1;

    fseek($file, $start);
    header('HTTP/1.1 206 Partial Content');
}

header("Content-Range: bytes $start-$end/$size");
header("Content-Length: ".$length);

$buffer = 1024 * 8;

while(!feof($file) && ($p = ftell($file)) <= $end)
{
    if ($p + $buffer > $end)
    {
        $buffer = $end - $p + 1;
    }

    set_time_limit(0);

    echo fread($file, $buffer);
    flush();
}

fclose($file);

exit();
?>
