<?php
use Slack\RealTimeClient;
use SlackReact\{Channel, TaskLink};

require_once './../vendor/autoload.php';

$configuration = include './configuration.php';

$channel = new Channel('apittest');
$channel->setAttachedTaskEvent('message', new TaskLink());

$loop = \React\EventLoop\Factory::create();
$client = new \SlackReact\Client($configuration['token'], $loop, new RealTimeClient($loop));
$client->setAttachedChannelEvent($channel);
$client->run();
