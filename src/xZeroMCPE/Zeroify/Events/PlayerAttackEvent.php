<?php


namespace xZeroMCPE\Zeroify\Events;


use pocketmine\event\plugin\PluginEvent;
use pocketmine\plugin\Plugin;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class PlayerAttackEvent extends PluginEvent
{
    public ZeroifyPlayer $attacker;
    public ZeroifyPlayer $victim;

    public bool $allowed;

    public function __construct(ZeroifyPlayer $attacker, ZeroifyPlayer $victim, bool $allowed)
    {
        $this->attacker = $attacker;
        $this->victim = $victim;
        $this->allowed = $allowed;
        parent::__construct(Zeroify::getInstance()->getEnvironment()->getPlugin());
    }

    /**
     * @return ZeroifyPlayer
     */
    public function getAttacker(): ZeroifyPlayer
    {
        return $this->attacker;
    }

    /**
     * @param ZeroifyPlayer $attacker
     */
    public function setAttacker(ZeroifyPlayer $attacker): void
    {
        $this->attacker = $attacker;
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
}