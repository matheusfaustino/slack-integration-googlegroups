<?php
namespace SlackReact;

use React\EventLoop\LoopInterface;
use Slack\RealTimeClient;

/**
 * Class Client
 * It represents your slack and this class bases its implementation in Slack\RealTimeClient
 *
 * @package SlackReact
 */
class Client
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var RealTimeClient
     */
    private $realTimeClient;

    /**
     * @var ChannelInterface[]
     */
    private $attachedChannelEvent;

    /**
     * Client constructor.
     * @param string $token
     * @param LoopInterface $loop
     * @param RealTimeClient $realTimeClient
     */
    public function __construct($token, LoopInterface $loop, RealTimeClient $realTimeClient)
    {
        $this->token = $token;
        $this->loop = $loop;
        $this->realTimeClient = $realTimeClient;
    }

    public function run(): void
    {
        /* sets token of slack */
        $this->realTimeClient->setToken($this->token);

        $this->realTimeClient->connect()->then(function () {
            /* Adds channels events */
            if (count($this->attachedChannelEvent)) {
                foreach ($this->attachedChannelEvent as $_channel) {
                    $this->realTimeClient
                        ->getChannelByName($_channel->getName())
                        ->then(function (\Slack\Channel $channel) use ($_channel) {
                            $_channel->run($channel, $this->realTimeClient);
                        }, function ($error) {
                            // @todo improves error event
                            throw new \Exception($error->getMessage());
                        });
                }
            }
            // @todo adds new kind of events
        }, function ($error) {
            // @todo improves error
            throw new \Exception($error->getMessage());
        });

        /* runs the client and create the web socket */
        $this->loop->run();
    }

    /**
     * Sets channel that will be called in run method.
     *
     * @param ChannelInterface $attachedChannelEvent
     * @return $this
     */
    public function setAttachedChannelEvent(ChannelInterface $attachedChannelEvent)
    {
        $this->attachedChannelEvent[] = $attachedChannelEvent;

        return $this;
    }
}
