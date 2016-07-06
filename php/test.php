<?php
include './vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();

$client = new \Slack\RealTimeClient($loop);
$client->setToken('xoxp-22665357441-22696295792-57122648562-f4462592c1');

$client->connect()->then(function () use ($client) {
    echo "Connected!\n";


    $client->getChannelByName('games')->then(function(\Slack\Channel $channel) use ($client) {
//        var_dump($channel);
        /*
        $client->apiCall('channels.history', [
            'channel' => $channel->getId(),
            'type' => 'message',
            'text' => 'testando...',
            'count' => 10,
            'ts' => DateTime::createFromFormat('y-m-d', date('y-m-d'))->getTimestamp()
        ])->then(function(Slack\Payload $obj) {
//            var_dump('alksjdlaksjdlkjas');
            var_dump($obj->getData()['messages']);
        }, function($error) { print $error->getMessage(); });
        */
        $client->on('message', function(\Slack\Payload $data) use ($client, $channel) {
            $data = $data->getData();
            var_dump($data);
            if ($data['channel'] !== $channel->getId()) return;

//            var_dump($data['text']);
//            $client->send('Ja recebi a msg...', $channel);
        });
    }, function($error) { print $error->getMessage(); });
});

//$client->on('message', function($data) use ($client) {
//    echo "Someone typed a message: ".$data['text']."\n";
//    echo $data['channel']."\n";
////    $client->disconnect();
//});

//$client->apiCall('auth.test')->then(function($item){
//    var_dump($item);
//});

//$client = new \Slack\ApiClient($loop);
//$client->setToken('xoxp-22665357441-22696295792-57122648562-f4462592c1');
//
//$client->getUserByName('fake')->then(function (\Slack\User $user) use ($client) {
//    $client->getDMByUser($user)->then(function ($dm) use ($client) {
////         $client->send('Hello from PHP!', $channel);
//        $client->send(sprintf('%s %d', 'testando', (int)rand()), $dm);
//    });
//}, function($error) {
//    print $error->getMessage();
//});

// disconnect after first message
// $client->on('message', function ($data) use ($client) {
//     echo "Someone typed a message: ".$data['text']."\n";
//     // $client->disconnect();
// });

// $client->connect()->then(function () {
//     echo "Connected!\n";
// });

$loop->run();
