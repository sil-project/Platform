<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Tools;

use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler;
use Composer\Script\Event;

class ComposerScriptHandler extends ScriptHandler
{
    public static function installSyliusThemeAssets(Event $event)
    {
        $options = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'install sylius assets');

        if (null === $consoleDir) {
            return;
        }

        $webDir = $options['symfony-web-dir'];

        $symlink = '';
        if ('symlink' == $options['symfony-assets-install']) {
            $symlink = '--symlink ';
        } elseif ('relative' == $options['symfony-assets-install']) {
            $symlink = '--symlink --relative ';
        }

        if (!static::hasDirectory($event, 'symfony-web-dir', $webDir, 'install assets')) {
            return;
        }

        static::executeCommand($event, $consoleDir, 'sylius:theme:assets:install ' . $symlink . escapeshellarg($webDir), $options['process-timeout']);
    }
}
