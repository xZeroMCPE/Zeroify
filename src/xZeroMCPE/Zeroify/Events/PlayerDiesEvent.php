<?php


namespace xZeroMCPE\Zeroify\Events;


use pocketmine\event\plugin\PluginEvent;
use xZeroMCPE\Zeroify\Configuration\MessageConfiguration;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class PlayerDiesEvent extends PluginEvent
{

    public ZeroifyPlayer $victim;
    public ?ZeroifyPlayer $attacker = null;
    public int $cause;
    public MessageConfiguration $message;
    public MessageConfiguration $deathMessage;

    public function __construct(ZeroifyPlayer $victim, ?ZeroifyPlayer $attacker, int $cause, MessageConfiguration $message, MessageConfiguration $deathMessage)
    {
        $this->attacker = $attacker;
        $this->victim = $victim;
        $this->cause = $cause;
        parent::__construct(Zeroify::getInstance()->getEnvironment()->getPlugin());
    }

    /**
     * @return ZeroifyPlayer
     */
    public function getVictim(): ZeroifyPlayer
    {
        return $this->victim;
    }

    /**
     * @param ZeroifyPlayer $victim
     */
    public function setVictim(ZeroifyPlayer $victim): void
    {
        $this->victim = $victim;
    }

    /**
     * @return ZeroifyPlayer|null
     */
    public function getAttacker(): ?ZeroifyPlayer
    {
        return $this->attacker;
    }

    /**
     * @param ZeroifyPlayer|null $attacker
     */
    public function setAttacker(?ZeroifyPlayer $attacker): void
    {
        $this->attacker = $attacker;
    }

    /**
     * @return int
     */
    public function getCause(): int
    {
        return $this->cause;
    }

    /**
     * @param int $cause
     */
    public function setCause(int $cause): void
    {
        $this->cause = $cause;
    }

    /**
     * @param MessageConfiguration $message
     */
    public function setMessage(MessageConfiguration $message): void
    {
        $this->message = $message;
    }

    /**
     * @return MessageConfiguration
     */
    public function getMessage(): MessageConfiguration
    {
        return $this->message;
    }

    /**
     * @return MessageConfiguration
     */
    public function getDeathMessage(): MessageConfiguration
    {
        return $this->deathMessage;
    }

    /**
     * @param MessageConfiguration $deathMessage
     */
    public function setDeathMessage(MessageConfiguration $deathMessage): void
    {
        $this->deathMessage = $deathMessage;
    }
}