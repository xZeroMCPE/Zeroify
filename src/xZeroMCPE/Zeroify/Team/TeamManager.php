<?php


namespace xZeroMCPE\Zeroify\Team;


use xZeroMCPE\Zeroify\Zeroify;

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
}