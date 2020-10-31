<?php


namespace xZeroMCPE\Zeroify\State;


use xZeroMCPE\Zeroify\Configuration\PositionConfiguration;
use xZeroMCPE\Zeroify\Configuration\TimeConfiguration;
use xZeroMCPE\Zeroify\Events\GameStateChangeEvent;
use xZeroMCPE\Zeroify\State\Types\StatePlaying;
use xZeroMCPE\Zeroify\State\Types\StateWaiting;
use xZeroMCPE\Zeroify\Zeroify;

class StateManager
{
    public ?State $state = null;


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

    public function hasState() : bool {
        return $this->state !== null;
    }

    public function switchState() {

        $newState = null;

        switch ($this->getState()->getName()) {
            case StateType::WAITING:
                $newState = new StateWaiting(StateType::WAITING, new TimeConfiguration(0));
                break;
            case StateType::PLAYING:
                $newState = new StatePlaying(StateType::PLAYING, new TimeConfiguration(0));
                break;
        }

        if($newState != null) {
            $ev = (new GameStateChangeEvent(Zeroify::getInstance()->getEnvironment()->getPlugin(), $this->getState(), $newState));
            $ev->call();

            $this->setState($ev->getNewState());
        }
    }
}