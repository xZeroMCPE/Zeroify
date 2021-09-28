<?php


namespace xZeroMCPE\Zeroify\Game;


use xZeroMCPE\Zeroify\Team\Team;

class Game
{

    public string $type;
    public array $teams = [];
    public array $teamsMaximum;

    /**]
     * Game constructor.
     * @param string $type
     * @param array|string[] $teams Key should be the amount of players must be in their, value being the team
     */
    public function __construct(string $type = GameType::DEFAULT, array $teams = GameType::TEAMS)
    {
        $this->type = $type;
        $this->teamsMaximum = $teams;
        foreach ($teams as $name => $count) {
            $this->teams[] = $name;
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array|string[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @param array|string[] $teams
     */
    public function setTeams(array $teams): void
    {
        $this->teams = $teams;
    }

    public function getMaxForTeam(Team $team): int
    {
        return isset($this->teamsMaximum[$team->getName()]) ? $this->teamsMaximum[$team->getName()] : 1;
    }
}