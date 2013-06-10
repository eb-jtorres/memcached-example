<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chad.thompson
 * Date: 6/7/13
 * Time: 1:31 PM
 * To change this template use File | Settings | File Templates.
 */
require 'vendor/autoload.php';

class DataFeed {

    var $blogurl = "http://news.clearancejobs.com";

    var $urlarray = array(
        "featured"          => "/feed/?type=featured",
        "big-news"          => "/feed/?type=big-news",
        "secondary-feature" => "/feed/?type=secondary-feature",
        "info-grid"         => "/feed/?type=info-grid",
        "blog-roll"         => "/feed/?type=blog-roll"
    );

    var $cacheFilePath = 'data/';


    function readAndParseNetwork(){

        // Read a file from network.

        // Parse using SimplePie


        foreach($this->urlarray as $name => $value){
            print "$value" . "\n";
            $feed = new SimplePie();
            $feed->enable_cache(false);
            $feed->set_feed_url($this->blogurl . $value);
            $feed->init();
        }

    }


    function readAndParseDiskCache(){

        foreach($this->urlarray as $name => $value){
            $feed = new SimplePie();
            $feed->enable_cache(false);
            $feed->set_feed_url($this->cacheFilePath. "$name.xml");
            $feed->init();

        }



    }




    function cacheFilesToDisk(){

        // Loop through files, write to disk in the data/ directory.

        foreach($this->urlarray as $name => $value){

            ## NEEDS PECL Extenstion - try curl
            $xmlhttp = file_get_contents($this->blogurl . $value);

            print("CACHE $this->blogurl$value\n");

            if(!empty($xmlhttp)){
                $filehandle = fopen($this->cacheFilePath . "$name.xml", 'w');
                if(is_resource($filehandle)){
                    fwrite($filehandle, $xmlhttp);
                }
                fclose($filehandle);

            }



        }


    }

    function cacheFilesToMemcache(){
        // Write files to memcache






    }





}