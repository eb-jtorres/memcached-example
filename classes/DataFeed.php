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

    var $feedarray = array();

    var $cacheFilePath = 'data/';

    var $meminstance;

    function __construct()
    {
        $this->meminstance = new Memcached();
        $this->meminstance->addServer('localhost',11211);
    }


    function readAndParseNetwork(){

        // Parse using SimplePie
        foreach($this->urlarray as $name => $value){
            $feed = new SimplePie();
            $feed->enable_cache(false);
            $feed->set_feed_url($this->blogurl . $value);
            $feed->init();

            $this->feedarray[$name] = $feed;
        }

    }

    function readAndParseDiskCache(){

        foreach($this->urlarray as $name => $value){
            $feed = new SimplePie();
            $feed->enable_cache(false);

            // Check for file existence - if it doesn't exist, grab the feed and cache the data.
            $filename = $this->cacheFilePath."$name.xml";
            if (!file_exists($filename)){
                $this->cacheFileToDisk($name);
            }

            $feed->set_feed_url($this->cacheFilePath. "$name.xml");
            $feed->init();

            $this->feedarray[$name] = $feed;

        }
    }

    function readAndParseMemcacheCache(){
        foreach($this->urlarray as $name => $value){
            $feed = new SimplePie();
            $feed->enable_cache(false);

            $feedString = $this->meminstance->get($name);

            if(!$feedString){
                if($this->meminstance->getResultCode() == Memcached::RES_NOTFOUND){
                    $this->cacheFileToMemcache($name);
                    $feedString = $this->meminstance->get($name);
                } else {
                    // error.
                    print "Memcached Error";
                }
            }

            $feed->set_raw_data($feedString);
            $feed->init();


            $this->feedarray[$name] = $feed;
        }

    }

    function cacheFileToDisk($name){

        $xmlhttp = file_get_contents($this->blogurl . $this->urlarray["$name"]);

        if(!empty($xmlhttp)){

            $filehandle = fopen($this->cacheFilePath . "$name.xml", 'w');
            if(is_resource($filehandle)){
                fwrite($filehandle, $xmlhttp);
            }
            fclose($filehandle);

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

    function cacheFileToMemcache($name){
        $xmlhttp = file_get_contents($this->blogurl . $this->urlarray["$name"]);
        if(!empty($xmlhttp)){
            $this->meminstance->set($name, $xmlhttp);
        }
    }

    function cacheFilesToMemcache(){
        // Write files to memcache


        foreach($this->urlarray as $name => $value){

            $xmlhttp = file_get_contents($this->blogurl . $value);

            print("CACHE $this->blogurl$value\n");

            if(!empty($xmlhttp)){
                $this->meminstance->set($name, $xmlhttp);
            }

        }

    }







}