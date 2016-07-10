<?php
namespace SlackReact;

use Slack\Payload;
use Slack\RealTimeClient;

/**
 * Interface TaskInterface
 * @package SlackReact
 */
interface TaskInterface
{
    /**
     * It is invoked inside the events
     *
     * @param RealTimeClient $client
     * @param Payload $payload
     * @return mixed
     */
    public function run(RealTimeClient $client, Payload $payload);
}
