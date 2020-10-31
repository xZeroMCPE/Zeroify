<?php


namespace xZeroMCPE\Zeroify\State;


use pocketmine\scheduler\Task;
use xZeroMCPE\Zeroify\Zeroify;

class StateTick extends Task
{
    public function onRun(int $currentTick)
    {
        if (Zeroify::getInstance()->getStateManager()->hasState()) {
            if (Zeroify::getInstance()->getStateManager()->getState()->getTimeConfiguration()->isFinished()) {
                Zeroify::getInstance()->getStateManager()->getState()->finished();
                Zeroify::getInstance()->getStateManager()->switchState();
            } else {
                Zeroify::getInstance()->getStateManager()->getState()->getTimeConfiguration()->subtract();
                Zeroify::getInstance()->getStateManager()->getState()->tick();
            }
        }
    }
}