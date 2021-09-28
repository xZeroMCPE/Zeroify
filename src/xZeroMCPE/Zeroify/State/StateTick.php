<?php


namespace xZeroMCPE\Zeroify\State;


use pocketmine\scheduler\Task;
use xZeroMCPE\Zeroify\Configuration\Configuration;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyEnvironment;

class StateTick extends Task
{
    public function getStateManager() : StateManager {
        return Zeroify::getInstance()->getStateManager();
    }

    public function getConfiguration() : Configuration {
        return Zeroify::getInstance()->getConfiguration();
    }

    public function onRun(int $currentTick)
    {

        if (Zeroify::getInstance()->getStateManager()->hasState()) {
            if ($this->getStateManager()->getState()->getTimeConfiguration()->isFinished()) {
                $this->getStateManager()->getState()->finished();
                $this->getStateManager()->switchState();
            } else {

                if($this->getStateManager()->getState()->canTick()) {
                    $this->getStateManager()->getState()->getTimeConfiguration()->subtract();
                    $this->getStateManager()->getState()->tick();
                } else {
                    if($this->getStateManager()->getState()->wasTicking) {
                        $this->getStateManager()->getState()->getTimeConfiguration()->reset();
                        $this->getStateManager()->getState()->wasTicking = false;
                    }
                }
            }
        }
    }
}