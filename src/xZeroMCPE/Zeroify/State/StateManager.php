<?php


namespace xZeroMCPE\Zeroify\State;


use xZeroMCPE\Zeroify\Configuration\PositionConfiguration;
use xZeroMCPE\Zeroify\Configuration\TimeConfiguration;
use xZeroMCPE\Zeroify\Events\GameStateChangeEvent;
use xZeroMCPE\Zeroify\State\Types\StateEnding;
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
                var_dump($this->getState()->getName() . " with type of " . StateType::PLAYING);
                $newState = new StatePlaying(StateType::PLAYING, new TimeConfiguration(((60 * 60) * 24) * 5)); // Should be 5 days...
                break;
            case StateType::PLAYING:
                $newState = new StateEnding(StateType::ENDED, new TimeConfiguration(0));
                break;
        }

        if($newState != null) {
            $ev = (new GameStateChangeEvent($this->getState(), $newState));
            $ev->call();
            $this->setState($ev->getNewState());
        }
    }

    public function isState(string $state) {
        return $this->getState()->getName() == $state;
    }
}