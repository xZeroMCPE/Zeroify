<?php


namespace xZeroMCPE\Zeroify\Configuration;


use pocketmine\level\Position;
use pocketmine\Server;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class PositionConfiguration
{
    public Position $position;
    public float $yaw = 0;
    public float $pitch = 0;

    public bool $noPosition;

    public function __construct(Position $position, float $yaw = 0, float $pitch = 0, bool $noPosition = false)
    {
        $this->position = $position;
        $this->yaw = $yaw;
        $this->pitch = $pitch;
        $this->noPosition = $noPosition; //Useful if you want them to teleport to their self (for spectators? on death)
    }

    /*
     * Creates an instance of itself, with a valid world
     * **WARNING** this is just for testing, it will
     * by default use the server default world..
     */
    public static function createFromNothing() : PositionConfiguration {
        return new PositionConfiguration(new Position(0, 0, 0, Server::getInstance()->getDefaultLevel()), 0, 0);
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * @return float|int
     */
    public function getPitch()
    {
        return $this->pitch;
    }

    /**
     * @return float|int
     */
    public function getYaw()
    {
        return $this->yaw;
    }

    /**
     * @return bool
     */
    public function isNoPosition(): bool
    {
        return $this->noPosition;
    }

    /**
     * @param bool $noPosition
     */
    public function setNoPosition(bool $noPosition): void
    {
        $this->noPosition = $noPosition;
    }

    public function handle(ZeroifyPlayer $player) {
       if(!$this->isNoPosition()) {
           $player->teleport($this->getPosition(), $this->getYaw(), $this->getPitch());
       } else {
           $player->teleport($player->getPosition(), $this->getYaw(), $this->getPitch());
       }
    }
}