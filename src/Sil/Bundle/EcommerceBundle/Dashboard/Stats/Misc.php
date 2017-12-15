<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Dashboard\Stats;

use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

class Misc extends AbstractStats
{
    /**
     * @var RawQueries
     */
    private $rawQueries;

    /**
     * @var MoneyFormatterInterface
     */
    private $moneyFormatter;

    /**
     * @var CurrencyContextInterface
     */
    private $currencyContext;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    public function getData(array $parameters = []): array
    {
        // $stats structure : [
        //      [
        //          'label' => 'Your stat label',
        //          'value' => 1854
        //      ],
        // ]
        $stats = [];

        // ####################################################
        // SALES FOR CURRENT YEAR
        // ####################################################

        $result = $this->rawQueries->getSalesForCurrentYear();
        $result['value'] = $this->moneyFormatter->format((int) $result['value'], $this->currencyContext->getCurrencyCode(), $this->localeContext->getLocaleCode());
        $stats[] = $result;

        // ####################################################
        // TOTAL SALES
        // ####################################################

        $result = $this->rawQueries->getTotalSales();
        $result['value'] = $this->moneyFormatter->format((int) $result['value'], $this->currencyContext->getCurrencyCode(), $this->localeContext->getLocaleCode());
        $stats[] = $result;

        // ####################################################
        // TAXES FOR CURRENT YEAR
        // ####################################################

        $result = $this->rawQueries->getTaxesForCurrentYear();
        $result['value'] = $this->moneyFormatter->format((int) $result['value'], $this->currencyContext->getCurrencyCode(), $this->localeContext->getLocaleCode());
        $stats[] = $result;

        // ####################################################
        // TOTAL TAXES
        // ####################################################

        $result = $this->rawQueries->getTotalTaxes();
        $result['value'] = $this->moneyFormatter->format((int) $result['value'], $this->currencyContext->getCurrencyCode(), $this->localeContext->getLocaleCode());
        $stats[] = $result;

        // END STATS

        return $stats;
    }

    /**
     * @param RawQueries $rawQueries
     */
    public function setRawQueries(RawQueries $rawQueries): void
    {
        $this->rawQueries = $rawQueries;
    }

    /**
     * @param MoneyFormatterInterface $moneyFormatter
     */
    public function setMoneyFormatter(MoneyFormatterInterface $moneyFormatter): void
    {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * @param CurrencyContextInterface $currencyContext
     */
    public function setCurrencyContext(CurrencyContextInterface $currencyContext): void
    {
        $this->currencyContext = $currencyContext;
    }

    /**
     * @param LocaleContextInterface $localeContext
     */
    public function setLocaleContext(LocaleContextInterface $localeContext): void
    {
        $this->localeContext = $localeContext;
    }
}
