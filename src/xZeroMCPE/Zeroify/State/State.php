<?php


namespace xZeroMCPE\Zeroify\State;


use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\utils\TextFormat;
use xZeroMCPE\Zeroify\Configuration\Configuration;
use xZeroMCPE\Zeroify\Configuration\MessageConfiguration;
use xZeroMCPE\Zeroify\Configuration\PositionConfiguration;
use xZeroMCPE\Zeroify\Configuration\TimeConfiguration;
use xZeroMCPE\Zeroify\Events\GameStateChangeEvent;
use xZeroMCPE\Zeroify\Events\PlayerAttackEvent;
use xZeroMCPE\Zeroify\Events\PlayerAttackUnknownEvent;
use xZeroMCPE\Zeroify\Events\PlayerDiesEvent;
use xZeroMCPE\Zeroify\Team\Defaults\SpectatorTeam;
use xZeroMCPE\Zeroify\Team\Defaults\TeamIdentifiers;
use xZeroMCPE\Zeroify\Team\TeamManager;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class State
{
    public string $name;
    public TimeConfiguration $timeConfiguration;

    /**
     * If somehow the @see State::canTick() is set to false,
     * but a previous timer was running, this will be true,
     * causing the timer to reset.
     */
    public bool $wasTicking = false;

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

    /**
     * This is when 1 player attacks another player
     * @param ZeroifyPlayer $attacker
     * @param ZeroifyPlayer $victim
     * @return bool
     */
    public function canAttack(ZeroifyPlayer $attacker, ZeroifyPlayer $victim) : bool {

        $event = new PlayerAttackEvent($attacker, $victim, $this->getName() == StateType::WAITING ? false : true);
        return $event->isAllowed();
    }

    /**
     * This is for when the player is getting damage from non-players,
     * i.e; fall damage, fire, etc.
     * @param ZeroifyPlayer $victim
     * @param int $cause
     * @return bool
     */
    public function canDamage(ZeroifyPlayer $victim, int $cause) : bool {
        $event = new PlayerAttackUnknownEvent($victim, $cause, $this->getName() == StateType::WAITING ? false : true);
        return $event->isAllowed();
    }

    public function getStateManager() : StateManager {
        return Zeroify::getInstance()->getStateManager();
    }

    public function getTeamManager() : TeamManager {
        return Zeroify::getInstance()->getTeamManager();
    }

    public function getConfiguration() : Configuration {
        return Zeroify::getInstance()->getConfiguration();
    }

    public function handleDeath(ZeroifyPlayer $victim, EntityDamageEvent $event) {

        $ev = null;

        $message = new MessageConfiguration(MessageConfiguration::format(
            "Death",
            TextFormat::RED,
            ""
        ),
        TextFormat::RED . "You died,", TextFormat::GOLD . "Ouch");
        $message2 = new MessageConfiguration(MessageConfiguration::format(
            "Kill",
            TextFormat::RED,
            ""
        ),
            "", "");

        if($event instanceof EntityDamageByEntityEvent) {
            $attacker = $event->getDamager();

            if($attacker instanceof ZeroifyPlayer) {
                $message->setMessage(TextFormat::RED . "You were killed by " . TextFormat::LIGHT_PURPLE . $attacker->getName() . TextFormat::RED . " better luck next time!");
                $message2->setMessage(TextFormat::LIGHT_PURPLE . $victim->getName() . TextFormat::RED . " got killed by " . TextFormat::DARK_RED . $attacker->getName());
                $ev = new PlayerDiesEvent($victim, $attacker, $event->getCause(), $message, $message2);

            }
        } else {
            $message->setMessage(TextFormat::RED . "You died, better luck next time?");
            $message2->setMessage(TextFormat::LIGHT_PURPLE . $victim->getName() . TextFormat::RED . " died");
            $ev = new PlayerDiesEvent($victim, null, $event->getCause(), $message, $message2);
        }
        if(!is_null($ev)) {
            $ev->call();
            $victim->setTeam($this->getTeamManager()->getTeam(TeamIdentifiers::SPECTATOR));

            /*
             * Broadcast killed message now.
             */
            $this->getTeamManager()->sendMessageAll($message2);
        }
    }

    /*
     * When the state is being loaded for the first time
     */
    public function init() {
        Zeroify::getInstance()->getEnvironment()->log("State: " . $this->getName() . " is init(ing)");
    }

    /*
     * This gets called every second,
     * So you may use this for your task needs.
     */
    public function tick() {
        Zeroify::getInstance()->getEnvironment()->log("State: " . $this->getName() . " is ticking! " . gmdate("H:i:s", (float) microtime(true)));
    }

    /**
     * If this returns false, the @see StateTick::onRun()
     * will not call the @see TimeConfiguration::subtract()
     */
    public function canTick() : bool {
        return true;
    }

    /*
     * Called when a state is finished ticking...
     */
    public function finished() {
        Zeroify::getInstance()->getEnvironment()->log("State: " . $this->getName() . " has finished");
    }

    public function switch() {
        $this->getStateManager()->switchState();
    }
}