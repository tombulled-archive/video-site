<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');

class Database extends SQLite3
{
    function __construct($db_file)
    {
        $this->open($db_file);
    }

    function get_error()
    {
        return $this->lastErrorMsg();
    }

    function query($sql)
    {
        $records = parent::query($sql);

        while($record = $records->fetchArray(SQLITE3_ASSOC))
        {
            yield $record;
        }
    }

    function query_one($sql)
    {
        $records = parent::query($sql);

        return $records->fetchArray(SQLITE3_ASSOC);
    }
}

global $DB_CORE;

$DB_CORE = new Database($CFG->databases->core);

function expand_post($post)
{
    global $DB_CORE;

    $id = $post['id'];
    $post_id = $post['post_id'];
    $producer_id = $post['producer_id'];
    $categories_id = $post['categories_id'];
    $poster_id = $post['poster_id'];
    $video_id = $post['video_id'];
    $image_id = $post['image_id'];
    $description = $post['description'];
    $title = $post['title'];
    $year = $post['year'];
    $month = $post['month'];
    $day = $post['day'];

    $post_data = array
    (
        'id' => $id,
        'post_id' => $post_id,
        'categories_id' => $categories_id,
        'description' => $description,
        'title' => $title,
    );

    $post_data['date'] = array
    (
        'day' => $day,
        'month' => $month,
        'year' => $year,
    );

    $post_data['producer'] = null;
    $post_data['categories'] = array();
    $post_data['poster'] = null;
    $post_data['video'] = null;
    $post_data['image'] = null;

    if (! empty($producer_id))
    {
        $sql_producer = "SELECT * from table_producers WHERE id=$producer_id";

        $producer = $DB_CORE->query_one($sql_producer);

        $producer_id = $producer['id'];
        $producer_name = $producer['name'];
        $producer_url = $producer['url'];
        $producer_company_id = $producer['company_id'];
        $producer_description = $producer['description'];
        $producer_poster_id = $producer['poster_id'];
        $producer_advert_domain = $producer['advert_domain'];
        $producer_identifier = $producer['identifier'];
        $producer_title = $producer['title'];

        $post_data['producer'] = $producer;
    }

    if (! empty($categories_id))
    {
        $sql_categories_map = "SELECT * from table_map_categories WHERE id=$categories_id";

        $categories_map = $DB_CORE->query($sql_categories_map);

        foreach ($categories_map as $category_map)
        {
            $category_map_id = $category_map['id'];
            $category_map_post_id = $category_map['post_id'];
            $category_map_category_id = $category_map['category_id'];

            $sql_category = "SELECT * FROM table_categories WHERE category_id=$category_map_category_id";

            $category = $DB_CORE->query_one($sql_category);

            $category_id = $category['id'];
            $category_name = $category['name'];
            $category_identifier = $category['identifier'];
            $category_description = $category['description'];
            $category_category_id = $category['category_id'];

            array_push($post_data['categories'], $category);
        }
    }

    if (! empty($poster_id))
    {
        $sql_poster = "SELECT * from table_files WHERE id=$poster_id";

        $poster = $DB_CORE->query_one($sql_poster);

        $poster_id = $poster['id'];
        $poster_name = $poster['name'];
        $poster_extension = $poster['extension'];
        $poster_url = $poster['url'];
        $poster_size = $poster['size'];
        $poster_duration = $poster['duration'];

        $post_data['poster'] = $poster;
    }

    if (! empty($video_id))
    {
        $sql_video = "SELECT * from table_files WHERE id=$video_id";

        $video = $DB_CORE->query_one($sql_video);

        $video_id = $video['id'];
        $video_name = $video['name'];
        $video_extension = $video['extension'];
        $video_url = $video['url'];
        $video_size = $video['size'];
        $video_duration = $video['duration'];

        $post_data['video'] = $video;
    }

    if (! empty($image_id))
    {
        $sql_image = "SELECT * from table_files WHERE id=$image_id";

        $image = $DB_CORE->query_one($sql_image);

        $image_id = $image['id'];
        $image_name = $image['name'];
        $image_extension = $image['extension'];
        $image_url = $image['url'];
        $image_size = $image['size'];
        $image_duration = $image['duration'];

        $post_data['image'] = $image;
    }

    return $post_data;
}

function get_most_recent_posts($page=1, $size=null)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;

    if (is_null($size))
    {
        $page_size = $CFG->site->page_size;
    }
    else
    {
        $page_size = $size;
    }

    $visited = ($page - 1) * $page_size;

    $sql_posts = "SELECT * FROM table_posts ORDER BY year DESC, month DESC, day DESC, id DESC LIMIT $page_size OFFSET $visited";

    $records_posts = $db->query($sql_posts);

    foreach ($records_posts as $record_post)
    {
        $post = expand_post($record_post);

        yield $post;
    }
}

function get_total_posts()
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql_total_posts = "SELECT COUNT(*) FROM table_posts";

    $total_posts = $db->query_one($sql_total_posts)['COUNT(*)'];

    return $total_posts;
}

function get_total_producers($producers)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql_total_posts = "SELECT COUNT(*) FROM table_producers";

    if (! is_null($producers))
    {
        $sql_total_posts .= " WHERE name LIKE '$producers%'";
    }

    $total_posts = $db->query_one($sql_total_posts)['COUNT(*)'];

    return $total_posts;
}

function get_total_producer($producer_id)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql_total_posts = "SELECT COUNT(*) FROM table_posts WHERE producer_id=$producer_id";

    $total_posts = $db->query_one($sql_total_posts)['COUNT(*)'];

    return $total_posts;
}

function get_total_category($category_id)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql_total_posts =
        "SELECT COUNT(*) FROM table_posts " .
        "INNER JOIN table_map_categories ON table_posts.categories_id = table_map_categories.id " .
        "INNER JOIN table_categories ON table_categories.category_id = table_map_categories.category_id " .
        "WHERE table_categories.id = $category_id";

    $total_posts = $db->query_one($sql_total_posts)['COUNT(*)'];

    return $total_posts;
}

function get_total_search_results($search)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql_total_posts =
        "SELECT COUNT(*) FROM table_posts " .
        "WHERE description LIKE '%$search%' " .
        "OR title LIKE '%$search%' ";

    $total_posts = $db->query_one($sql_total_posts)['COUNT(*)'];

    return $total_posts;
}

function get_total_archives($year=null, $month=null, $day=null)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $wheres = array();

    if (! is_null($year))
    {
        array_push($wheres, "year=$year");
    }
    if (! is_null($month))
    {
        array_push($wheres, "month=$month");
    }
    if (! is_null($day))
    {
        array_push($wheres, "day=$day");
    }

    $where = implode(' AND ', $wheres);

    $sql_total_posts = "SELECT COUNT(*) FROM table_posts WHERE $where";

    $total_posts = $db->query_one($sql_total_posts)['COUNT(*)'];

    return $total_posts;
}

function get_pages_posts()
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;

    $total_posts = get_total_posts();

    $total_pages = ceil($total_posts / $page_size);

    return $total_pages;
}

function get_pages_archives($year=null, $month=null, $day=null)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;

    $total_posts = get_total_archives($year, $month, $day);

    $total_pages = ceil($total_posts / $page_size);

    return $total_pages;
}

function get_pages_category($category_id)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;

    $total_posts = get_total_category($category_id);

    $total_pages = ceil($total_posts / $page_size);

    return $total_pages;
}

function get_pages_search($search)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;

    $total_posts = get_total_search_results($search);

    $total_pages = ceil($total_posts / $page_size);

    return $total_pages;
}

function get_pages_producers($producers=null)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;

    $total_posts = get_total_producers($producers);

    $total_pages = ceil($total_posts / $page_size);

    return $total_pages;
}

function get_pages_producer($producer_id)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;

    $total_posts = get_total_producer($producer_id);

    $total_pages = ceil($total_posts / $page_size);

    return $total_pages;
}

function get_archived_posts($page=1, $year=null, $month=null, $day=null)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;
    $visited = ($page - 1) * $page_size;

    $wheres = array();

    if (! is_null($year))
    {
        array_push($wheres, "year=$year");
    }
    if (! is_null($month))
    {
        array_push($wheres, "month=$month");
    }
    if (! is_null($day))
    {
        array_push($wheres, "day=$day");
    }

    $where = implode(' AND ', $wheres);

    $sql_posts = "SELECT * FROM table_posts WHERE $where ORDER BY post_id DESC LIMIT $page_size OFFSET $visited";

    $records_posts = $db->query($sql_posts);

    foreach ($records_posts as $record_post)
    {
        $post = expand_post($record_post);

        yield $post;
    }
}

function get_random_archived_post($year=null, $month=null, $day=null)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $wheres = array();

    if (! is_null($year))
    {
        array_push($wheres, "year=$year");
    }
    if (! is_null($month))
    {
        array_push($wheres, "month=$month");
    }
    if (! is_null($day))
    {
        array_push($wheres, "day=$day");
    }

    $where = implode(' AND ', $wheres);

    $sql_post = "SELECT * FROM table_posts WHERE $where ORDER BY RANDOM() LIMIT 1";

    $record_post = $db->query_one($sql_post);

    $post = expand_post($record_post);

    return $post;
}

function get_latest_archived_post($year=null, $month=null, $day=null)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $wheres = array();

    if (! is_null($year))
    {
        array_push($wheres, "year=$year");
    }
    if (! is_null($month))
    {
        array_push($wheres, "month=$month");
    }
    if (! is_null($day))
    {
        array_push($wheres, "day=$day");
    }

    $where = implode(' AND ', $wheres);

    $sql_post = "SELECT * FROM table_posts WHERE $where ORDER BY post_id DESC LIMIT 1";

    $record_post = $db->query_one($sql_post);

    $post = expand_post($record_post);

    return $post;
}

function get_random_producer_post($id)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql_post = "SELECT * FROM table_posts WHERE producer_id=$id ORDER BY RANDOM() LIMIT 1";

    $record_post = $db->query_one($sql_post);

    $post = expand_post($record_post);

    return $post;
}

function get_latest_producer_post($id)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql_post = "SELECT * FROM table_posts WHERE producer_id=$id ORDER BY post_id DESC LIMIT 1";

    $record_post = $db->query_one($sql_post);

    if (! $record_post)
    {
        return;
    }

    $post = expand_post($record_post);

    return $post;
}

function get_all_categories()
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql = "SELECT * FROM table_categories ORDER BY name";

    $records = $db->query($sql);

    return $records;
}

function count_categories($key='id')
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql =
        "SELECT COUNT(table_map_categories.category_id) AS total, table_categories.$key " .
        "FROM table_map_categories " .
        "INNER JOIN table_categories ON table_map_categories.category_id=table_categories.category_id " .
        "GROUP BY table_map_categories.category_id " .
        "ORDER BY total DESC";

    $records = $db->query($sql);

    $counted = array();

    foreach ($records as $record)
    {
        $counted[$record[$key]] = $record['total'];
    }

    return $counted;
}

function get_archive_list($years=4)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql =
        "SELECT year, month FROM table_posts " .
        "WHERE year IN " .
        "(" .
            "SELECT year FROM table_posts " .
            "GROUP BY year " .
            "ORDER BY year DESC " .
            "LIMIT $years" .
        ") " .
        "GROUP BY year, month " .
        "ORDER BY year DESC, month DESC";

    $records = $db->query($sql);

    return $records;
}

function get_archive_years()
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql = "SELECT DISTINCT year FROM table_posts ORDER BY year DESC";

    $records = $db->query($sql);

    $years = array();

    foreach ($records as $record)
    {
        array_push($years, $record['year']);
    }

    return $years;
}

function get_archive_months($year)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql = "SELECT DISTINCT month FROM table_posts WHERE year=$year ORDER BY year";

    $records = $db->query($sql);

    $months = array();

    foreach ($records as $record)
    {
        array_push($months, $record['month']);
    }

    return $months;
}

function get_archive_days($year, $month)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql = "SELECT DISTINCT day FROM table_posts WHERE year=$year AND month=$month ORDER BY day";

    $records = $db->query($sql);

    $days = array();

    foreach ($records as $record)
    {
        array_push($days, $record['day']);
    }

    return $days;
}

function get_random_posts($size=null)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;

    if (is_null($size))
    {
        $page_size = $CFG->site->page_size;
    }
    else
    {
        $page_size = $size;
    }

    $sql_posts = "SELECT * FROM table_posts ORDER BY RANDOM() LIMIT $page_size";

    $records_posts = $db->query($sql_posts);

    foreach ($records_posts as $record_post)
    {
        $post = expand_post($record_post);

        yield $post;
    }
}

function count_producers($limit=30, $key='id')
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;

    $sql =
        "SELECT COUNT(producer_id) AS total, table_producers.$key FROM table_posts " .
        "INNER JOIN table_producers ON producer_id=table_producers.id " .
        "GROUP BY producer_id " .
        "ORDER BY total DESC " .
        "LIMIT $limit";

    $records = $db->query($sql);

    $counted = array();

    foreach ($records as $record)
    {
        $counted[$record[$key]] = $record['total'];
    }

    return $counted;
}

function search_posts($search, $page=1)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;
    $visited = ($page - 1) * $page_size;

    $sql_posts =
        "SELECT * FROM table_posts " .
        "WHERE description LIKE '%$search%' " .
        "OR title LIKE '%$search%' " .
        "ORDER BY post_id DESC " .
        "LIMIT $page_size " .
        "OFFSET $visited";

    $records_posts = $db->query($sql_posts);

    foreach ($records_posts as $record_post)
    {
        $post = expand_post($record_post);

        yield $post;
    }
}

function get_producer_posts($id, $page=1)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;
    $visited = ($page - 1) * $page_size;

    $sql_posts = "SELECT * FROM table_posts WHERE producer_id=$id ORDER BY post_id DESC LIMIT $page_size OFFSET $visited";

    $records_posts = $db->query($sql_posts);

    foreach ($records_posts as $record_post)
    {
        $post = expand_post($record_post);

        yield $post;
    }
}

function get_category_posts($id, $page=1)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;
    $visited = ($page - 1) * $page_size;

    $sql_posts =
        "SELECT table_posts.* FROM table_posts " .
        "INNER JOIN table_map_categories ON table_posts.categories_id = table_map_categories.id " .
        "INNER JOIN table_categories ON table_categories.category_id = table_map_categories.category_id " .
        "WHERE table_categories.id = $id ORDER BY table_posts.post_id DESC LIMIT $page_size OFFSET $visited";

    $records_posts = $db->query($sql_posts);

    foreach ($records_posts as $record_post)
    {
        $post = expand_post($record_post);

        yield $post;
    }
}

function get_most_recent_category_post($id)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql_post =
        "SELECT table_posts.* FROM table_posts " .
        "INNER JOIN table_map_categories ON table_posts.categories_id = table_map_categories.id " .
        "INNER JOIN table_categories ON table_categories.category_id = table_map_categories.category_id " .
        "WHERE table_categories.id = $id ORDER BY table_posts.post_id DESC LIMIT 1";

    $record_post = $db->query_one($sql_post);

    $post = expand_post($record_post);

    return $post;
}

function get_random_category_post($id)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql_post =
        "SELECT table_posts.* FROM table_posts " .
        "INNER JOIN table_map_categories ON table_posts.categories_id = table_map_categories.id " .
        "INNER JOIN table_categories ON table_categories.category_id = table_map_categories.category_id " .
        "WHERE table_categories.id = $id ORDER BY RANDOM() LIMIT 1";

    $record_post = $db->query_one($sql_post);

    $post = expand_post($record_post);

    return $post;
}

function get_producers($page=1, $startswith=null)
{
    global $DB_CORE;
    global $CFG;

    $db = $DB_CORE;
    $page_size = $CFG->site->page_size;
    $visited = ($page - 1) * $page_size;

    $where = "";

    if (! is_null($startswith))
    {
        $where = "WHERE name LIKE '$startswith%' ";
    }

    $sql = "SELECT * FROM table_producers " . $where . "ORDER BY name LIMIT $page_size OFFSET $visited";

    $records = $db->query($sql);

    return $records;
}

function get_producers_from_ids($ids)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $ids_len = count($ids);
    $where_in = "";
    $case = "";

    foreach ($ids as $index => $id)
    {
        $rank = $index + 1;

        $where_in .= "$id";
        $case .= "WHEN $id THEN $rank";

        if ($index != $ids_len - 1)
        {
            $where_in .= ",";
            $case .= " ";
        }
    }

    $sql =
        "SELECT * FROM table_producers " .
        "WHERE id IN ($where_in) " .
        "ORDER BY CASE id $case END";

    $records = $db->query($sql);

    return $records;
}

function get_producer($id)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql = "SELECT * FROM table_producers WHERE id=$id";

    $record = $db->query_one($sql);

    return $record;
}

function get_category($id)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql = "SELECT * FROM table_categories WHERE id=$id";

    $record = $db->query_one($sql);

    return $record;
}

function get_post($id)
{
    global $DB_CORE;

    $db = $DB_CORE;

    $sql = "SELECT * FROM table_posts WHERE id=$id";

    $record = $db->query_one($sql);

    if (! $record)
    {
        return;
    }

    $post = expand_post($record);

    return $post;
}

if(! $DB_CORE)
{
    //echo $DB_CORE->get_error();

    die();
}

?>
