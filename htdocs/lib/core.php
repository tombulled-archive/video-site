<?php

function startswith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endswith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function pretty_day($day)
{
    $str_day = (string)$day;

    if (endswith($str_day, '1') and $str_day != '11')
    {
        $extension = 'st';
    }
    else if (endswith($str_day, '2') and $str_day != '12')
    {
        $extension = 'nd';
    }
    else if (endswith($str_day, '3') and $str_day != '13')
    {
        $extension = 'rd';
    }
    else
    {
        $extension = 'th';
    }

    $pretty_day = "$str_day$extension";

    return $pretty_day;
}

function pretty_month($month)
{
    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

    $pretty_month = $months[$month - 1];

    return $pretty_month;
}

function pretty_date($year, $month, $day)
{
    $pretty_month = pretty_month($month);
    $pretty_day = str_pad((string)$day, 2, '0', STR_PAD_LEFT);
    $pretty_year = str_pad((string)$year, 4, '0', STR_PAD_LEFT);

    $pretty_date = "$pretty_month $day, $year";

    return $pretty_date;
}

function pretty_size($bytes)
{
    $kb = $bytes / 1024;
    $mb = $kb / 1014;
    $gb = $mb / 1024;

    $kb_intval = intval($kb);
    $mb_intval = intval($mb);
    $gb_intval = intval($gb);

    $kb_2dp = round($kb, 2);
    $mb_2dp = round($mb, 2);
    $gb_2dp = round($gb, 2);

    if ($gb_intval >= 1)
    {
        $pretty_size = "$gb_2dp GB";
    }
    else if ($mb_intval >= 1)
    {
        $pretty_size = "$mb_2dp MB";
    }
    else
    {
        $pretty_size = "$kb_2dp KB";
    }

    return $pretty_size;
}

function pretty_duration($seconds)
{
    $seconds = intval($seconds);

    $minutes = $seconds / 60;
    $minutes_intval = intval($minutes);

    $seconds = $seconds - ($minutes_intval * 60);

    $pretty_minutes = str_pad((string)$minutes_intval, 2, '0', STR_PAD_LEFT);
    $pretty_seconds = str_pad((string)$seconds, 2, '0', STR_PAD_LEFT);

    $pretty_duration = "$minutes_intval:$pretty_seconds";

    return $pretty_duration;
}

function get_param_page()
{
    global $CFG;

    $param_page = $_GET[$CFG->params->page] ?? 1;

    if (ctype_digit($param_page))
    {
        $param_page = (int)$param_page;
    }
    else
    {
        $param_page = 1;
    }

    return $param_page;
}

function param_int_required($key)
{
    $param = $_GET[$key] ?? null;

    if (! is_null($param) && ctype_digit($param))
    {
        $param = (int)$param;
    }
    else
    {
        die_bad_request();
    }

    return $param;
}

function param_ascii_required($key, $allow='')
{
    $param = $_GET[$key] ?? null;

    if (is_null($param) || preg_match("/[^A-Za-z0-9$allow]/", $param))
    {
        die_bad_request();
    }

    return $param;
}

function param_all_ascii_required($key, $allow='')
{
    $param = $_GET[$key] ?? null;

    if (is_null($param) || preg_match("/[^ -~$allow]/", $param))
    {
        die_bad_request();
    }

    return $param;
}

function param_ascii_char($key, $allow='')
{
    $param = $_GET[$key] ?? null;

    if (is_null($param) || strlen($param) != 1 || preg_match("/[^A-Za-z0-9$allow]/", $param))
    {
        return;
    }

    return $param;
}

function media_type($extension)
{
    $types = array
    (
        'jpg' => 'image/jpg',
        'gif' => 'image/gif',
        'mp4' => 'video/mp4',
        'flv' => 'video/x-flv',
        'png' => 'image/png',
        'mov' => 'image/mov',
    );

    return $types[$extension];
}

function die_bad_request()
{
    //http_response_code(400);
    header('Location: /');

    die();
}

?>
