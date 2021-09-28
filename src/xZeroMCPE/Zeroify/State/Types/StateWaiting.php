<?php


namespace xZeroMCPE\Zeroify\State\Types;


use pocketmine\level\sound\PopSound;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\utils\TextFormat;
use xZeroMCPE\Zeroify\Configuration\MessageConfiguration;
use xZeroMCPE\Zeroify\Events\GameStartEvent;
use xZeroMCPE\Zeroify\State\State;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class StateWaiting extends State
{

    public function init()
    {
        parent::init();
    }

    public function canTick(): bool
    {
        return count($this->getTeamManager()->getAllPlayers()) >= Zeroify::getInstance()->getMinimum();
    }

    public function tick()
    {
        parent::tick();

        if (!$this->getTimeConfiguration()->isFinished()) {
            if ($this->getTimeConfiguration()->getTime() <= 30) {

                if($this->getTimeConfiguration()->getTime() > 10) {
                    foreach ($this->getTeamManager()->getAllPlayers() as $player) {
                        $player->sendPopup(TextFormat::YELLOW . "The game is starting in " . TextFormat::RED . $this->getTimeConfiguration()->getTime() . "s");
                    }
                }

                if ($this->getTimeConfiguration()->getTime() <= 10) {
                    Zeroify::getInstance()->getTeamManager()->sendMessageAll(new MessageConfiguration(
                        "",
                        TextFormat::GREEN . "Starting", TextFormat::RED . $this->getTimeConfiguration()->getTime() . "s"
                    ));
                    foreach ($this->getTeamManager()->getAllPlayers() as $player) {
                        $player->getLevel()->addSound(new PopSound($player));
                    }
                }
            }
        }
    }

    public function finished()
    {

        $this->getTeamManager()->pickTeams();;

        /*
        * Teleport them to the game playing world, by accessing their team position
        */
        foreach ($this->getTeamManager()->getAllPlayers() as $player) {
            $player->getTeam()->getPosition()->handle($player);
        }

        /**
         * Call the @see GameStartEvent
         */
        $event = new GameStartEvent(
            new MessageConfiguration(
                MessageConfiguration::format(
                    "Game",
                    TextFormat::GREEN,
                    "The game has started, good luck!"
                ),
                TextFormat::BOLD . TextFormat::GREEN . "Game Started",
                TextFormat::GOLD . "Good luck"
            ));
        $event->call();

        /*
         * Send them the custom message,
         * and also with the custom title too
         * And show them that guardian effect thingy
         */
        foreach (Zeroify::getInstance()->getEnvironment()->getPlugin()->getServer()->getOnlinePlayers() as $player) {
            if ($player instanceof ZeroifyPlayer) {
                $event->getMessage()->handle($player);
                $pk = new LevelEventPacket();
                $pk->evid = LevelEventPacket::EVENT_GUARDIAN_CURSE;
                $pk->position = $player;
                $pk->data = 0;
                $player->dataPacket($pk);
            }
        }
    }
}