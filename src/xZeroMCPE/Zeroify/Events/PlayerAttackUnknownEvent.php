<?php


namespace xZeroMCPE\Zeroify\Events;


use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\plugin\Plugin;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

/**
 * When a player gets attack by something
 * that did not originate from an enemy/other player
 */
class PlayerAttackUnknownEvent extends PluginEvent implements Cancellable
{

    public ZeroifyPlayer $victim;
    public int $cause;
    public bool $allowed;

    public function __construct(ZeroifyPlayer $victim, int $cause, bool $allowed)
    {
        $this->victim = $victim;
        $this->cause = $cause;
        $this->allowed = $allowed;
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
     * @return bool
     */
    public function isAllowed(): bool
    {
        return $this->allowed;
    }

    /**
     * @param bool $allowed
     */
    public function setAllowed(bool $allowed): void
    {
        $this->allowed = $allowed;
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
}