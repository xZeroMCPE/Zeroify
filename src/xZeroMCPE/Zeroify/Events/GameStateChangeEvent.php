<?php


namespace xZeroMCPE\Zeroify\Events;


use pocketmine\event\plugin\PluginEvent;
use pocketmine\plugin\Plugin;
use xZeroMCPE\Zeroify\State\State;
use xZeroMCPE\Zeroify\Zeroify;

class GameStateChangeEvent extends PluginEvent
{
    public State $state;
    public State $newState;
    public function __construct(State $state, State $newState)
    {
        $this->state = $state;
        $this->newState = $newState;
        parent::__construct(Zeroify::getInstance()->getEnvironment()->getPlugin());
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @param State $state
     */
    public function setState(State $state): void
    {
        $this->state = $state;
    }

    /**
     * @return State
     */
    public function getNewState(): State
    {
        return $this->newState;
    }

    /**
     * @param State $newState
     */
    public function setNewState(State $newState): void
    {
        $this->newState = $newState;
    }



}