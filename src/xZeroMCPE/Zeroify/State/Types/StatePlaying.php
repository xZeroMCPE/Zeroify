<?php


namespace xZeroMCPE\Zeroify\State\Types;


use xZeroMCPE\Zeroify\State\State;

class StatePlaying extends State
{

    public function tick()
    {
        parent::tick();

        foreach ($this->getTeamManager()->getAllPlayers() as $player) {
            $player->sendPopup("DEBUG" . "\n" . "Game state: " . $this->getStateManager()->getState()->getName() . "\n" . "Time: " . $this->getTimeConfiguration()->getTimeFormatted() . "\n" . "Team: " . $player->getTeam()->getName() . " with count: " . $player->getTeam()->getCount());
        }
    }
}