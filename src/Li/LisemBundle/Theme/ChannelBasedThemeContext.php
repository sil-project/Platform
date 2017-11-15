<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Theme;

use Sylius\Bundle\ThemeBundle\Context\ThemeContextInterface;
use Sylius\Bundle\ThemeBundle\Repository\ThemeRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
final class ChannelBasedThemeContext implements ThemeContextInterface
{
    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var ThemeRepositoryInterface
     */
    private $themeRepository;

    private static $_channel;

    /**
     * @param ChannelContextInterface  $channelContext
     * @param ThemeRepositoryInterface $themeRepository
     */
    public function __construct(ChannelContextInterface $channelContext, ThemeRepositoryInterface $themeRepository)
    {
        $this->channelContext = $channelContext;
        $this->themeRepository = $themeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getTheme(): ?ThemeInterface
    {
        try {
            /* @var ChannelInterface $channel */
            if (self::$_channel === null) {
                self::$_channel = $this->channelContext->getChannel();
            }

            return $this->themeRepository->findOneByName(self::$_channel->getThemeName());
        } catch (ChannelNotFoundException $exception) {
            return null;
        } catch (\Exception $exception) {
            return null;
        }
    }
}
