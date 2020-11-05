<?php


namespace xZeroMCPE\Zeroify\Configuration;


use pocketmine\utils\TextFormat;
use xZeroMCPE\Zeroify\Zeroify;
use xZeroMCPE\Zeroify\ZeroifyPlayer;

class MessageConfiguration
{
    public string $message;
    public string $title;
    public string $subTitle;

    const BROADCAST_PLAYER_ONLY = 1; // Sends the message to the player only
    const BROADCAST_ALL = 2; // Sends the message to everyone on the server
    public function __construct(string $message, string $title = "", string $subTitle = "") {
        $this->message = $message;
        $this->title = $title;
        $this->subTitle = $subTitle;
    }

    public static function createEmpty() : MessageConfiguration {
        return new MessageConfiguration("", "", "");
    }

    /**
     * Didn't feel like typing it multiple times... so
     * this method helps, turns it into something like this
     * Hello> Your message here!
     * @param string $prefix
     * @param string $color
     * @param string $message
     * @return string
     */
    public static function format(string $prefix, string $color, string $message): string
    {
        return TextFormat::BLUE . TextFormat::BOLD . $prefix . "> " . TextFormat::RESET . $color . $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSubTitle(): string
    {
        return $this->subTitle;
    }

    /**
     * @param string $subTitle
     */
    public function setSubTitle(string $subTitle): void
    {
        $this->subTitle = $subTitle;
    }

    /**
     * Handles setting the message to the player,
     * if
     * @param ZeroifyPlayer $player
     * @param int $type
     * @param bool $titleOnly @see MessageConfiguration::getTitle()
     * or @see MessageConfiguration::getSubTitle()
     * is empty, it will not send it
     */
    public function handle(ZeroifyPlayer  $player, int $type = MessageConfiguration::BROADCAST_PLAYER_ONLY, $titleOnly = false) {

        if(!$this->title) {
            if($type == MessageConfiguration::BROADCAST_PLAYER_ONLY) {
                $player->sendMessage($this->getMessage());
            } else {
                foreach (Zeroify::getInstance()->getTeamManager()->getAllPlayers() as $p) {
                    $p->sendMessage($this->getMessage());
                }
            }
        }

        if(strlen($this->getTitle()) != 0 && strlen($this->getSubTitle()) != 0) {
            $player->addTitle($this->getTitle(), $this->getSubTitle());
        }
    }
}