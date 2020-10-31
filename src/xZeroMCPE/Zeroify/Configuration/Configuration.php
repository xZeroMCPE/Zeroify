<?php


namespace xZeroMCPE\Zeroify\Configuration;


use pocketmine\level\Position;
use xZeroMCPE\Zeroify\State\State;

class Configuration
{

    public PositionConfiguration $lobbyPosition;

    public function __construct(PositionConfiguration $lobbyPosition)
    {
        $this->lobbyPosition = $lobbyPosition;
    }

    /**
     * @return PositionConfiguration
     */
    public function getLobbyPosition(): PositionConfiguration
    {
        return $this->lobbyPosition;
    }

    /**
     * @param PositionConfiguration $lobbyPosition
     */
    public function setLobbyPosition(PositionConfiguration $lobbyPosition): void
    {
        $this->lobbyPosition = $lobbyPosition;
    }
}