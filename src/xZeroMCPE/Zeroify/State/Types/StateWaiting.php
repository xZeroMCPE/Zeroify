<?php


namespace xZeroMCPE\Zeroify\State\Types;


use pocketmine\utils\TextFormat;
use xZeroMCPE\Zeroify\Configuration\MessageConfiguration;
use xZeroMCPE\Zeroify\Events\GameStartEvent;
use xZeroMCPE\Zeroify\State\State;
use xZeroMCPE\Zeroify\State\StateType;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class StateWaiting extends State
{

    public function init()
    {
        parent::init();

        /*
         * Teleport them to the game playing world, by accessing their team position
         */
        foreach (Zeroify::getInstance()->getEnvironment()->getPlugin()->getServer()->getOnlinePlayers() as $player) {
            if($player instanceof ZeroifyPlayer) {
                $player->getTeam()->getPosition()->handle($player);
            }
        }

        /**
         * Call the @see GameStartEvent
         */
        $event = new GameStartEvent(
            Zeroify::getInstance()->getEnvironment()->getPlugin(),
            new MessageConfiguration(
                TextFormat::BLUE . "Game>" . TextFormat::RESET . TextFormat::GREEN . "The game has started, good luck!",
                TextFormat::BOLD . TextFormat::GREEN . "Game started",
                TextFormat::GOLD ."Good luck"
            ));
        $event->call();

        /*
         * Send them the custom message,
         * and also with the custom title too
         */
        foreach (Zeroify::getInstance()->getEnvironment()->getPlugin()->getServer()->getOnlinePlayers() as $player) {
            if ($player instanceof ZeroifyPlayer) {
                $player->sendMessage($event->getMessage()->getMessage());
                $player->addTitle($event->getMessage()->getTitle(), $event->getMessage()->getSubTitle());
            }
        }
    }

    public function tick()
    {
        parent::tick();
        if($this->getTimeConfiguration()->getTime() <= 30) {

        }
    }
}