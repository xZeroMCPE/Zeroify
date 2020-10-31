<?php


namespace xZeroMCPE\Zeroify\Observer;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use xZeroMCPE\Zeroify\State\State;
use xZeroMCPE\Zeroify\State\StateType;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class Observer implements Listener
{

    public function onLogin(PlayerLoginEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof ZeroifyPlayer) {
            switch (Zeroify::getInstance()->getConfiguration()->getState()->getState()) {
                case StateType::WAITING:
                    $player->setTeam(Zeroify::getInstance()->getTeamManager()->getDefaultTeam());
                    Zeroify::getInstance()->getConfiguration()->getLobbyPosition()->handle($player);
                    break;

                case StateType::IN_GAME:
                case StateType::ENDED:

                    break;
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof ZeroifyPlayer) {
            Zeroify::getInstance()->getConfiguration()->getLobbyPosition()->handle($player);
        }
    }
}