<?php
namespace SlackReact;

use Slack\Payload;
use Slack\RealTimeClient;

/**
 * Class Channel
 * Attach events in a specific channel of your slack
 *
 * @package SlackReact
 */
class Channel extends ChannelAbstract
{
    /**
     * Tasks attached in channel
     *
     * @var TaskInterface[]
     */
    private $attachedTaskEvent;

    /**
     * Channel constructor.
     *
     * @param string $channelName
     */
    public function __construct(string $channelName)
    {
        $this->name = $channelName;
    }

    /**
     * Sets tasks that will be called for a specific channel.
     * It is used in "run" function
     *
     * @param string $event
     * @param TaskInterface $attachedTaskEvent
     * @return Channel
     */
    public function setAttachedTaskEvent(string $event, TaskInterface $attachedTaskEvent)
    {
        $this->attachedTaskEvent[$event][] = $attachedTaskEvent;

        return $this;
    }

    /**
     * Called when the client connects
     *
     * @param \Slack\Channel $channel
     * @param RealTimeClient $client
     * @return mixed
     */
    public function run(\Slack\Channel $channel, RealTimeClient $client)
    {
        if (!count($this->attachedTaskEvent))
            return;

        foreach ($this->attachedTaskEvent as $event => $tasks) {
            $client->on($event, function (Payload $payload) use ($tasks, $channel, $client) {
                $data = $payload->getData();
                // @todo improves channel validation. Check in Slack lib used
                if ($data['channel'] !== $channel->getId())
                    return;

                foreach ($tasks as $task) {
                    $task->run($client, $payload);
                }
            });
        }
    }

    /**
     * Get channel's name.
     * It is used to get the channel in slack's client
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
