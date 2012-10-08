#ZingersCrossed

##Fingers Crossed logging implementation for Zend Framework 2.

Quite probably a waste of time, as I've just found a Writer has been merged into the 2.1 branch of ZF2. But still.

Clone into your vendor folder, and enable in your `application.config.php`. ZingersCrossed can then be instantiated
with it's own writer(s):

~~~
$ZingersCrossed = new \ZingersCrossed\ZingersCrossedWriter();
$writer = new \Zend\Log\Writer\Stream('log.txt');
$ZingersCrossed->addWriter($writer);

$logger = new \Zend\Log\Logger();
$logger->addWriter($ZingersCrossed);

// default logging level is ERR so
// info won't get logged unless...
$logger->info('INFO message');

// you also trigger ERR or lower
$logger->err('ERROR message');
~~~

@GeeH

