<?php
if(!function_exists('import')){function import($lib){return $_SERVER['DOCUMENT_ROOT']."/lib/$lib.php";}}

header("Content-type: text/css");

require_once import('config');

?>
/*
html {
    height: 100%;
    width: 100%;
    margin: 0;
}
*/

html {
    min-height: 100%;
}

body {
  margin: 0;
  background-color: #f6f6f6;
  /*height: 100%;
  width: 100%;*/

}

/*
video::-webkit-media-controls-overlay-play-button {
  display: none;
}

*::-webkit-media-controls-panel {
  display: none!important;
  -webkit-appearance: none;
}

*::-webkit-media-controls-play-button {
  display: none!important;
  -webkit-appearance: none;
}

*::-webkit-media-controls-start-playback-button {
  display: none!important;
  -webkit-appearance: none;
}
*/

.top-nav ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  <?php
  if ($CFG->html->behaviour->nav->sticky)
  {
      echo 'position: -webkit-sticky; /* Safari */';
      echo 'position: sticky;';
  }
  ?>
  top: 0;
  font-family: Arimo;
  background-color: #f6f6f6;
  text-transform: uppercase;
  font-weight: 400;
  line-height: 26px;
}

.top-nav ul li {
  float: left;
}

.top-nav ul li a {
  display: block;
  color: #111111;
  text-align: center;
  padding: 25px 15px;
  text-decoration: none;

  font-size: 14px;
}

.top-nav ul li a:hover:not(.active) {
  color: #56a5d9;
}

.top-nav ul li a.active {
  color: #56a5d9;
}

div.header {
  background-color: #ffffff;
  padding-top: 10px;
  padding-bottom: 10px;

}

h1.site-title {
    margin: 0;
}

h1.site-title a {
  font-size: 28px;
  font-family: Arimo;
  font-weight: 700;
  color: #111111;
  line-height: 50px;
  text-decoration: none;
  outline: 0;
  box-sizing: border-box;
  text-transform: uppercase;
  text-align: center;
  display: block;
  word-wrap: break-word;
  margin: 0;
}

span.site-description {
  font-size: 12px;
  line-height: 20px;
  display: block;
  text-transform: uppercase;
  box-sizing: border-box;
  text-align: center;
  color: #111111;
  font-family: Arimo;
  font-weight: 400;
  word-wrap: break-word;
  outline: 0;
  text-decoration: none;
}

@media screen and (max-width: 600px) {
  .top-nav .search-container {
    /*float: none;*/
  }
  .top-nav a, .top-nav input[type=text], .top-nav .search-container button {
    float: left; /*none*/
    display: block;
    text-align: left;
    /*width: 100%;*/
    margin: 0;
    /*padding: 14px;*/
  }
  .top-nav input[type=text] {
    border: 1px solid #ccc;
  }
}

.top-nav .search-container {
  float: right;
  padding-top: 10px;
  padding-bottom: 10px;
}

.top-nav input[type=text] {
  padding: 6px;
  margin-top: 8px;
  font-size: 17px;
  /*font-size: 20px;*/
  /*font-size: 1.5em;*/
  border: none;
  width: 130px;
}

.top-nav .search-container button {
  float: right;
  padding: 6px 10px;
  margin-top: 8px;
  margin-right: 16px;
  background: #ddd;
  font-size: 17px;
  border: none;
  cursor: pointer;
  /*height: 36px;*/
  width: 38px;
}

.top-nav .search-container button:hover {
  background: #ccc;
}

.top-nav {
    <?php
    if ($CFG->html->behaviour->nav->sticky)
    {
        echo 'position: -webkit-sticky; /* Safari */';
        echo 'position: sticky;';
    }
    ?>
  top: 0;
  z-index: 1;
}

div.content-posts {
  /*padding: 10px;*/
  background-color: #f6f6f6;
  <?php
  if ($CFG->html->behaviour->sub_menu->visible)
  {
      echo 'width: 75%; /* Width of "left" div  */';
  }
  else
  {
      echo 'width: 100%;';
  }
  ?>
  margin-bottom: 30px;
  float: left;
  /*height: 100%;*/
}

div.entry-content img {
    display: block;
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;

}

div.content-post {
    margin-bottom: 50px;
}

h2.entry-title {
  margin: 0;
}

h2.entry-title a {
  font-family: Arimo;
  color: #56a5d9;
  display: block;
  text-decoration: none;
  box-sizing: border-box;
  text-transform: none;
  font-weight: 700;
  word-wrap: break-word;
  margin-bottom: 3px;
  font-size: 28px;
  line-height: 36px;
  margin-top: 0;
}

h2.entry-title a:hover {
  color: #fe6e1e;
}

div.meta-categories a {
  font-family: Arimo;
  color: #fe6e1e;
  text-decoration: none;
  font-size: 18px;
  margin-left: 8px;
  margin-right: 8px;
}

div.meta-producer a {
  font-family: Arimo;
  color: #fe6e1e;
  text-decoration: none;
  font-size: 18px;
  margin-left: 8px;
  margin-right: 8px;
}

div.meta-item {
  color: #999999;
  font-family: Arimo;
  font-weight: 400;
  font-size: 19px;
  display: inline-block;
  vertical-align: top;
  box-sizing: border-box;
  line-height: 20px;
  word-wrap: break-word;
}

div.entry-meta {
  color: #cccccc;
  font-family: Arimo;
  font-weight: 400;
  font-size: 19px;
  margin-bottom: 10px;
  box-sizing: border-box;
  word-wrap: break-word;
  line-height: 20px;
}

div.meta-item span {
  font-family: Arimo;
  text-decoration: none;
  font-size: 18px;
  margin: 8px;
  color: #999999;
}

div.meta-item span a {
    text-decoration: none;
    color: #999999;
}

video.entry-video {
  padding-top: 56.25%;
  width: 100%;
  display: block;
  vertical-align: top;
  box-sizing: border-box;
  color: #fff;
  background-color: #000;
  position: relative;
  padding: 0;
  padding-top: 0px;
}

@media screen and (max-width: 700px) {
    p.dynamic-description {
        display: none !important;
    }
}

div.entry-description p {
  font-size: 23px;
  line-height: 25px;
  color: #666666;
  padding: 0;
  margin: 0;
  font-family: Arimo;
  text-align: justify;
  box-sizing: border-box;
  font-weight: 400;
  word-wrap: break-word;
}

div.entry-description  {
  margin-top: 20px;
}

.pagination {
    display: inline-block;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 24px;
    word-spacing: 16px;
    font-weight: bold;
    text-align: center;
    padding: 0px 0px 0px 0px;
    box-sizing: border-box;
    color: #ffffff;
    font-family: Arimo;
    line-height: 20px;
    word-wrap: break-word;
    flex-wrap: wrap;
}

.pagination span {
    float: left;
    padding: 8px 16px;
}

.pagination a {
    float: left;
    /*padding: 8px 16px;*/
    text-decoration: none;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 24px;
    word-spacing: 16px;
    font-weight: bold;
    text-align: center;
    box-sizing: border-box;
    font-family: Arimo;
    line-height: 20px;
    word-wrap: break-word;
}

.pagination a.active {
  color: #fe6e1e;
}

.pagination a:hover:not(.active) {
}

nav.pagination-load-more {
    padding-top: 4px;
    min-height: 43px;
    margin-top: 36px;
    clear: both;
    position: relative;
    text-align: center;
    display: block;
    box-sizing: border-box;
    color: #111111;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px;
    line-height: 20px;
    word-wrap: break-word;
}

nav.pagination-load-more a {
    font-size: 52px;
    line-height: 65px;
    font-weight: bold;
    color: #FFF;
    background-color: #fe6e1e;
    padding: 14px 27px;
    text-align: center;
    display: inline-block;
    border: none;
    text-decoration: none;
    box-sizing: border-box;
    font-family: Arimo;
    word-wrap: break-word;
    text-transform: uppercase;
}

nav.pagination-load-more a span {
    font-size: 60px;
    width: 55px;
    display: inline-block;
    height: 20px;
    line-height: 1;
    font-family: dashicons;
    text-decoration: inherit;
    font-weight: 400;
    font-style: normal;
    vertical-align: top;
    text-align: center;
    box-sizing: border-box;
    color: #FFF;
    text-transform: uppercase;
    word-wrap: break-word;
}

footer {
    background: #34495e;
    color: #ffffff;
    float: left;
    width: 100%;
    padding: 0;
    display: block;
    box-sizing: border-box;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px;
    line-height: 20px;
    word-wrap: break-word;
    /*padding-left: 5%;
    padding-right: 5%;*/
}

div.pagination-next a {
    padding-top: 0px;
    padding-left: 0px;
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    box-sizing: border-box;
    text-align: center;
    text-transform: uppercase;
    word-spacing: 16px;
    font-weight: bold;
    font-family: Arimo;
    font-size: 13px;
    line-height: 20px;
    word-wrap: break-word;
}

div.pagination-next a span {
    display: inline-block;
    width: 20px;
    height: 20px;
    font-size: 20px;
    line-height: 1;
    font-family: dashicons;
    text-decoration: inherit;
    font-weight: 400;
    font-style: normal;
    vertical-align: top;
    text-align: center;
    box-sizing: border-box;
    color: rgba(255,255,255,0.8);
    text-transform: uppercase;
    word-spacing: 16px;
    word-wrap: break-word;
}

div.pagination-next {
    background-color: #fe6e1e;
    color: #FFF;
    font-size: 12px;
    /*padding: 14px 27px;*/
    padding: 14px 20px;
    text-align: center;
    display: inline-block;
    text-transform: uppercase;
    color: #fff;
    border: none;
    line-height: 1;
    box-sizing: border-box;
    word-spacing: 16px;
    font-weight: bold;
    font-family: Arimo;
    word-wrap: break-word;
}

div.side-menu {
    float: left;
    margin-left: 30px;
    width: 20%;
}

@media screen and (max-width: 750px) {
    .side-menu {
        display: none;
        width: 0% !important;
    }

    .content-posts {
        width: 100% !important;
    }
}

div.content-wrapper {
    overflow: hidden;
    padding: 10px;
    /*width: 100%;*/
    /*height: 100%;*/
    min-height: calc(100vh - 278px);
}

div.intro-header h1 {
    text-transform: uppercase;
    font-family: Arimo;
    color: #56a5d9;
    display: block;
    text-decoration: none;
    box-sizing: border-box;
    font-weight: 700;
    word-wrap: break-word;
    /*margin-bottom: 3px;*/
    font-size: 20px;
    line-height: 1;
    margin-top: 0;
    /*padding-bottom: 10px;*/
}

div.intro-header h1 a {
    text-transform: uppercase;
    font-family: Arimo;
    color: #56a5d9;
    /*display: block;*/
    text-decoration: none;
    box-sizing: border-box;
    font-weight: 700;
    word-wrap: break-word;
    /*margin-bottom: 3px;*/
    font-size: 20px;
    line-height: 1;
    margin-top: 0;
    /*padding-bottom: 10px;*/
}

div.intro-description p {
    font-family: Arimo;
    font-size: 17px;
    font-weight: 400;
    margin: 0;
}

@media screen and (max-width: 600px) {
    .aspect-container {
        width: 100%;
        padding-top: 64%;
    }
}

@media screen and (max-width: 1000px) and (min-width: 600px) {
    .aspect-container {
        width: 50%;
        padding-top: 32%;
    }
}

@media screen and (min-width: 1000px) {
    .aspect-container {
        width: 33.3%;
        padding-top: 20%;
    }
}

.aspect-container {
    display: inline;
    float: left;
    overflow: hidden;
    margin-bottom: 0px !important;
    position: relative;
}

.aspect-dummy {
    position: absolute;
    top: 0;
    left: 10px;
    bottom: 0;
    right: 10px;
}

.content-intro {
    margin-bottom: 20px;
}

div.selector-container {
    width: 100%;
    display: flex;
    justify-content: space-between;
    /*align-items: center;
    font-size: 24px;
    word-spacing: 16px;
    font-weight: bold;
    line-height: 20px;
    word-wrap: break-word;
    padding: 0px 0px 0px 0px;*/
    box-sizing: border-box;
    font-family: Arimo;
    padding-left: 20px;
    padding-right: 20px;
    flex-wrap: wrap;
    text-align: center;
    color: #ffffff;
}

a.selector-selection {
    font-family: Arimo;
    color: #56a5d9;
    /*
    display: block;
    box-sizing: border-box;
    word-wrap: break-word;
    margin-bottom: 3px;
    margin-top: 0;
    */
    text-decoration: none;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 28px;
    line-height: 36px;
    min-width: 30px;
}

a.selector-selection:hover:not(.active) {
  color: #fe6e1e;
}

a.selector-selection.active {
  color: #fe6e1e;
}

@media screen and (max-width: 500px) {
    a.selector-selection {
        flex: 0 0 14%;
    }
}

@media screen and (max-width: 1000px) and (min-width: 500px) {
    a.selector-selection {
        flex: 0 0 7%;
    }
}

@media screen and (min-width: 1000px) {
    a.selector-selection {
        flex: 0 0 0%;
    }
}

@media screen and (max-width: 375px)
{
    span.pagination-pages
    {
        display: none;
    }
}

.pagination-next a {
    padding: 8px 16px;
}

.copyright {
    background: rgba(0,0,0,.25);
    /*height: 80px;*/
    font-size: 10px;
    padding: 28px 0;
    letter-spacing: 1px;
    text-transform: uppercase;
    box-sizing: border-box;
    color: #ffffff;
    font-family: Arimo;
    font-weight: 400;
    word-wrap: break-word
    padding-top: 20px;
    padding-bottom: 20px;
}

.copyright-container {
    /*width: 976px;*/
    padding-left: 18px;
    padding-right: 18px;
    margin-right: auto;
    margin-left: auto;
    box-sizing: border-box;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #ffffff;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.copyright-content {
    text-align: center;
    margin-bottom: 0;
    font-size: 10px;
    letter-spacing: 1px;
    color: rgba(255,255,255,.5);
    line-height: 22px;
    /*margin: 0 0 24px;*/
    box-sizing: border-box;
    text-transform: uppercase;
    font-family: Arimo;
    font-weight: 400;
    word-wrap: break-word;
}

.copyright-links {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    box-sizing: border-box;
    text-align: center;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-family: Arimo;
    font-weight: 400;
    /*font-size: 13px !important;*/
    line-height: 20px !important;
    word-wrap: break-word;
}

.copyright-links-text {
    font-weight: 700;
    box-sizing: border-box;
    color: rgba(255,255,255,0.8);
    text-align: center;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-family: Arimo;
    /*font-size: 13px !important;*/
    line-height: 20px !important;
    word-wrap: break-word;
    padding-left: 5px;
}

.pagination-container {
    /*margin-left: 5%;
    margin-right: 5%;*/
    /*margin-left: 10px;*/
    margin-left: 50px;
    margin-right: 10px;
}

.widget {
    border: none !important;
    margin-bottom: 36px;
    padding: 0;
    box-sizing: border-box;
    color: #111111;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-title {
    text-transform: uppercase;
    color: #56a5d9;
    font-family: Arimo;
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 16px;
    line-height: 22px;
    margin-top: 0;
    box-sizing: border-box;
    word-wrap: break-word;
}

.widget-title span {
    box-sizing: border-box;
    text-transform: uppercase;
    color: #56a5d9;
    font-family: Arimo;
    font-weight: 700;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-categories ul {
    padding: 0;
    margin: 0;
    list-style: none;
    box-sizing: border-box;
    color: #111111;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-categories ul li {
    margin-bottom: 4px;
    box-sizing: border-box;
    list-style: none;
    color: #111111;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-categories ul li a {
    font-size: 21px !important;
    color: #111111;
    line-height: 22px;
    font-weight: 500;
    text-decoration: none;
    box-sizing: border-box;
    list-style: none;
    font-family: Arimo;
    word-wrap: break-word;
}

.widget-category-text {
    box-sizing: border-box;
    font-size: 21px !important;
    color: #111111;
    font-weight: 500;
    list-style: none;
    font-family: Arimo;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-category-count {
    margin-right: 7px;
    float: left;
    background-color: #fe6e1e;
    display: inline-block;
    color: #FFF;
    font-size: 10px;
    min-width: 24px;
    height: 24px;
    line-height: 10px;
    padding: 6px 0;
    vertical-align: 1px;
    text-align: center;
    border-radius: 50%;
    box-sizing: border-box;
    font-weight: 500;
    list-style: none;
    font-family: Arimo;
    word-wrap: break-word;
}

.widget-archives ul {
    padding: 0;
    margin: 0;
    list-style: none;
    box-sizing: border-box;
    color: #111111;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-archives ul li {
    margin-bottom: 0 !important;
    color: #999999;
    box-sizing: border-box;
    list-style: none;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-archives ul li a {
    color: #111111;
    font-size: 14px;
    line-height: 22px;
    font-weight: 500;
    text-decoration: none;
    box-sizing: border-box;
    list-style: none;
    font-family: Arimo;
    word-wrap: break-word;
}

.widget-new-content {
    line-height: 14px;
    font-size: 14px;
    box-sizing: border-box;
    color: #111111;
    font-family: Arimo;
    font-weight: 400;
    word-wrap: break-word;
}

.widget-new-entry {
    color: #fe6e1e;
    text-decoration: none;
    box-sizing: border-box;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-new-entry img {
    height: auto;
    max-width: 100%;
    vertical-align: middle;
    border: 0;
    box-sizing: border-box;
    color: #fe6e1e;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-shuffled-content {
    line-height: 14px;
    font-size: 14px;
    box-sizing: border-box;
    color: #111111;
    font-family: Arimo;
    font-weight: 400;
    word-wrap: break-word;
}

.widget-shuffled-entry {
    color: #fe6e1e;
    text-decoration: none;
    box-sizing: border-box;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-shuffled-entry img {
    height: auto;
    max-width: 100%;
    vertical-align: middle;
    border: 0;
    box-sizing: border-box;
    color: #fe6e1e;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-searches ul {
    padding: 0;
    margin: 0;
    list-style: none;
    box-sizing: border-box;
    color: #111111;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-searches ul li {
    margin-bottom: 0 !important;
    color: #999999;
    box-sizing: border-box;
    list-style: none;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-searches ul li a {
    color: #111111;
    font-size: 14px;
    line-height: 22px;
    font-weight: 500;
    text-decoration: none;
    box-sizing: border-box;
    list-style: none;
    font-family: Arimo;
    word-wrap: break-word;
}

.widget-producers ul {
    padding: 0;
    margin: 0;
    list-style: none;
    box-sizing: border-box;
    color: #111111;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-producers ul li {
    margin-bottom: 4px;
    box-sizing: border-box;
    list-style: none;
    color: #111111;
    font-family: Arimo;
    font-weight: 400;
    font-size: 13px !important;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-producers ul li a {
    font-size: 21px !important;
    color: #111111;
    line-height: 22px;
    font-weight: 500;
    text-decoration: none;
    box-sizing: border-box;
    list-style: none;
    font-family: Arimo;
    word-wrap: break-word;
}

.widget-producer-text {
    box-sizing: border-box;
    font-size: 21px !important;
    color: #111111;
    font-weight: 500;
    list-style: none;
    font-family: Arimo;
    line-height: 20px !important;
    word-wrap: break-word;
}

.widget-producer-count {
    margin-right: 7px;
    float: left;
    /*background-color: #fe6e1e;*/
    background-color: #56a5d9;
    display: inline-block;
    color: #FFF;
    font-size: 10px;
    min-width: 24px;
    height: 24px;
    line-height: 10px;
    padding: 6px 0;
    vertical-align: 1px;
    text-align: center;
    border-radius: 50%;
    box-sizing: border-box;
    font-weight: 500;
    list-style: none;
    font-family: Arimo;
    word-wrap: break-word;
}
