<?php


namespace xZeroMCPE\Zeroify\State;


use xZeroMCPE\Zeroify\Configuration\PositionConfiguration;
use xZeroMCPE\Zeroify\Configuration\TimeConfiguration;
use xZeroMCPE\Zeroify\Events\GameStateChangeEvent;
use xZeroMCPE\Zeroify\Zeroify;

class State
{
    public string $name;
    public TimeConfiguration $timeConfiguration;

    /**
     * State constructor.
     * @param string $name The name of the state
     * @param TimeConfiguration $timeConfiguration A timer, or sorts
     */
    public function __construct(string $name, TimeConfiguration $timeConfiguration)
    {
        $this->name = $name;
        $this->timeConfiguration = $timeConfiguration;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return State
     */
    public function setName(string $name): State
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return TimeConfiguration
     */
    public function getTimeConfiguration(): TimeConfiguration
    {
        return $this->timeConfiguration;
    }

    /**
     * @param TimeConfiguration $timeConfiguration
     */
    public function setTimeConfiguration(TimeConfiguration $timeConfiguration): void
    {
        $this->timeConfiguration = $timeConfiguration;
    }


    public function init() {
        Zeroify::getInstance()->getEnvironment()->log("State: " . $this->getName() . " is init(ing)");
    }

    public function tick() {
        Zeroify::getInstance()->getEnvironment()->log("State: " . $this->getName() . " is ticking! " . gmdate("H:i:s", (float) microtime(true)));
    }

    /*
     * Called when a state is finished ticking...
     */
    public function finished() {
        Zeroify::getInstance()->getEnvironment()->log("State: " . $this->getName() . " has finished");
    }
}