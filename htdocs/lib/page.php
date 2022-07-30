<?php

if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

require_once import('config');
require_once import('db');
require_once import('core');

function indent($amount, $str, $val="\t")
{
    $data = str_repeat($val, $amount) . $str;

    return $data;
}

class Post
{
    public $post;
    public $individual;

    function __construct($post, $individual=false)
    {
        $this->post = $post;
        $this->individual = $individual;
    }

    function dump($indent=0)
    {
        global $CFG;

        $post = $this->post;
        $data = "";

        $categories = $post['categories'];
        $categories_len = count($categories);

        $video = ! is_null($post['video']);
        $poster = ! is_null($post['poster']);

        $producer = $post['producer'];
        $has_producer = ! is_null($producer);

        if ($video)
        {
            $duration = pretty_duration($post['video']['duration']);
            $size = pretty_size($post['video']['size']);
        }
        else
        {
            $duration = '0:00';
            $size = pretty_size($post['image']['size']);
        }

        $data .= indent($indent + 0, "<div class=\"content-post\">\n");
        $data .= indent($indent + 1, "<div class=\"entry-header\">\n");
        $data .= indent($indent + 2, " <h2 class=\"entry-title\">\n");
        $data .= indent($indent + 3, "<a href=\"/post/" . $post['id'] . "\">\n");
        $data .= indent($indent + 4, $post['title'] . "\n");
        $data .= indent($indent + 3, "</a>\n");
        $data .= indent($indent + 2, "</h2>\n");
        $data .= indent($indent + 1, "</div>\n");
        $data .= indent($indent + 0, "\n");
        $data .= indent($indent + 1, "<div class=\"entry-meta\">\n");
        $data .= indent($indent + 2, "<div class=\"meta-item meta-categories\">\n");

        for ($category_index = 0;  $category_index < $categories_len; ++$category_index)
        {
            $category = $categories[$category_index];

            $data .= indent($indent + 3, "<a href=\"/category/" . $category['id'] . "\">" . $category['name'] . "</a>\n");

            if ($category_index != $categories_len - 1)
            {
                $data .= indent($indent + 3, $CFG->html->elements->post->meta->categories->separator . "\n");
                $data .= indent($indent + 0, "\n");
            }
        }

        $data .= indent($indent + 2, "</div>\n");

        if ($categories_len != 0)
        {
            $data .= indent($indent + 2, $CFG->html->elements->post->meta->separator . "\n");
            $data .= indent($indent + 0, "\n");
        }

        $post_year = $post['date']['year'];
        $post_month = $post['date']['month'];
        $post_day = $post['date']['day'];

        $data .= indent($indent + 2, "<div class=\"meta-item meta-date\">\n");
        $data .= indent($indent + 3, "<span>\n");
        $data .= indent
        (
            $indent + 4,
            "<a href=\"" . "/archives/$post_year/$post_month/$post_day" . "\">" .
            pretty_date
            (
                $post_year,
                $post_month,
                $post_day
            ) .
            "</a>"
        );
        $data .= indent($indent + 3, "</span>\n");
        $data .= indent($indent + 2, "</div>\n");
        $data .= indent($indent + 2, $CFG->html->elements->post->meta->separator . "\n");
        $data .= indent($indent + 0, "\n");
        $data .= indent($indent + 2, "<div class=\"meta-item meta-size\">\n");
        $data .= indent($indent + 3, "<span>" . $size . "</span>\n");
        $data .= indent($indent + 2, "</div>\n");

        if ($duration != '0:00')
        {
            $data .= indent($indent + 2, $CFG->html->elements->post->meta->separator . "\n");
            $data .= indent($indent + 0, "\n");
            $data .= indent($indent + 2, "<div class=\"meta-item meta-duration\">\n");
            $data .= indent($indent + 3, "<span>" . $duration . "</span>\n");
            $data .= indent($indent + 2, "</div>\n");
        }

        if ($has_producer)
        {
            $data .= indent($indent + 2, $CFG->html->elements->post->meta->separator . "\n");
            $data .= indent($indent + 0, "\n");
            $data .= indent($indent + 2, "<div class=\"meta-item meta-producer\">\n");
            $data .= indent($indent + 3, "<a href=\"/producer/" . $producer['id'] . "\">" . $producer['name'] . "</a>\n");
            $data .= indent($indent + 2, "</div>\n");
        }

        $data .= indent($indent + 1, "</div>\n");
        $data .= indent($indent + 0, "\n");
        $data .= indent($indent + 1, "<div class=\"entry-content\">\n");

        if ($video)
        {
            if ($poster)
            {
                $poster_url = '/file/' . $post['post_id'] . '/' . $post['poster']['local_name'] . '.' . $post['poster']['extension'];
            }
            else
            {
                $poster_url = '';
            }

            $video_extension = $post['video']['extension'];

            $video_type = 'video/mp4';

            $video_url = "/file/" . $post['post_id'] . "/video.mp4";

            $data .= indent($indent + 2, "<video class=\"entry-video video-js vjs-default-skin  vjs-16-9 vjs-big-play-centered\" data-setup='{\"aspectRatio\":\"818:460\"}' controls=\"controls\" poster=\"" . $poster_url . "\" preload=\"none\">\n");
            $data .= indent($indent + 3, "<source src=\"$video_url\" type=\"" . $video_type . "\">\n");
            $data .= indent($indent + 2, "</video>\n");
        }
        else
        {
            $image_url = '/file/' . $post['post_id'] . '/' . $post['image']['local_name'] . '.' . $post['image']['extension'];

            $data .= indent($indent + 2, "<img src=\"$image_url\">\n");
        }

        $data .= indent($indent + 1, "</div>\n");
        $data .= indent($indent + 0, "\n");
        $data .= indent($indent + 1, "<div class=\"entry-actions\">\n");
        $data .= indent($indent + 1, "</div>\n");
        $data .= indent($indent + 0, "\n");
        $data .= indent($indent + 1, "<div class=\"entry-description\">\n");
        $data .= indent($indent + 2, "<p" . ($this->individual ? "" : " class=\"dynamic-description\"") . ">\n");
        $data .= indent($indent + 3, $post['description'] . "\n");
        $data .= indent($indent + 2, "</p>\n");
        $data .= indent($indent + 1, "</div>\n");
        $data .= indent($indent + 0, "</div>\n");

        return $data;
    }
}

class PostCategory
{
    public $title = '';
    public $image = '';
    public $url = '';
    public $description = '';

    function __construct($title=null, $url=null, $post=null, $image=null, $description=null)
    {
        if (! is_null($post))
        {
            $poster = $post['poster'];
            $image = $post['image'];

            $post_id = $post['id'];
            $post_post_id = $post['post_id'];

            /*if (! is_null($poster))
            {
                $image = $poster;
            }
            else if (! is_null($image))
            {
                $image = $image;
            }
            else
            {
                $image = null;
            }*/

            $image = $poster;

            if (! is_null($image))
            {
                $image_name = $image['name'];
                $image_local_name = $image['local_name'];
                $image_extension = $image['extension'];
                $image_filename = "$image_local_name.$image_extension";

                $image_url = "/file/$post_post_id/$image_filename";

                $image = $image_url;
            }
        }

        $this->set_title($title);
        $this->set_image($image);
        $this->set_url($url);
        $this->set_description($description);
    }

    function set_title($title)
    {
        if (! is_null($title))
        {
            $this->title = $title;
        }
    }

    function set_description($description)
    {
        if (! is_null($description))
        {
            $this->description = $description;
        }
    }

    function set_image($image)
    {
        if (! is_null($image))
        {
            $this->image = $image;
        }
    }

    function set_url($url)
    {
        if (! is_null($url))
        {
            $this->url = $url;
        }
    }

    function dump($indent=0)
    {
        $data = "";

        $data .= indent($indent + 0, "<div class=\"content-post aspect-container\">\n");
        $data .= indent($indent + 1, "<div class=\"aspect-dummy\">\n");
        $data .= indent($indent + 2, "<div class=\"entry-header\">\n");
        $data .= indent($indent + 3, "<h2 class=\"entry-title\">\n");
        $data .= indent($indent + 4, "<a href=\"" . $this->url . "\">\n");
        $data .= indent($indent + 5, $this->title . "\n");
        $data .= indent($indent + 4, "</a>\n");
        $data .= indent($indent + 3, "</h2>\n");
        $data .= indent($indent + 2, "</div>\n");
        $data .= indent($indent + 0, "\n");
        $data .= indent($indent + 2, "<div class=\"entry-content\">\n");
        $data .= indent($indent + 3, "<a href=\"" . $this->url . "\">\n");
        $data .= indent($indent + 4, "<img src=\"" . $this->image . "\" style=\"width: 100%;\" alt=\"" . $this->description . "\">\n");
        $data .= indent($indent + 3, "</a>\n");
        $data .= indent($indent + 2, "</div>\n");
        $data .= indent($indent + 0, "\n");
        $data .= indent($indent + 2, "<div class=\"entry-actions\">\n");
        $data .= indent($indent + 2, "</div>\n");
        $data .= indent($indent + 1, "</div>\n");
        $data .= indent($indent + 0, "</div>\n");

        return $data;
    }
}

class Page
{
    public $title;
    public $additonal_head = '';
    public $nav;
    public $info_title;
    public $info_description;
    public $load_more_url;
    public $pagination_url;
    public $pagination_current;
    public $pagination_total;
    public $posts = array();
    public $body_top = '';

    function __construct($title=null, $nav=null)
    {
        global $CFG;

        $this->title = $CFG->html->title;

        if (! is_null($title))
        {
            $this->set_title($title);
        }

        if (! is_null($nav))
        {
            $this->set_nav($nav);
        }
    }

    function append_head($html)
    {
        $this->additonal_head .= "$html\n";
    }

    function insert_body($html)
    {
        $this->body_top .= "$html\n";
    }

    function set_title($title)
    {
        global $CFG;

        $this->title = $CFG->html->title . ' - ' . $title;
    }

    function set_nav($nav)
    {
        $this->nav = $nav;
    }

    function set_page_info($title=null, $description='')
    {
        $this->info_title = $title;
        $this->info_description = $description;
    }

    function set_load_more($url)
    {
        $this->load_more_url = $url;
    }

    function set_pagination($url, $current, $total=null)
    {
        $this->pagination_url = $url;
        $this->pagination_current = $current;
        $this->pagination_total = $total;
    }

    function add_post($post)
    {
        array_push($this->posts, $post);
    }

    function dump()
    {
        global $CFG;

        $data = "";

        $data .= indent(0, "<!DOCTYPE html>\n");
        $data .= indent(0, "\n");
        $data .= indent(0, "<html lang=\"" . $CFG->html->lang . "\">\n");
        $data .= indent(1, "<head>\n");
        $data .= indent(2, "<meta charset=\"" . $CFG->html->meta->charset . "\">\n");
        $data .= indent(2, "<meta name=\"viewport\" content=\"" . $CFG->html->meta->viewport . "\">\n");
        $data .= indent(2, "<meta name=\"description\" content=\"" . $CFG->html->meta->description . "\">\n");
        $data .= indent(2, "<meta name=\"keywords\" content=\"" . $CFG->html->meta->keywords . "\">\n");
        $data .= indent(2, "<meta name=\"robots\" content=\"" . "index,follow" . "\">\n");
        $data .= indent(2, "<meta http-equiv=\"Content-type\" content=\"" . "text/html; charset=UTF-8" . "\">\n");
        $data .= indent(0, "\n");
        $data .= indent(2, "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"/favicon.ico\" />\n");
        $data .= indent(2, "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $CFG->html->assets->fonts->arimo . "\" />\n");
        $data .= indent(2, "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $CFG->html->assets->fonts->fontawesome . "\" />\n");
        $data .= indent(2, "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $CFG->html->assets->fonts->dashicons . "\" />\n");
        $data .= indent(2, "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $CFG->html->assets->css->main . "\" />\n");
        $data .= indent(2, "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $CFG->html->assets->packages->video_js->css_min . "\" />\n");
        $data .= indent(2, "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $CFG->html->assets->packages->video_js->css_new . "\" />\n");
        $data .= indent(2, "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $CFG->html->assets->packages->video_js->css_custom . "\" />\n");
        $data .= indent(0, "\n");
        $data .= indent(2, "<script src=\"" . $CFG->html->assets->packages->video_js->js_min . "\"></script>\n");
        $data .= indent(0, "\n");
        $data .= indent(2, "<title>" . $this->title . "</title>\n");
        if ($this->additonal_head != '')
        {
            $data .= indent(0, "\n");
            $data .= indent(0, $this->additonal_head);
        }
        $data .= indent(1, "</head>\n");
        $data .= indent(1, "<body>\n");
        $data .= indent(2, "<div class=\"header\">\n");
        $data .= indent(3, "<h1 class=\"site-title\">\n");
        $data .= indent(4, "<a href=\"/\">" . $CFG->site->title . "</a>\n");
        $data .= indent(3, "</h1>\n");
        $data .= indent(0, "\n");
        $data .= indent(3, "<span class=\"site-description\">" . $CFG->site->description . "</span>\n");
        $data .= indent(2, "</div>\n");
        $data .= indent(0, "\n");
        $data .= indent(2, "<div class=\"top-nav\">\n");
        $data .= indent(3, "<ul>\n");
        $data .= indent(4, "<li>\n");
        $data .= indent(5, "<a class=\"" . (($this->nav == $CFG->html->elements->nav->home->text) ? "active" : "") . "\" href=\"" . $CFG->html->elements->nav->home->link . "\">" . $CFG->html->elements->nav->home->text . "</a>\n");
        $data .= indent(4, "</li>\n");
        $data .= indent(0, "\n");
        $data .= indent(4, "<li>\n");
        $data .= indent(5, "<a class=\"" . (($this->nav == $CFG->html->elements->nav->categories->text) ? "active" : "") . "\" href=\"" . $CFG->html->elements->nav->categories->link . "\">" . $CFG->html->elements->nav->categories->text . "</a>\n");
        $data .= indent(4, "</li>\n");
        $data .= indent(0, "\n");
        $data .= indent(4, "<li>\n");
        $data .= indent(5, "<a class=\"" . (($this->nav == $CFG->html->elements->nav->shuffle->text) ? "active" : "") . "\" href=\"" . $CFG->html->elements->nav->shuffle->link . "\">" . $CFG->html->elements->nav->shuffle->text . "</a>\n");
        $data .= indent(4, "</li>\n");
        $data .= indent(0, "\n");
        $data .= indent(4, "<li>\n");
        $data .= indent(5, "<a class=\"" . (($this->nav == $CFG->html->elements->nav->archives->text) ? "active" : "") . "\" href=\"" . $CFG->html->elements->nav->archives->link . "\">" . $CFG->html->elements->nav->archives->text . "</a>\n");
        $data .= indent(4, "</li>\n");
        $data .= indent(0, "\n");
        $data .= indent(4, "<li>\n");
        $data .= indent(5, "<a class=\"" . (($this->nav == $CFG->html->elements->nav->producers->text) ? "active" : "") . "\" href=\"" . $CFG->html->elements->nav->producers->link . "\">" . $CFG->html->elements->nav->producers->text . "</a>\n");
        $data .= indent(4, "</li>\n");
        $data .= indent(0, "\n");
        $data .= indent(4, "<div class=\"search-container\">\n");
        $data .= indent(5, "<form action=\"" . $CFG->html->elements->search->action . "\">\n");
        $data .= indent(6, "<input type=\"text\" placeholder=\"" . $CFG->html->elements->search->placeholder . "\" name=\"search\">\n");
        $data .= indent(0, "\n");
        $data .= indent(6, "<button type=\"submit\">\n");
        $data .= indent(7, "<i class=\"fa fa-search\"></i>\n");
        $data .= indent(6, "</button>\n");
        $data .= indent(5, "</form>\n");
        $data .= indent(4, "</div>\n");
        $data .= indent(3, "</ul>\n");
        $data .= indent(2, "</div>\n");
        $data .= indent(0, "\n");
        if ($this->body_top)
        {
            $data .= indent(0, $this->body_top);
            $data .= indent(0, "\n");
        }
        $data .= indent(2, "<div class=\"content-wrapper\">\n");
        if (! is_null($this->info_title))
        {
            $data .= indent(3, "<div class=\"content-intro\">\n");
            $data .= indent(4, "<div class=\"intro-header\">\n");
            $data .= indent(5, "<h1>" . $this->info_title . "</h1>\n");
            $data .= indent(4, "</div>\n");
            $data .= indent(0, "\n");
            $data .= indent(4, "<div class=\"intro-description\">\n");
            $data .= indent(5, "<p>" . $this->info_description . "</p>\n");
            $data .= indent(4, "</div>\n");
            $data .= indent(3, "</div>\n");
            $data .= indent(0, "\n");
        }
        $data .= indent(3, "<div class=\"content-posts\">\n");
        foreach ($this->posts as $post)
        {
            $post_html = $post->dump($indent=4);

            if ($post_html)
            {
                $data .= indent(0, $post_html . "\n");
            }
        }

        if (! is_null($this->load_more_url) && (is_null($this->pagination_total) || $this->pagination_current != $this->pagination_total))
        {
            $data .= indent(0, "\n");
            $data .= indent(4, "<nav class=\"pagination-load-more\">\n");
            $data .= indent(5, "<a href=\"" . $this->load_more_url . "\">\n");
            $data .= indent(6, "<span class=\"dashicons dashicons-update\"></span>\n");
            $data .= indent(6, $CFG->html->elements->pagination->load_more->text . "\n");
            $data .= indent(5, "</a>\n");
            $data .= indent(4, "</nav>\n");
        }
        $data .= indent(3, "</div>\n");
        $data .= indent(0, "\n");
        $data .= indent(3, "<div class=\"side-menu\">\n");

        if ($CFG->html->behaviour->sub_menu->visible)
        {
            $data .= indent(4, "<div class=\"widget widget-categories\">\n");
            $data .= indent(5, "<h4 class=\"widget-title\">\n");
            $data .= indent(6, "<span>" . "Categories" . "</span>\n");
            $data .= indent(5, "</h4>\n");
            $data .= indent(0, "\n");
            $data .= indent(5, "<ul>\n");

            $categories = get_all_categories();
            $category_counts = count_categories();

            foreach ($categories as $category)
            {
                $category_name = $category['name'];
                $category_id = $category['id'];

                if ($category_name == 'Admin' || $category_name == 'Uncategorized')
                {
                    continue;
                }

                $category_count = $category_counts[$category_id];

                $data .= indent(6, "<li>\n");
                $data .= indent(7, "<a href=\"" . "/category/$category_id" . "\">\n");
                $data .= indent(8, "<span class=\"widget-category-count\">" . $category_count . "</span>\n");
                $data .= indent(8, "<span class=\"widget-category-text\">" . $category_name . "</span>\n");
                $data .= indent(7, "</a>\n");
                $data .= indent(6, "</li>\n");
            }

            $data .= indent(5, "</ul>\n");
            $data .= indent(4, "</div>\n");

            $data .= indent(4, "<div class=\"widget widget-archives\">\n");
            $data .= indent(5, "<h4 class=\"widget-title\">\n");
            $data .= indent(6, "<span>" . "Archives" . "</span>\n");
            $data .= indent(5, "</h4>\n");
            $data .= indent(0, "\n");
            $data .= indent(5, "<ul>\n");

            $archives = get_archive_list();

            foreach ($archives as $archive)
            {
                $archive_year = $archive['year'];
                $archive_month = $archive['month'];

                $pretty_archive_month = pretty_month($archive_month);

                $data .= indent(6, "<li>\n");
                $data .= indent(7, "<a href=\"" . "/archives/$archive_year/$archive_month" . "\">" . "$pretty_archive_month $archive_year" . "</a>\n");
                $data .= indent(6, "</li>\n");
            }

            $data .= indent(5, "</ul>\n");
            $data .= indent(4, "</div>\n");

            $data .= indent(4, "<div class=\"widget widget-new\">\n");
            $data .= indent(5, "<h4 class=\"widget-title\">\n");
            $data .= indent(6, "<span>" . "New Videos" . "</span>\n");
            $data .= indent(5, "</h4>\n");
            $data .= indent(0, "\n");
            $data .= indent(5, "<div class=\"widget-new-content\">\n");

            $new_posts = iterator_to_array(get_most_recent_posts($page=1, $size=30));
            $new_posts_len = count($new_posts);

            foreach ($new_posts as $index => $new_post)
            {
                $post_id = $new_post['id'];
                $post_post_id = $new_post['post_id'];
                $poster_name = $new_post['poster']['local_name'];
                $poster_extension = $new_post['poster']['extension'];

                $data .= indent(6, "<a class=\"widget-new-entry\" href=\"" . "/post/$post_id" . "\">\n");
                $data .= indent(7, "<img src=\"" . "/file/$post_post_id/$poster_name.$poster_extension" . "\" width=\"100%\" border=\"0\">\n");
                $data .= indent(6, "</a>\n");

                if ($index != $new_posts_len - 1)
                {
                    $data .= indent(0, "\n");
                    $data .= indent(6, "<br>\n");
                    $data .= indent(6, "<br>\n");
                    $data .= indent(0, "\n");
                }
            }

            $data .= indent(5, "</div>\n");
            $data .= indent(4, "</div>\n");

            $data .= indent(4, "<div class=\"widget widget-producers\">\n");
            $data .= indent(5, "<h4 class=\"widget-title\">\n");
            $data .= indent(6, "<span>" . "Top Producers" . "</span>\n");
            $data .= indent(5, "</h4>\n");
            $data .= indent(0, "\n");
            $data .= indent(5, "<ul>\n");

            $producers_counted = count_producers();
            $producers_ids = array_keys($producers_counted);
            $producers = get_producers_from_ids($producers_ids);

            foreach ($producers as $producer)
            {
                $producer_id = $producer['id'];
                $producer_name = $producer['name'];

                $producer_count = $producers_counted[$producer_id];

                $data .= indent(6, "<li>\n");
                $data .= indent(7, "<a href=\"" . "/producer/$producer_id" . "\">\n");
                $data .= indent(8, "<span class=\"widget-producer-count\">" . $producer_count . "</span>\n");
                $data .= indent(8, "<span class=\"widget-producer-text\">" . $producer_name . "</span>\n");
                $data .= indent(7, "</a>\n");
                $data .= indent(6, "</li>\n");
            }

            $data .= indent(5, "</ul>\n");
            $data .= indent(4, "</div>\n");

            $data .= indent(4, "<div class=\"widget widget-searches\">\n");
            $data .= indent(5, "<h4 class=\"widget-title\">\n");
            $data .= indent(6, "<span>" . "Suggested Searches" . "</span>\n");
            $data .= indent(5, "</h4>\n");
            $data .= indent(0, "\n");
            $data .= indent(5, "<ul>\n");

            $suggested_searches = array
            (
                'SSD', 'Flash', 'Storage'
            );

            foreach ($suggested_searches as $search)
            {
                $search_lowercase = strtolower($search);

                $data .= indent(6, "<li>\n");
                $data .= indent(7, "<a href=\"" . "/search/$search_lowercase" . "\">" . $search . "</a>\n");
                $data .= indent(6, "</li>\n");
            }

            $data .= indent(5, "</ul>\n");
            $data .= indent(4, "</div>\n");

            $data .= indent(4, "<div class=\"widget widget-shuffled\">\n");
            $data .= indent(5, "<h4 class=\"widget-title\">\n");
            $data .= indent(6, "<span>" . "Shuffled Videos" . "</span>\n");
            $data .= indent(5, "</h4>\n");
            $data .= indent(0, "\n");
            $data .= indent(5, "<div class=\"widget-shuffled-content\">\n");

            $shuffled_posts = iterator_to_array(get_random_posts($size=30));
            $shuffled_posts_len = count($shuffled_posts);

            foreach ($shuffled_posts as $index => $shuffled_post)
            {
                $post_id = $shuffled_post['id'];
                $post_post_id = $shuffled_post['post_id'];
                $poster_name = $shuffled_post['poster']['local_name'];
                $poster_extension = $shuffled_post['poster']['extension'];

                $data .= indent(6, "<a class=\"widget-shuffled-entry\" href=\"" . "/post/$post_id" . "\">\n");
                $data .= indent(7, "<img src=\"" . "/file/$post_post_id/$poster_name.$poster_extension" . "\" width=\"100%\" border=\"0\">\n");
                $data .= indent(6, "</a>\n");

                if ($index != $new_posts_len - 1)
                {
                    $data .= indent(0, "\n");
                    $data .= indent(6, "<br>\n");
                    $data .= indent(6, "<br>\n");
                    $data .= indent(0, "\n");
                }
            }
        }

        $data .= indent(5, "</div>\n");
        $data .= indent(4, "</div>\n");

        $data .= indent(3, "</div>\n");
        $data .= indent(2, "</div>\n");
        $data .= indent(0, "\n");
        $data .= indent(2, "<footer>\n");

        $url_next = "";

        if (! is_null($this->pagination_url))
        {
            $data .= indent(3, "<div class=\"pagination-container\">\n");
            $data .= indent(4, "<div class=\"pagination\">\n");
            $data .= indent(5, "<span class=\"pagination-pages\">" . $CFG->html->elements->pagination->text . "</span>\n");
            $data .= indent(0, "\n");

            $url_page_first = $this->pagination_url . "1";

            if ($this->pagination_current == 1)
            {
                $data .= indent(5, "<a>&lt;&lt;</a>\n");
            }
            else
            {
                $data .= indent(5, "<a href=\"" . $url_page_first. "\">&lt;&lt;</a>\n");
            }

            if ($this->pagination_current > 1)
            {
                $url_previous = $this->pagination_url . ($this->pagination_current - 1);
                $url_current = $this->pagination_url . $this->pagination_current;

                $data .= indent(5, "<a href=\"" . $url_previous . "\">" . ($this->pagination_current - 1) . "</a>\n");
                $data .= indent(5, "<a class=\"active\">" . ($this->pagination_current) . "</a>\n");

                if (is_null($this->pagination_total) || $this->pagination_current != $this->pagination_total)
                {
                    $url_next = $this->pagination_url . ($this->pagination_current + 1);

                    $data .= indent(5, "<a href=\"" . $url_next . "\">" . ($this->pagination_current + 1) . "</a>\n");
                }
            }
            else
            {
                $url_current = $this->pagination_url . $this->pagination_current;

                $data .= indent(5, "<a class=\"active\">" . $this->pagination_current . "</a>\n");

                if (is_null($this->pagination_total) || $this->pagination_current < $this->pagination_total)
                {
                    $url_next = $this->pagination_url . ($this->pagination_current + 1);

                    $data .= indent(5, "<a href=\"" . $url_next . "\">" . ($this->pagination_current + 1) . "</a>\n");
                }

                if (is_null($this->pagination_total) || $this->pagination_current + 1 < $this->pagination_total)
                {
                    $url_next_2 = $this->pagination_url . ($this->pagination_current + 2);

                    $data .= indent(5, "<a href=\"" . $url_next_2 . "\">" . ($this->pagination_current + 2) . "</a>\n");
                }
            }

            if (! is_null($this->pagination_total))
            {
                $url_page_last = $this->pagination_url . $this->pagination_total;

                if ($this->pagination_current == $this->pagination_total)
                {
                    $data .= indent(5, "<a>&gt;&gt;</a>\n");
                }
                else
                {
                    $data .= indent(5, "<a href=\"" . $url_page_last. "\">&gt;&gt;</a>\n");
                }
            }

            $data .= indent(0, "\n");
            $data .= indent(5, "<div class=\"pagination-next\">\n");

            if (! is_null($this->pagination_total) && $this->pagination_current == $this->pagination_total)
            {
                $data .= indent(6, "<a>\n");
            }
            else
            {
                $data .= indent(6, "<a href=\"$url_next\">\n");
            }

            $data .= indent(7, "<span class=\"dashicons dashicons-arrow-right-alt2\">\n");
            $data .= indent(7, "</span>\n");
            $data .= indent(6, "</a>\n");
            $data .= indent(5, "</div>\n");
            $data .= indent(4, "</div>\n");
            $data .= indent(3, "</div>\n");
            $data .= indent(0, "\n");
        }

        $data .= indent(3, "<div class=\"copyright\">\n");
        $data .= indent(4, "<div class=\"copyright-container\">\n");
        $data .= indent(5, "<p class=\"copyright-content\">\n");
        $data .= indent(6, $CFG->site->footer . "\n");
        $data .= indent(0, "\n");
        $data .= indent(6, "<a class=\"copyright-links\" href=\"" . $CFG->site->disclaimer_url . "\" target=\"_blank\" rel=\"noopener noreferrer\">\n");
        $data .= indent(7, "<strong class=\"copyright-links-text\">" . $CFG->site->disclaimer_text . "</strong>\n");
        $data .= indent(6, "</a>\n");
        $data .= indent(5, "</p>\n");
        $data .= indent(4, "</div>\n");
        $data .= indent(3, "</div>\n");
        $data .= indent(2, "</footer>\n");
        $data .= indent(1, "</body>\n");
        $data .= indent(0, "</html>");

        return $data;
    }
}

?>
