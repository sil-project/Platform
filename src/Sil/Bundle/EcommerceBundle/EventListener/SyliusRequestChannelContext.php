<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Channel\Context\RequestBased\RequestResolverInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;

class SyliusRequestChannelContext implements RequestResolverInterface
{
    /**
     * @var string
     */
    private $fallbackChannelCode;

    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    public function findChannel(Request $request): ChannelInterface
    {
        $channelCode = $this->fallbackChannelCode;
        $cookiesChannelCode = $request->cookies->get('_channel_code');
        $requestChannelCode = $request->query->get('_channel_code');
        if (isset($cookiesChannelCode)) {
            $channelCode = $cookiesChannelCode;
        }
        // No else as query->get should be use if setted
        if (isset($requestChannelCode)) {
            $channelCode = $requestChannelCode;
        }

        return $this->channelRepository->findOneByCode($channelCode);
    }

    /**
     * @param string fallbackChannelCode
     */
    public function setFallbackChannelCode($fallbackChannelCode)
    {
        $this->fallbackChannelCode = $fallbackChannelCode;
    }

    /**
     * @param ChannelRepositoryInterface channelRepository
     *
     * @return self
     */
    public function setChannelRepository(ChannelRepositoryInterface $channelRepository)
    {
        $this->channelRepository = $channelRepository;

        return $this;
    }
}
