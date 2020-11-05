<?php


namespace xZeroMCPE\Zeroify\Team;


use xZeroMCPE\Zeroify\Configuration\MessageConfiguration;
use xZeroMCPE\Zeroify\Team\Defaults\TeamIdentifiers;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class TeamManager
{

    public array $teams = [];

    public string $defaultTeam = "";


    public function addTeam(Team $team) {
        $this->teams[$team->getName()] = $team;

        if($team->isDefault()) {
            $this->defaultTeam = $team->getName();
        }
    }

    /**
     * @return Team
     */
    public function getDefaultTeam(): Team
    {
        return $this->getTeam($this->defaultTeam);
    }

    /**
     * @param Team $defaultTeam
     */
    public function setDefaultTeam(Team $defaultTeam): void
    {
        $this->defaultTeam = $defaultTeam->getName();
    }

    public function hasDefaultTeam() : bool{
        return $this->defaultTeam != "";
    }

    public function removeTeam(string $name) {
        if(isset($this->teams[$name])) {
            unset($this->teams[$name]);
        }
    }

    public function getTeam(string $name) : ? Team {
        if(isset($this->teams[$name])) {
            return $this->teams[$name];
        }
        return null;
    }

    /**
     * @param string $team
     * @see TeamIdentifiers::ALL
     * @see TeamIdentifiers::PLAYER
     * @see TeamIdentifiers::SPECTATOR
     * @return ZeroifyPlayer[]
     */
    public function getAllPlayers(string $team = TeamIdentifiers::ALL) : array {
        $players = [];

        foreach (Zeroify::getInstance()->getEnvironment()->getPlugin()->getServer()->getOnlinePlayers() as $player) {
            if($player instanceof ZeroifyPlayer) {
                if($team == TeamIdentifiers::ALL) {
                    $players[] = $player;
                } else {
                    if($player->isInTeam() && $player->getTeam()->equalsString($team)) {
                        $players[] = $player;
                    }
                }
            }
        }
        return $players;
    }

    public function sendMessageAll(MessageConfiguration $configuration) {
        foreach ($this->getAllPlayers() as $player) {
            if($player instanceof ZeroifyPlayer) {
                $configuration->handle($player);
            }
        }
    }
}