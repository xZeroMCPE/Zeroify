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

        /*
         * Send out console messages if development mode is on
         */
        if(Zeroify::getInstance()->getEnvironment()->isDevelopment()) {
            $debug = [
                "State" => $this->getStateManager()->hasState() ? $this->getStateManager()->getState()->getName() : "NO_STATE",
                "canTick" => $this->getStateManager()->getState()->canTick()
            ];
           ZeroifyEnvironment::getInstance()->log(implode(" ", $debug));
        }

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

                    if(Zeroify::getInstance()->getEnvironment()->isDevelopment()) {
                        var_dump("not ticking state due to requirements not met");
                    }
                }
            }
        }
    }
}