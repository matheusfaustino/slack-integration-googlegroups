<?php
include './vendor/autoload.php';

$config = include_once 'configuration.php';

$loop = \React\EventLoop\Factory::create();

$client = new \Slack\RealTimeClient($loop);
$client->setToken($config['token']);

/* @todo Make things by attachment */
$client->connect()->then(function () use ($client, $config) {
    echo "Connected!...";

    foreach ($config['channel'] as $_channel) {
        $client->getChannelByName($_channel)->then(function(\Slack\Channel $channel) use ($client) {
            echo "Channel {$channel->getName()} found \n";
            $client->on('message', function(\Slack\Payload $data) use ($client, $channel) {
                $data = $data->getData();

                if ($data['channel'] !== $channel->getId()) return;

                $text = isset($data['subtype']) ? $data['previous_message']['text'] : $data['text'];

                $pattern = '@<(https?|ftp):\/\/[^\s\/$.?#].[^\s]*>@iS';
                if (preg_match_all($pattern, $text, $output)) {
                    for($i = 0; $i < count($output[0]); $i++)
                        printf("%s\n", substr($output[0][$i], 1, count($output[0][$i]) - 2));

//                printf("\nUrl encontrada...");
                }
//            $client->send('Ja recebi a msg...', $channel);
            });
        }, function($error) { print $error->getMessage(); });
    }
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
