# Memcached Example

The goal of this project is to demonstrate how memcached (and cache expiry) can be used 
to retrieve and store networked data.  In this particular example, the app will display
data retrieved from an RSS feed.

## Prerequisites

A few things that you'll need to get started with the project

1.  A separate "localhost" install of memcached.  
2.  The php-memcached (Note the "d") library.  
3.  PHP Composer.  Composer handles all PHP dependencies outside of the apt- installed memecached libraries.

(The process will be different on RedHat variants.)