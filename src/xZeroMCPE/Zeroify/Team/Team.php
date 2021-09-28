<?php


namespace xZeroMCPE\Zeroify\Team;


use pocketmine\Player;
use xZeroMCPE\Zeroify\Configuration\PositionConfiguration;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class Team
{
    public string $name;
    public array $players;

    public bool $isDefault;
    public bool $friendlyFire;
    public int $gamemode;
    public bool $playable;
    public PositionConfiguration $position;


    public function __construct(string $name, PositionConfiguration $position, array $players = [], bool $isDefault = false, bool $friendlyFire = false, $gamemode = 0, bool $playable = true)
    {
        $this->name = $name;
        $this->position = $position;
        $this->players = $players;
        $this->isDefault = $isDefault;
        $this->friendlyFire = $friendlyFire;
        $this->gamemode = $gamemode;
        $this->playable = $playable;
    }

    /**
     * @param bool $cap
     * @return string
     */
    public function getName($cap = false): string
    {
        if($cap) {
            return strtoupper($this->name);
        }
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Player[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * @param array $players
     */
    public function setPlayers(array $players): void
    {
        $this->players = $players;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault
     */
    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    /**
     * @return bool
     */
    public function isFriendlyFire(): bool
    {
        return $this->friendlyFire;
    }

    /**
     * @param bool $friendlyFire
     */
    public function setFriendlyFire(bool $friendlyFire): void
    {
        $this->friendlyFire = $friendlyFire;
    }

    /**
     * @return int
     */
    public function getGamemode() : int
    {
        return $this->gamemode;
    }

    /**
     * @param int $gamemode
     */
    public function setGamemode(int $gamemode): void
    {
        $this->gamemode = $gamemode;
    }

    /**
     * @return bool
     */
    public function isPlayable(): bool
    {
        return $this->playable;
    }

    /**
     * @param bool $playable
     */
    public function setPlayable(bool $playable): void
    {
        $this->playable = $playable;
    }

    /**
     * @return PositionConfiguration
     */
    public function getPosition(): PositionConfiguration
    {
        return $this->position;
    }

    /**
     * @param PositionConfiguration $position
     */
    public function setPosition(PositionConfiguration $position): void
    {
        $this->position = $position;
    }

    public function equals(Team $team): bool
    {
        return $team->getName() === $this->getName();
    }

    public function equalsString(string $team): bool
    {
        return strtolower($team) === strtolower($this->getName());
    }

    public function getCount() : int {
        return count($this->players);
    }


    public function alreadyInTest($what) : bool {
        return isset($this->testCount[$what]);
    }

    public function canAttack(ZeroifyPlayer $player, ZeroifyPlayer  $player2): bool
    {
        if($player->isSameTeam($player2)) {
            if($player->getTeam()->isFriendlyFire()) {
                return true;
            }
        }
        return false;
    }

    public function add(ZeroifyPlayer $player): void
    {
        $player->getInventory()->clearAll();
        $player->setHealth($player->getMaxHealth());
        $player->setFood($player->getMaxFood());
        $player->setGamemode($this->getGamemode());
        $this->players[$player->getName()] = $player->getName();
    }

    public function remove(ZeroifyPlayer $player): void
    {
        if(isset($this->players[$player->getName()])) {
            unset($this->players[$player->getName()]);

            Zeroify::getInstance()->getTeamManager()->tick();
        }
    }

    public function init(ZeroifyPlayer $player) : void {
        $player->setTeam($this);
        $player->getInventory()->clearAll();
        $player->setHealth($player->getMaxHealth());
        $player->setFood($player->getMaxFood());
        $player->setGamemode($this->getGamemode());
    }

    public function handleLeave(ZeroifyPlayer $player): void
    {
        $this->remove($player);
    }
}