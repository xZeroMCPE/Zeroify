<?php


namespace xZeroMCPE\Zeroify;


use pocketmine\plugin\Plugin;
use xZeroMCPE\Zeroify\Observer\Observer;

class ZeroifyEnvironment
{
    const DEVELOPMENT = 1;
    const PRODUCTION = 2;

    public int $environment;
    public Plugin  $plugin;

    public static ZeroifyEnvironment $instance;

    public function __construct(Plugin  $plugin, int $environment = ZeroifyEnvironment::DEVELOPMENT) {
        self::$instance = $this;
        $this->plugin = $plugin;
        $this->environment = $environment;
        $this->registerHandler();
    }

    /**
     * @return ZeroifyEnvironment
     */
    public static function getInstance(): ZeroifyEnvironment
    {
        return self::$instance;
    }

    /**
     * @return int
     */
    public function getEnvironment(): int
    {
        return $this->environment;
    }

    /**
     * @param int $environment
     */
    public function setEnvironment(int $environment): void
    {
        $this->environment = $environment;
    }

    public function isDevelopment() : bool {
        return $this->getEnvironment() != ZeroifyEnvironment::PRODUCTION;
    }

    /**
     * @return Plugin
     */
    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }

    /**
     * @param Plugin $plugin
     */
    public function setPlugin(Plugin $plugin): void
    {
        $this->plugin = $plugin;
    }

    public function registerHandler() {
        $this->getPlugin()->getServer()->getPluginManager()->registerEvents(new Observer(), $this->getPlugin());
    }

    public function log(string $what) {
        $this->getPlugin()->getLogger()->info($what);
    }
}