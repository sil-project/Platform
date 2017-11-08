<?php

/*
 * This file is part of the Sil Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Fixture\Factory;

use Sylius\Bundle\CoreBundle\Fixture\Factory\ChannelExampleFactory as BaseFactory;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ChannelExampleFactory extends BaseFactory
{
    /**
     * {@inheritdoc}
     */
    public function create(array $options = []): ChannelInterface
    {
        /**
         * @var ChannelInterface
         */
        $channel = parent::create($options);
        $channel->setAccountVerificationRequired($options['account_verified_required']);

        return $channel;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver
            ->setDefault('account_verified_required', false)
            ->setAllowedTypes('account_verified_required', 'bool');
    }
}
