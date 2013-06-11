<?php
/**
 * Created by JetBrains PhpStorm.
 * User: chad.thompson
 * Date: 6/7/13
 * Time: 1:36 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('classes/DataFeed.php');

class DataFeedTest extends PHPUnit_Framework_TestCase {


    ## Test #1:  Parse an XML document with the Simple Pie parser.  Measure time required for data retrieval
    ## and parsing on average over a number of iterations.
    public function testReadAndParseNetwork(){
        $dataFeed = new DataFeed();
        $timer = new PHP_Timer();
        $timer ->start();
        $dataFeed->readAndParseNetwork();
        $time = $timer->stop();

        print "NETWORK READ AND PARSE: $time \n\n";

        $this->assertEquals(5,  sizeof($dataFeed->feedarray));
    }

    ## Test #2:  Parse an XML document, store data in a memcache instance.  Test over a number of iterations.
    public function testReadAndParseDiskCache(){
        $dataFeed = new DataFeed();
        $timer = new PHP_Timer();
        $timer->start();
        $dataFeed->readAndParseDiskCache();


        $time = $timer->stop();

        print "DISK READ AND PARSE: $time \n\n";


    }


    ## Test #3:  Parse an XML document, generate HTML to store in memached.  Test over a number of iterations.
    public function testMemcacheReadAndParse(){

        $dataFeed = new DataFeed();
        $dataFeed->cacheFilesToMemcache();
        $timer = new PHP_Timer();
        $timer->start();
        $dataFeed->readAndParseMemcacheCache();
        $time = $timer->stop();

        print "MEMCACHE READ AND PARSE: $time\n\n";





    }


}
