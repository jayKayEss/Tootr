<?php

require_once dirname(__FILE__).'/../lib/Consts.php';
require_once dirname(__FILE__).'/../lib/DB.php';
require_once dirname(__FILE__).'/../lib/Generator.php';

$stream = Tootr\Consts::$STREAMS[array_rand(Tootr\Consts::$STREAMS)];
$db = new Tootr\DB();
$streamId = $db->getStreamId($stream);
$generator = new Tootr\Generator($db, $stream);
$tweet = $generator->generate();

header('Content-type: application/json');

print json_encode(array(
    'text' => $tweet,
    'stream' => $stream
));


