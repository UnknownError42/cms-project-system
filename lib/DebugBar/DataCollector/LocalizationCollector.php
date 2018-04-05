<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

namespace DebugBar\DataCollector;

/**
 * Collects info about the current localization state
 */
class LocalizationCollector extends DataCollector implements Renderable
{
    /**
     * Get the current locale
     *
     * @return string
     */
    public function getLocale()
    {
        return setlocale(LC_ALL, 0);
    }

    /**
     * Get the current translations domain
     *
     * @return string
     */
    public function getDomain()
    {
        return textdomain();
    }

    /**
     * @return array
     */
    public function collect()
    {
        return array(
          'locale' => $this->getLocale(),
          'domain' => $this->getDomain(),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'localization';
    }

    /**
     * @return array
     */
    public function getWidgets()
    {
        return array(
            'domain' => array(
                'icon' => 'bookmark',
                'map'  => 'localization.domain',
            ),
            'locale' => array(
                'icon' => 'flag',
                'map'  => 'localization.locale',
            )
        );
    }
}
