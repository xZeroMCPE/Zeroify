<?php


namespace xZeroMCPE\Zeroify;


use pocketmine\Player;
use xZeroMCPE\Zeroify\Events\PlayerTeamChangeEvent;
use xZeroMCPE\Zeroify\Team\Team;

class ZeroifyPlayer extends Player
{

    public string $team = "";

    /**
     * @param Team $team
     */
    public function setTeam(Team $team): void
    {
        $ev = new PlayerTeamChangeEvent(Zeroify::getInstance()->getEnvironment()->getPlugin(),
            $this,
            $team->getName(),
            $this->getTeamAsString(),
            $this->isInTeam());
        $ev->call();;

        if (!$ev->isCancelled()) {
            Zeroify::getInstance()->getTeamManager()->getTeam($this->getTeamAsString())->remove($this);
            Zeroify::getInstance()->getTeamManager()->getTeam($ev->getTeam())->add($this);
        }
    }

    public function isInTeam() : bool {
        return $this->team != "";
    }

    public function getTeam() : Team {
        return Zeroify::getInstance()->getTeamManager()->getTeam($this->getTeamAsString());
    }

    public function getTeamAsString() : string {
        return $this->team;
    }

    public function isSameTeam(ZeroifyPlayer $player) : bool {
        return $player->isInTeam() && $this->isInTeam() && $player->getTeam()->equals($this->getTeam());
    }
}