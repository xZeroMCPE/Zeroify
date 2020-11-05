<?php


namespace xZeroMCPE\Zeroify\Events;


use pocketmine\event\plugin\PluginEvent;
use xZeroMCPE\Zeroify\Configuration\MessageConfiguration;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class PlayerQuitGameEvent extends PluginEvent
{
    public ZeroifyPlayer $player;
    public MessageConfiguration $message;

    public function __construct(ZeroifyPlayer $player, MessageConfiguration $message)
    {
        $this->player = $player;
        $this->message = $message;
        parent::__construct(Zeroify::getInstance()->getEnvironment()->getPlugin());
    }

    /**
     * @return ZeroifyPlayer
     */
    public function getPlayer(): ZeroifyPlayer
    {
        return $this->player;
    }

    /**
     * @param ZeroifyPlayer $player
     */
    public function setPlayer(ZeroifyPlayer $player): void
    {
        $this->player = $player;
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