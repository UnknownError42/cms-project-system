<?php
/**
 * Copyright (c) 2018.  MyArtSide
 */

/**
 * @author macosxvn
 * Date: 20/06/2017
 * Time: 09:22
 */

namespace Macosxvn\Debugbar\Composer;

use Composer\Script\Event;

/**
 * Class Script
 * @package Macosxvn\Debugbar\Composer
 *
 * Run post-install-cmd to create symlink of debugbar resources in public folder
 * <code>
 *     composer run-script post-install-cmd -d vendor/macosxvn/phalcon-debugbar
 * </code>
 */
class Script {

    /**
     * /!\ NOTE: If change this constant value, please change the same constant in \Macosxvn\Debugbar\Debugbar
     */
    const PUBLIC_URI = '/debugbar';

    /**
     * Note: run composer script in vendor/macosxvn/phalcon-debugbar
     * @param Event $event
     * @throws \Exception
     */
    public static function postInstall(Event $event) {
        $composer = $event->getComposer();
        $vendorDir = $composer->getConfig()->get('vendor-dir');
        $projectDir = dirname(dirname(dirname($vendorDir)));
        if (preg_match("/(vendor)$/", $projectDir)) {
            $projectDir = dirname($projectDir);
        }
        $debugbarResources = $projectDir . "/vendor/maximebf/debugbar/src/DebugBar/Resources";
        if (file_exists($debugbarResources)) {
            $publicDir = $projectDir . "/public";
            if (file_exists($publicDir)) {
                $publicLink = $publicDir . self::PUBLIC_URI;
                @symlink($debugbarResources, $publicLink);
            } else {
                throw new \Exception("Public folder not found");
            }
        } else {
            throw new \Exception("Base debugbar not found");
        }
    }
}