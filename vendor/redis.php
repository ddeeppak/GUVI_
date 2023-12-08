<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Predis\Client;

$redis = new Client();

\Amp\Loop::run(function () use ($redis) {
    try {
        $result = yield $redis->get('LoginStatus');
        echo $result;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
});

?>