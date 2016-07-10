<?php
namespace SlackReact;

use Slack\Payload;
use Slack\RealTimeClient;

/**
 * Class TaskLink
 * Sample class to get links from slack messages
 *
 * @package SlackReact
 */
class TaskLink implements TaskInterface
{
    /**≥
     * @param RealTimeClient $client
     * @param Payload $payload
     * @return mixed|void
     */
    public function run(RealTimeClient $client, Payload $payload)
    {
        $data = $payload->getData();

        /* When slack receive a link, they usually edit the message, so it is another structure */
        $text = $data['previous_message']['text'] ?? $data['text'];

        /* this regex was found here: https://mathiasbynens.be/demo/url-regex */
        /* it was used the @stephenhay one with a slight modification */
        $pattern = '@<(https?|ftp):\/\/[^\s\/$.?#].[^\s]*>@iS';
        if (preg_match_all($pattern, $text, $output)) {
            for ($i = 0; $i < count($output[0]); $i++)
                printf("(%s) %s\n", static::class, substr($output[0][$i], 1, count($output[0][$i]) - 2));
        }
    }
}
