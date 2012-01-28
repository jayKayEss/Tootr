<?php

namespace Tootr;

require_once 'lib/Consts.php';
require_once 'lib/DB.php';
require_once 'lib/Parser.php';
require_once 'lib/Generator.php';

$db = new DB();

foreach (Consts::$STREAMS as $stream) {
    $streamId = $db->getStreamId($stream);
    $generator = new Generator($db, $stream);
    $tweet = $generator->generate() . "\n";

    print "$stream [".strlen($tweet)."] $tweet\n";
}

