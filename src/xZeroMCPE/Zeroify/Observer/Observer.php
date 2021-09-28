<?php


namespace xZeroMCPE\Zeroify\Observer;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;
use xZeroMCPE\Zeroify\Configuration\Configuration;
use xZeroMCPE\Zeroify\Configuration\MessageConfiguration;
use xZeroMCPE\Zeroify\Events\PlayerAttackEvent;
use xZeroMCPE\Zeroify\Events\PlayerAttackUnknownEvent;
use xZeroMCPE\Zeroify\Events\PlayerJoinGameEvent;
use xZeroMCPE\Zeroify\Events\PlayerQuitGameEvent;
use xZeroMCPE\Zeroify\State\State;
use xZeroMCPE\Zeroify\State\StateType;
use xZeroMCPE\Zeroify\Team\TeamManager;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class Observer implements Listener
{

    public static Observer $instance;

    public function __construct()
    {
        self::$instance = $this;
    }

    /**
     * @return Observer
     */
    public static function getInstance(): Observer
    {
        return self::$instance;
    }

    /*
     * When a player tries to join when a game is ended/already started
     */
    public $cannotJoinGameCallback = null;


    public function getConfiguration() :  Configuration {
        return Zeroify::getInstance()->getConfiguration();
    }

    public function getTeamManager() : TeamManager {
        return Zeroify::getInstance()->getTeamManager();
    }

    public function onCreation(PlayerCreationEvent  $event) {
        $event->setPlayerClass(ZeroifyPlayer::class);
    }


    /**
     * @return callable|null
     */
    public function getCannotJoinGameCallback()
    {
        return $this->cannotJoinGameCallback;
    }

    /**
     * @param callable $cannotJoinGameCallback
     */
    public function setCannotJoinGameCallback(callable $cannotJoinGameCallback): void
    {
        $this->cannotJoinGameCallback = $cannotJoinGameCallback;
    }

    public function onLogin(PlayerLoginEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof ZeroifyPlayer) {
            switch (Zeroify::getInstance()->getStateManager()->getState()->getName()) {
                case StateType::WAITING:
                    if(Zeroify::getInstance()->get)
                    $player->setTeam(Zeroify::getInstance()->getTeamManager()->getDefaultTeam());

                    Zeroify::getInstance()->getConfiguration()->getLobbyPosition()->handle($player);
                    break;

                case StateType::PLAYING:
                case StateType::ENDED:
                    if(!is_null($this->getCannotJoinGameCallback())) {
                        $callback = $this->getCannotJoinGameCallback();
                        $callback($player);
                    }
                    break;
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();

        if ($player instanceof ZeroifyPlayer) {
            Zeroify::getInstance()->getConfiguration()->getLobbyPosition()->handle($player);
            $ev = new PlayerJoinGameEvent($player, new MessageConfiguration(
                MessageConfiguration::format("Join",
                    TextFormat::YELLOW,
                    TextFormat::LIGHT_PURPLE . $player->getDisplayName() . TextFormat::YELLOW . " joined!"
                ),
                TextFormat::GOLD  . Zeroify::getInstance()->getName(),
                TextFormat::GRAY . "Starting soon..."
            ));
            $ev->call();
            $event->setJoinMessage($ev->getMessage()->getMessage());

            $message = new MessageConfiguration(MessageConfiguration::format(
                "Team",
                TextFormat::GRAY,
                "You've joined the " . TextFormat::LIGHT_PURPLE . $player->getTeam()->getName(true) . TextFormat::GRAY . " team!"
            ));
            $message->handle($player);
        }
    }

    public function onQuit(PlayerQuitEvent  $event)
    {

        $player = $event->getPlayer();

        if ($player instanceof ZeroifyPlayer) {
            Zeroify::getInstance()->getConfiguration()->getLobbyPosition()->handle($player);
            $ev = new PlayerQuitGameEvent($player, new MessageConfiguration(
                MessageConfiguration::format("Quit",
                    TextFormat::RED,
                    TextFormat::LIGHT_PURPLE . $player->getDisplayName() . TextFormat::RED . " left!"
                ),
                "",
                ""
            ));
            $ev->call();
            $event->setQuitMessage($ev->getMessage()->getMessage());

            switch (Zeroify::getInstance()->getStateManager()->getState()->getName()) {
                case StateType::PLAYING:
                    $ev = new PlayerQuitGameEvent($player, MessageConfiguration::createEmpty());
                    $ev->call();
                    $player->getTeam()->handleLeave($player);
                    break;
            }
        }
    }
    public function onDamage(EntityDamageEvent $event) {

        $player = $event->getEntity();

        if($player instanceof ZeroifyPlayer) {
            if($event instanceof EntityDamageByEntityEvent) {
                $damager = $event->getDamager();

                if($damager instanceof ZeroifyPlayer) {
                    if(!Zeroify::getInstance()->getStateManager()->getState()->canAttack($damager, $player)) {
                        $event->setCancelled();
                    } else {
                        if($player->getHealth() - $event->getBaseDamage() <= 0) {
                            Zeroify::getInstance()->getStateManager()->getState()->handleDeath($player, $event);
                        }
                    }
                }
            } else {
                if(!Zeroify::getInstance()->getStateManager()->getState()->canDamage($player, $event->getCause())) {
                    $event->setCancelled();
                } else {
                    if($player->getHealth() - $event->getBaseDamage() <= 0) {
                        Zeroify::getInstance()->getStateManager()->getState()->handleDeath($player, $event);
                    }
                }
            }
        }
    }

    /**
     * Disables, if @see Configuration::$basicProtection is on
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event)
    {
        if (Zeroify::getInstance()->getStateManager()->isState(StateType::WAITING)) {
            if($this->getConfiguration()->hasBasicProtection()) {
                $event->setCancelled();
            }
        }
    }

    /**
     * Disables, if @param BlockPlaceEvent $event
     * @see Configuration::$basicProtection is on
     */
    public function onPlace(BlockPlaceEvent  $event) {
        if (Zeroify::getInstance()->getStateManager()->isState(StateType::WAITING)) {
            if($this->getConfiguration()->hasBasicProtection()) {
                $event->setCancelled();
            }
        }
    }

    /**
     * Disables, if @param PlayerAttackEvent $event
     * @see Configuration::$basicProtection is on
     */
    public function onAttack(PlayerAttackEvent  $event) {
        if (Zeroify::getInstance()->getStateManager()->isState(StateType::WAITING)) {
            if($this->getConfiguration()->hasBasicProtection()) {
                $event->setCancelled();
            }
        }
    }

    /**
     * Disables, if @param PlayerAttackUnknownEvent $event
     * @see Configuration::$basicProtection is on
     */
    public function onAttackOther(PlayerAttackUnknownEvent  $event) {
        if (Zeroify::getInstance()->getStateManager()->isState(StateType::WAITING)) {
            if($this->getConfiguration()->hasBasicProtection()) {
                $event->setCancelled();
            }
        }
    }

    public function onChat(PlayerChatEvent  $event) {

        $player = $event->getPlayer();

        if($player instanceof ZeroifyPlayer) {
            $format = str_replace(
                [
                    "@team",
                    "@xyz",
                    "@holding",
                ],
                [
                    $player->getTeam()->getName(true),
                    $player->getX() . ", " . $player->getY() . ", " . $player->getZ(),
                    $player->getInventory()->getItemInHand()->isNull() ? "Nothing" : $player->getInventory()->getItemInHand()->getName() . " x" . $player->getInventory()->getItemInHand()->getCount()
                ], $event->getMessage()
            );
            $event->setMessage($format);
        }
    }
}