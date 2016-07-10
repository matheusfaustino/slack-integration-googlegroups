<?php
namespace SlackReact;

use Slack\Payload;
use Slack\RealTimeClient;

/**
 * Class TaskPrint
 * Sample class that prints every message sent in channel
 * @package SlackReact
 */
class TaskPrint implements TaskInterface
{
    public function run(RealTimeClient $client, Payload $payload)
    {
        $data = $payload->getData();

        $text = $data['previous_message']['text'] ?? $data['text'];

        printf("(%s) Mensagem enviada: %s \n", static::class, $text);
    }
}
