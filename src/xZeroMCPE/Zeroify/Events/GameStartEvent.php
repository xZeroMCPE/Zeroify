<?php


namespace xZeroMCPE\Zeroify\Events;


use pocketmine\event\plugin\PluginEvent;
use pocketmine\plugin\Plugin;
use xZeroMCPE\Zeroify\Configuration\MessageConfiguration;
use xZeroMCPE\Zeroify\Zeroify;

class GameStartEvent extends PluginEvent
{

    public MessageConfiguration $message;

    public function __construct(MessageConfiguration $message)
    {
        $this->message = $message;
        parent::__construct(Zeroify::getInstance()->getEnvironment()->getPlugin());
    }

    /**
     * @return MessageConfiguration
     */
    public function getMessage(): MessageConfiguration
    {
        return $this->message;
    }

    /**
     * @param MessageConfiguration $message
     */
    public function setMessage(MessageConfiguration $message): void
    {
        $this->message = $message;
    }
}