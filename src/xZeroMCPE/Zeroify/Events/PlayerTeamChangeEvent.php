<?php


namespace xZeroMCPE\Zeroify\Events;


use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\plugin\PluginEvent;
use pocketmine\plugin\Plugin;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class PlayerTeamChangeEvent extends PluginEvent implements Cancellable
{
    public ZeroifyPlayer $player;
    public string $team;
    public string $oldTeam;
    public bool $isFirstTime;

    public function __construct(ZeroifyPlayer $player, string $team, string $oldTeam, bool $isFirstTime)
    {
        $this->player = $player;
        $this->team = $team;
        $this->oldTeam = $oldTeam;
        $this->isFirstTime = $isFirstTime;
        parent::__construct(Zeroify::getInstance()->getEnvironment()->getPlugin());
    }

    /**
     * @return string
     */
    public function getOldTeam(): string
    {
        return $this->oldTeam;
    }

    /**
     * @param string $oldTeam
     */
    public function setOldTeam(string $oldTeam): void
    {
        $this->oldTeam = $oldTeam;
    }

    /**
     * @param string $team
     */
    public function setTeam(string $team): void
    {
        $this->team = $team;
    }

    /**
     * @return ZeroifyPlayer
     */
    public function getPlayer(): ZeroifyPlayer
    {
        return $this->player;
    }

    /**
     * @return string
     */
    public function getTeam(): string
    {
        return $this->team;
    }

    /**
     * @return bool
     */
    public function isFirstTime(): bool
    {
        return $this->isFirstTime;
    }

    /**
     * @param bool $isFirstTime
     */
    public function setIsFirstTime(bool $isFirstTime): void
    {
        $this->isFirstTime = $isFirstTime;
    }
}