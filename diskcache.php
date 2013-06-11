<?php
require 'vendor/autoload.php';
require_once 'classes/DataFeed.php';

$dataFeed = new DataFeed();
$dataFeed->readAndParseDiskCache();

include_once 'header.inc.php';

$m = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates'),
));

$feed = new SimplePie();
foreach($dataFeed->feedarray as $currentFeed){
    $feed = $currentFeed;
    echo $m->render('feed', array("url" => $feed->feed_url));

    $feeditems = array();
    foreach ($feed->get_items() as $item){
        echo $m->render('feeditem', array("permalink" => $item->get_permalink() ));

    }
}

include_once 'footer.inc.php';

