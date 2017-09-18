<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <?php wp_head(); ?>
    <script>
    //var in_ajax = 0; //bot indexing
    var in_ajax = 1; //real user
<?php
  include __DIR__ . "/Crawler/CrawlerDetect.php";
  foreach (glob(__DIR__."/Crawler/Fixtures/*.php") as $filename)
  {
      include $filename;
  }
  use Jaybizzle\CrawlerDetect\CrawlerDetect;

  $CrawlerDetect = new CrawlerDetect;

  // Check the user agent of the current 'visitor'
  if($CrawlerDetect->isCrawler()) {
    echo "in_ajax = 0;";
  }
?>
    </script>
</head>
<body <?php body_class(); ?>>
<div id="appDiv">