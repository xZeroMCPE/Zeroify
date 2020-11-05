<?php


namespace xZeroMCPE\Zeroify\Configuration;


use pocketmine\level\Position;
use xZeroMCPE\Zeroify\State\State;

class Configuration
{

    public PositionConfiguration $lobbyPosition;

    public $basicProtection = true;

    /**
     * Configuration constructor.
     * @param PositionConfiguration $lobbyPosition
     * @param bool $basicProtection Enables basic protection for the framework
     * (As in, stops basic actions on waiting state, i.e; block breaking, placing, etc.
     */
    public function __construct(PositionConfiguration $lobbyPosition, $basicProtection = true)
    {
        $this->lobbyPosition = $lobbyPosition;
        $this->basicProtection = $basicProtection;
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

    public function enableBasicProtection() {
        $this->basicProtection = true;
    }

    public function disableBasicProtection() {
        $this->basicProtection = false;
    }

    public function hasBasicProtection() : bool {
        return $this->basicProtection;
    }
}