<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

/**
 * @author macosxvn
 * Date: 19/06/2017
 * Time: 16:41
 */

namespace Macosxvn\Debugbar;

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\MessagesCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use DebugBar\DebugBar as BaseDebugBar;
use Macosxvn\Debugbar\Events\Manager;
use Phalcon\DiInterface;
use Phalcon\Events\Manager as PhalconManager;

class Debugbar extends BaseDebugBar {

    const SERVICE_NAME = 'debugbar';
    const PUBLIC_URI = '/debugbar';

    /**
     * @var DiInterface
     */
    protected $di;
    /**
     * @var \Phalcon\Config
     */
    protected $config;

    public function __construct(DiInterface $di) {
        $this->di = $di;
        $this->config = Config::getDefaultConfig();
        if (isset($di->get('config')['debugbar'])) {
            $this->config->merge($di->get('config')['debugbar']);
        }

        $this->initialize();
    }

    public function initialize() {
        if (!$this->config->get(Config::CONFIG_ENABLED)) {
            return;
        }

        if (!$this->di->has('app') && !$this->di->has('application')) {
            throw new \Exception('Application service is not set');
        }

        // Init javascript renderer
        $renderer = $this->getJavascriptRenderer(Debugbar::PUBLIC_URI);

        if (isset($this->config[Config::CONFIG_VENDOR])) {
            foreach ($this->config[Config::CONFIG_VENDOR] as $vendorName => $value) {
                if (!$value) {
                    $renderer->disableVendor($vendorName);
                }
            }
        }

        $this->_addDefaultCollector();

        $this->getMessageCollector()->info("info message");

        $this->getMessageCollector()->error("this is an error");

        $this->getMessageCollector()->warning("this is a warning");

        foreach (Config::COLLECTORS as $collectorName) {
            /**
             * If collector is enabled -> create & add collector
             */
            if (isset($this->config['collectors'][$collectorName])) {

            }
        }

        /**
         * Trigger debugbar to content
         */
        /* @var $application \Phalcon\Mvc\Application */
        if ($this->di->has('app')) {
            $application = $this->di->get('app');
        }
        elseif ($this->di->has('application')) {
            $application = $this->di->get('application');
        }
        if ($eventManager = $application->getEventsManager()) {
            $eventManager->attach('application', new Manager());
        }
        else {
            if ($this->di->get('eventsManager')) {
                $eventManager = $this->di->get('eventsManager');
            }
            else {
                $eventManager = new PhalconManager();
            }
            $eventManager->attach('application', new Manager());
            $application->setEventsManager($eventManager);
        }
    }

    /**
     * @return \DebugBar\DataCollector\DataCollectorInterface
     */
    public function getTimeCollector() {
        return $this->getCollector("time");
    }

    /**
     * @return \DebugBar\DataCollector\DataCollectorInterface|MessagesCollector
     */
    public function getMessageCollector() {
        if (!isset($this->collectors["messages"])) {
            $this->addCollector(new MessagesCollector());
        }
        return $this->getCollector("messages");
    }

    protected function _addDefaultCollector() {
        // Always add memory collector & time data collector
        $this->addCollector(new MemoryCollector());
        $this->addCollector(new TimeDataCollector());

        // Add config collector by default
        if ($this->config[Config::CONFIG_COLLECTORS][Config::COLLECTOR_CONFIG]) {
            /* @var $globalConfig \Phalcon\Config */
            $globalConfig = $this->di->get('config');
            $config = array_merge($globalConfig->toArray(), ["debugbar" => $this->config->toArray()]);
            $this->addCollector(new ConfigCollector($config, Config::COLLECTOR_CONFIG));
        }

        // Add request collector
        if ($this->config[Config::CONFIG_COLLECTORS][Config::COLLECTOR_REQUEST]) {
            $this->addCollector(new RequestDataCollector());
        }

        // Add messages collector
        if ($this->config[Config::CONFIG_COLLECTORS][Config::COLLECTOR_MESSAGES]) {
            $this->addCollector(new MessagesCollector());
        }
    }
}