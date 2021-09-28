<?php


namespace xZeroMCPE\Zeroify;


use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use xZeroMCPE\Zeroify\Configuration\Configuration;
use xZeroMCPE\Zeroify\Configuration\PositionConfiguration;
use xZeroMCPE\Zeroify\Configuration\TimeConfiguration;
use xZeroMCPE\Zeroify\Game\GameType;
use xZeroMCPE\Zeroify\Observer\Observer;
use xZeroMCPE\Zeroify\State\StateManager;
use xZeroMCPE\Zeroify\State\StateTick;
use xZeroMCPE\Zeroify\State\StateType;
use xZeroMCPE\Zeroify\State\Types\StateWaiting;
use xZeroMCPE\Zeroify\Team\Defaults\PlayerTeam;
use xZeroMCPE\Zeroify\Team\Defaults\SpectatorTeam;
use xZeroMCPE\Zeroify\Team\Defaults\TeamIdentifiers;
use xZeroMCPE\Zeroify\Team\TeamManager;

class Zeroify
{
    public ZeroifyEnvironment $environment;
    public static Zeroify $instance;

    public string $name;
    public int $minimum;
    public int $maximum;

    public TeamManager $teamManager;
    public Configuration $configuration;
    public StateManager  $stateManager;

    /**
     * Zeroify constructor.
     * @param string $name The name of the Minigame
     * @param int $minimum The minimum players needed before countdown starts
     * @param int $maximum The maximum number of players
     * @param ZeroifyEnvironment $environment
     * @param Configuration $configuration
     *
     * By default, the waiting state is set, you can alter it by calling the
     * @see StateManager::setState() with your custom state,
     * So you're freely able to make your own game states :)
     */
    public function __construct(string $name, int $minimum, int $maximum, ZeroifyEnvironment  $environment, Configuration $configuration)
    {
        self::$instance = $this;

        $this->setName($name);
        $this->setMinimum($minimum);
        $this->setMaximum($maximum);
        $this->setEnvironment($environment);
        $this->setConfiguration($configuration);
        $this->setTeamManager(new TeamManager());
        if(!$this->getTeamManager()->hasDefaultTeam()) {
            $spawn = Server::getInstance()->getDefaultLevel()->getSpawnLocation();
            $this->getTeamManager()->addTeam(new PlayerTeam(TeamIdentifiers::PLAYER, new PositionConfiguration($spawn), [], true));

            $this->getTeamManager()->addTeam(new SpectatorTeam(TeamIdentifiers::SPECTATOR, new PositionConfiguration($spawn, 0, 0, true), [], false, false, Player::SPECTATOR));
        }

        $this->setStateManager(new StateManager());

        $this->getStateManager()->setState(new StateWaiting(StateType::WAITING, new TimeConfiguration((20 * 60) * 5))); // 5 minutes...
    }


    public function deploy ($deploy = true)
    {
        /*
            * Check if they have a state active.
            */
        if (!$this->getStateManager()->hasState()) {
            throw new \Exception("You did not set a state");
        }

        /*
         * Check to see if the team they have registered is all good
         */
        if ($this->getTeamManager()->getGame()->getType() != GameType::DEFAULT) {
            foreach ($this->getTeamManager()->getGame()->getTeams() as $team) {
                if (!$this->getTeamManager()->teamExists($team)) {
                    if (!$this->getStateManager()->hasState()) {
                        throw new \Exception("You did not register team: " . $team . " to support game-type of " . $this->getTeamManager()->getGame()->getType());
                    }
                }
            }
        }


        /*
         * Check to see if the team system iw working properly before deploying the game
         */
        if ($this->getTeamManager()->getGame()->getType() != GameType::DEFAULT) {


            switch ($this->getTeamManager()->getGame()->getType()) {
                case GameType::SOLO:
                case GameType::DUO:
                case GameType::SQUAD:
                    $count = 0;
                    foreach ($this->getTeamManager()->getGame()->getTeams() as $team) {
                        $team = $this->getTeamManager()->getTeam($team);

                        $count += $this->getTeamManager()->getGame()->getMaxForTeam($team);
                    }
                    if($count > $this->getMaximum()) {
                        throw new \Exception("The game type " . $this->getTeamManager()->getGame()->getType() . " does not have enough teams to support the maximum players of " . $this->getMaximum() . " (current: " . $count . ")");
                    }
                    unset($count);

                break;
            }


            if ($deploy) {
                $this->allGood();
            }
        }
    }

    /*
     * For internal only.....
     */
    protected function allGood() {

        $this->getEnvironment()->log(TextFormat::GREEN . "Everything seems good to go, the game shall now launch!");

        /**
         * 1. Calls the first/default state init, do stuff needed for first-run
         * 2. Schedule the StateTick now
         * 3. Registers the @see Observer listener
         */
        $this->getStateManager()->getState()->init();
        Zeroify::getInstance()->getEnvironment()->getPlugin()->getScheduler()->scheduleRepeatingTask(new StateTick(), 20);
        Zeroify::getInstance()->getEnvironment()->getPlugin()->getServer()->getPluginManager()->registerEvents(new Observer(), Zeroify::getInstance()->getEnvironment()->getPlugin());
    }

    /**
     * @return Zeroify
     */
    public static function getInstance(): Zeroify
    {
        return self::$instance;
    }

    /**
     * @return ZeroifyEnvironment
     */
    public function getEnvironment(): ZeroifyEnvironment
    {
        return $this->environment;
    }

    /**
     * @return StateManager
     */
    public function getStateManager(): StateManager
    {
        return $this->stateManager;
    }

    /**
     * @param StateManager $stateManager
     */
    public function setStateManager(StateManager $stateManager): void
    {
        $this->stateManager = $stateManager;
    }

    /**
     * @param ZeroifyEnvironment $environment
     * @return Zeroify
     */
    public function setEnvironment(ZeroifyEnvironment $environment): Zeroify
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    /**
     * @param Configuration $configuration
     */
    public function setConfiguration(Configuration $configuration): void
    {
        $this->configuration = $configuration;
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
     * @return Zeroify
     */
    public function setName(string $name): Zeroify
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinimum(): int
    {
        return $this->minimum;
    }

    /**
     * @param int $minimum
     * @return Zeroify
     */
    public function setMinimum(int $minimum): Zeroify
    {
        $this->minimum = $minimum;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaximum(): int
    {
        return $this->maximum;
    }

    /**
     * @param int $maximum
     * @return Zeroify
     */
    public function setMaximum(int $maximum): Zeroify
    {
        $this->maximum = $maximum;
        $class = new \ReflectionClass(Server::getInstance());
        $property = $class->getProperty("maxPlayers");
        $property->setAccessible(true);
        $property->setValue(Server::getInstance(), $maximum);
        return $this;
    }

    /**
     * @return TeamManager
     */
    public function getTeamManager(): TeamManager
    {
        return $this->teamManager;
    }

    /**
     * @param TeamManager $teamManager
     * @return Zeroify
     */
    public function setTeamManager(TeamManager $teamManager): Zeroify
    {
        $this->teamManager = $teamManager;
        return $this;
    }
}