<?php
namespace SlackReact;

use Slack\Channel;
use Slack\RealTimeClient;

/**
 * Interface ChannelInterface
 * @package SlackReact
 */
interface ChannelInterface
{
    /**
     * The logic of the channel task
     *
     * @param Channel $channel
     * @param RealTimeClient $client
     * @return mixed
     */
    public function run(Channel $channel, RealTimeClient $client);

    /**
     * Returns channel's name.
     * It will help filter some events.
     *
     * @return string
     */
    public function getName(): string;
}
