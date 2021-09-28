<?php


namespace xZeroMCPE\Zeroify\Team;


use xZeroMCPE\Zeroify\Configuration\MessageConfiguration;
use xZeroMCPE\Zeroify\Game\Game;
use xZeroMCPE\Zeroify\Game\GameType;
use xZeroMCPE\Zeroify\State\StateType;
use xZeroMCPE\Zeroify\Team\Defaults\TeamIdentifiers;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class TeamManager
{

    public array $teams = [];

    public string $defaultTeam = "";

    public Game $game;

    /**
     * TeamManager constructor.
     * Use @see TeamManager::setGame()
     */
    public function __construct() {
        $this->game = new Game();
    }

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

    public function getTeam(string $name) : ? Team {
        if(isset($this->teams[$name])) {
            return $this->teams[$name];
        }
        return null;
    }

    public function hasDefaultTeam() : bool{
        return $this->defaultTeam != "";
    }

    public function removeTeam(string $name) {
        if(isset($this->teams[$name])) {
            unset($this->teams[$name]);
        }
    }

    public function teamExists(string $name) : bool {
        return isset($this->team[$name]);
    }

    public function sendMessageAll(MessageConfiguration $configuration, string $excluding = "") {
        foreach ($this->getAllPlayers() as $player) {
            if($player instanceof ZeroifyPlayer) {
                if($excluding != "") {
                    if($player->getTeam()->equalsString($excluding)) continue;
                }
                $configuration->handle($player);
            }
        }
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

    public function tick() {
        if(Zeroify::getInstance()->getStateManager()->getState()->getName() == StateType::PLAYING) {
            $playableTeams = $this->getPlayableTeams();

            foreach ($playableTeams as $team => $count) {
                if($count != 0) continue;
                unset($playableTeams[$team]);

            }
            if(count($playableTeams) == 1) {
                Zeroify::getInstance()->getStateManager()->endGame(array_keys($playableTeams)[0]);
            }
        }
    }

    /**
     * @return array Team[]
     */
    public function getPlayableTeams() : array {

        $list = [];
        foreach ($this->teams as $team) {
            $team = $this->getTeam($team);
            if($team->isPlayable()) {
                $list[$team->getName()] = count($team->getPlayers());
            }
        }
        return $list;
    }

    public function pickTeams() {
        if($this->getGame()->getType() != GameType::DEFAULT) {


           switch ($this->getGame()->getType()) {
               case GameType::SOLO:
                       foreach ($this->getAllPlayers() as $index => $player) {
                           $player->setTeam($this->getTeam($this->getGame()->getTeams()[$index]));
                       }
                   break;

               case GameType::DUO:
               case GameType::SQUAD:
                       foreach ($this->getGame()->getTeams() as $team) {
                           $team = $this->getTeam($team);

                           if($team->getCount() != $this->getGame()->getMaxForTeam($team)) {
                               foreach ($this->getAllPlayers(TeamIdentifiers::PLAYER) as $player) {
                                   $player->setTeam($this->getTeam($team->getName()));
                               }
                           }
                       }
                   break;
           }
        }
    }

    /*
     * This checks teams, and finishes game as it needs to
     */

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * @param Game $game
     * @throws \Exception
     */
    public function setGame(Game $game): void
    {
        $this->game = $game;
        Zeroify::getInstance()->deploy(false);
    }
}