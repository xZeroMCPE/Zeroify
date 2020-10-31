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

    public function __construct(Position $position, float $yaw = 0, float $pitch = 0)
    {
        $this->position = $position;
        $this->yaw = $yaw;
        $this->pitch = $pitch;
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

    public function handle(ZeroifyPlayer $player) {
        $player->teleport($this->getPosition(), $this->getYaw(), $this->getPitch());
    }
}