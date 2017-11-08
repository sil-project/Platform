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

namespace Sil\Bundle\EcommerceBundle\Services\Sylius\Setup;

use Sylius\Bundle\CoreBundle\Installer\Setup\CurrencySetupInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Intl\Intl;

/**
 * Initial code taken from: Sylius\Bundle\CoreBundle\Installer\Setup\CurrencySetup
 * This one uses the "sylius_currency.currency" app parameter instead of asking currency code to the user.
 *
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
final class CurrencySetup implements CurrencySetupInterface
{
    /**
     * @var RepositoryInterface
     */
    private $currencyRepository;

    /**
     * @var FactoryInterface
     */
    private $currencyFactory;

    /**
     * @var string
     */
    private $currencyCode;

    /**
     * @param RepositoryInterface $currencyRepository
     * @param FactoryInterface    $currencyFactory
     * @param string              $currencyCode       3 letters currency code (USD, EUR...)
     */
    public function __construct(RepositoryInterface $currencyRepository, FactoryInterface $currencyFactory, $currencyCode)
    {
        $this->currencyRepository = $currencyRepository;
        $this->currencyFactory = $currencyFactory;
        $this->currencyCode = $currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setup(InputInterface $input, OutputInterface $output, QuestionHelper $questionHelper): CurrencyInterface
    {
        $existingCurrency = $this->currencyRepository->findOneBy(['code' => $this->currencyCode]);
        if (null !== $existingCurrency) {
            $name = $this->getCurrencyName($existingCurrency->getCode());
            $output->writeln(sprintf('Currency <info>%s (%s)</info> exists already. Skipped.', $this->currencyCode, $name));

            return $existingCurrency;
        }

        $name = $this->getCurrencyName($this->currencyCode);
        if (null === $name) {
            throw new \Exception(sprintf('Currency with code <info>%s</info> could not be resolved. Please check your "sylius_currency.currency" parameter', $this->currencyCode));
        }
        /**
         * @var CurrencyInterface
         */
        $currency = $this->currencyFactory->createNew();
        $currency->setCode($this->currencyCode);
        $this->currencyRepository->add($currency);

        $output->writeln(sprintf('Added <info>%s %s)</info> currency.', $this->currencyCode, $name));

        return $currency;
    }

    /**
     * @param string $code
     *
     * @return string|null
     */
    private function getCurrencyName($code)
    {
        return Intl::getCurrencyBundle()->getCurrencyName($code);
    }
}
