<?php


namespace xZeroMCPE\Zeroify\State\Types;


use pocketmine\utils\TextFormat;
use xZeroMCPE\Zeroify\Configuration\MessageConfiguration;
use xZeroMCPE\Zeroify\State\State;

class StateEnding extends State
{

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->getTeamManager()->sendMessageAll(new MessageConfiguration(MessageConfiguration::format(
            "Game",
            TextFormat::GREEN,
            TextFormat::BOLD . "GG," . TextFormat::RESET . TextFormat::GREEN . TextFormat::LIGHT_PURPLE . " " . $this->getTeamManager()->getTeam($this->getStateManager()->getWinner())->getWinners() . TextFormat::GREEN . " has won this match!"
        )));
    }
}