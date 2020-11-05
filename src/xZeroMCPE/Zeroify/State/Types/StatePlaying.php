<?php


namespace xZeroMCPE\Zeroify\State\Types;


use xZeroMCPE\Zeroify\State\State;

class StatePlaying extends State
{

    public function tick()
    {
        parent::tick();

        foreach ($this->getTeamManager()->getAllPlayers() as $player) {
            $player->sendPopup("Game state: " . $this->getStateManager()->getState()->getName() . "\n" . "Time: " . $this->getTimeConfiguration()->getTimeFormatted());
        }
    }
}