<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Component\Currency\Model;

use DomainException;
use Symfony\Component\Intl\Intl;

class Currency implements CurrencyInterface
{
    /**
     * The string code.
     *
     * @var string
     */
    protected $code;

    /**
     * The numeric code.
     *
     * @var int
     */
    protected $isoCode;

    /**
     * String representation of currency symbol.
     *
     * @var string
     */
    protected $symbol;

    public function __construct(string $code, int $isoCode = 0, string $symbol = null)
    {
        if (!$this->isCodeManaged($code)) {
            throw new DomainException(sprintf('The currency with code %s is not managed by this component', $code));
        }

        $this->code = $code;
        $this->isoCode = $isoCode ?: $this->findIsoCodeForCurrencyCode($code);
        $this->symbol = $symbol ?? Intl::getCurrencyBundle()->getCurrencySymbol($code);
    }

    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsoCode(): int
    {
        return $this->isoCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getSymbol(): string
    {
        return (string) $this->symbol;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return Intl::getCurrencyBundle()->getCurrencyName($this->getCode());
    }

    private function findIsoCodeForCurrencyCode(string $code): int
    {
        foreach ($this->getManagedCurrencies() as $currency) {
            if ($code === $currency['code']) {
                return $currency['isoCode'];
            }
        }

        return 0;
    }

    private function isCodeManaged(string $code): bool
    {
        return $this->findIsoCodeForCurrencyCode($code) !== 0;
    }

    public function __toString(): string
    {
        if (($symbol = $this->getSymbol()) !== '') {
            return $symbol;
        }

        return $this->getCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getManagedCurrencies(): array
    {
        return [
            ['code' => 'AED', 'isoCode' => 784],
            ['code' => 'AFN', 'isoCode' => 971],
            ['code' => 'ALL', 'isoCode' => 8],
            ['code' => 'AMD', 'isoCode' => 51],
            ['code' => 'ANG', 'isoCode' => 532],
            ['code' => 'AOA', 'isoCode' => 973],
            ['code' => 'ARS', 'isoCode' => 32],
            ['code' => 'AUD', 'isoCode' => 36],
            ['code' => 'AWG', 'isoCode' => 533],
            ['code' => 'AZN', 'isoCode' => 944],
            ['code' => 'BAM', 'isoCode' => 977],
            ['code' => 'BBD', 'isoCode' => 52],
            ['code' => 'BDT', 'isoCode' => 50],
            ['code' => 'BGN', 'isoCode' => 975],
            ['code' => 'BHD', 'isoCode' => 48],
            ['code' => 'BIF', 'isoCode' => 108],
            ['code' => 'BMD', 'isoCode' => 60],
            ['code' => 'BND', 'isoCode' => 96],
            ['code' => 'BOB', 'isoCode' => 68],
            ['code' => 'BOV', 'isoCode' => 984],
            ['code' => 'BRL', 'isoCode' => 986],
            ['code' => 'BSD', 'isoCode' => 44],
            ['code' => 'BTN', 'isoCode' => 64],
            ['code' => 'BWP', 'isoCode' => 72],
            ['code' => 'BYN', 'isoCode' => 933],
            ['code' => 'BZD', 'isoCode' => 84],
            ['code' => 'CAD', 'isoCode' => 124],
            ['code' => 'CDF', 'isoCode' => 976],
            ['code' => 'CHE', 'isoCode' => 947],
            ['code' => 'CHF', 'isoCode' => 756],
            ['code' => 'CHW', 'isoCode' => 948],
            ['code' => 'CLF', 'isoCode' => 990],
            ['code' => 'CLP', 'isoCode' => 152],
            ['code' => 'CNY', 'isoCode' => 156],
            ['code' => 'COP', 'isoCode' => 170],
            ['code' => 'COU', 'isoCode' => 970],
            ['code' => 'CRC', 'isoCode' => 188],
            ['code' => 'CUC', 'isoCode' => 931],
            ['code' => 'CUP', 'isoCode' => 192],
            ['code' => 'CVE', 'isoCode' => 132],
            ['code' => 'CZK', 'isoCode' => 203],
            ['code' => 'DJF', 'isoCode' => 262],
            ['code' => 'DKK', 'isoCode' => 208],
            ['code' => 'DOP', 'isoCode' => 214],
            ['code' => 'DZD', 'isoCode' => 12],
            ['code' => 'EGP', 'isoCode' => 818],
            ['code' => 'ERN', 'isoCode' => 232],
            ['code' => 'ETB', 'isoCode' => 230],
            ['code' => 'EUR', 'isoCode' => 978],
            ['code' => 'FJD', 'isoCode' => 242],
            ['code' => 'FKP', 'isoCode' => 238],
            ['code' => 'GBP', 'isoCode' => 826],
            ['code' => 'GEL', 'isoCode' => 981],
            ['code' => 'GHS', 'isoCode' => 936],
            ['code' => 'GIP', 'isoCode' => 292],
            ['code' => 'GMD', 'isoCode' => 270],
            ['code' => 'GNF', 'isoCode' => 324],
            ['code' => 'GTQ', 'isoCode' => 320],
            ['code' => 'GYD', 'isoCode' => 328],
            ['code' => 'HKD', 'isoCode' => 344],
            ['code' => 'HNL', 'isoCode' => 340],
            ['code' => 'HRK', 'isoCode' => 191],
            ['code' => 'HTG', 'isoCode' => 332],
            ['code' => 'HUF', 'isoCode' => 348],
            ['code' => 'IDR', 'isoCode' => 360],
            ['code' => 'ILS', 'isoCode' => 376],
            ['code' => 'INR', 'isoCode' => 356],
            ['code' => 'IQD', 'isoCode' => 368],
            ['code' => 'IRR', 'isoCode' => 364],
            ['code' => 'ISK', 'isoCode' => 352],
            ['code' => 'JMD', 'isoCode' => 388],
            ['code' => 'JOD', 'isoCode' => 400],
            ['code' => 'JPY', 'isoCode' => 392],
            ['code' => 'KES', 'isoCode' => 404],
            ['code' => 'KGS', 'isoCode' => 417],
            ['code' => 'KHR', 'isoCode' => 116],
            ['code' => 'KMF', 'isoCode' => 174],
            ['code' => 'KPW', 'isoCode' => 408],
            ['code' => 'KRW', 'isoCode' => 410],
            ['code' => 'KWD', 'isoCode' => 414],
            ['code' => 'KYD', 'isoCode' => 136],
            ['code' => 'KZT', 'isoCode' => 398],
            ['code' => 'LAK', 'isoCode' => 418],
            ['code' => 'LBP', 'isoCode' => 422],
            ['code' => 'LKR', 'isoCode' => 144],
            ['code' => 'LRD', 'isoCode' => 430],
            ['code' => 'LSL', 'isoCode' => 426],
            ['code' => 'LYD', 'isoCode' => 434],
            ['code' => 'MAD', 'isoCode' => 504],
            ['code' => 'MDL', 'isoCode' => 498],
            ['code' => 'MGA', 'isoCode' => 969],
            ['code' => 'MKD', 'isoCode' => 807],
            ['code' => 'MMK', 'isoCode' => 104],
            ['code' => 'MNT', 'isoCode' => 496],
            ['code' => 'MOP', 'isoCode' => 446],
            ['code' => 'MRO', 'isoCode' => 478],
            ['code' => 'MUR', 'isoCode' => 480],
            ['code' => 'MVR', 'isoCode' => 462],
            ['code' => 'MWK', 'isoCode' => 454],
            ['code' => 'MXN', 'isoCode' => 484],
            ['code' => 'MXV', 'isoCode' => 979],
            ['code' => 'MYR', 'isoCode' => 458],
            ['code' => 'MZN', 'isoCode' => 943],
            ['code' => 'NAD', 'isoCode' => 516],
            ['code' => 'NGN', 'isoCode' => 566],
            ['code' => 'NIO', 'isoCode' => 558],
            ['code' => 'NOK', 'isoCode' => 578],
            ['code' => 'NPR', 'isoCode' => 524],
            ['code' => 'NZD', 'isoCode' => 554],
            ['code' => 'OMR', 'isoCode' => 512],
            ['code' => 'PAB', 'isoCode' => 590],
            ['code' => 'PEN', 'isoCode' => 604],
            ['code' => 'PGK', 'isoCode' => 598],
            ['code' => 'PHP', 'isoCode' => 608],
            ['code' => 'PKR', 'isoCode' => 586],
            ['code' => 'PLN', 'isoCode' => 985],
            ['code' => 'PYG', 'isoCode' => 600],
            ['code' => 'QAR', 'isoCode' => 634],
            ['code' => 'RON', 'isoCode' => 946],
            ['code' => 'RSD', 'isoCode' => 941],
            ['code' => 'RUB', 'isoCode' => 643],
            ['code' => 'RWF', 'isoCode' => 646],
            ['code' => 'SAR', 'isoCode' => 682],
            ['code' => 'SBD', 'isoCode' => 90],
            ['code' => 'SCR', 'isoCode' => 690],
            ['code' => 'SDG', 'isoCode' => 938],
            ['code' => 'SEK', 'isoCode' => 752],
            ['code' => 'SGD', 'isoCode' => 702],
            ['code' => 'SHP', 'isoCode' => 654],
            ['code' => 'SLL', 'isoCode' => 694],
            ['code' => 'SOS', 'isoCode' => 706],
            ['code' => 'SRD', 'isoCode' => 968],
            ['code' => 'SSP', 'isoCode' => 728],
            ['code' => 'STD', 'isoCode' => 678],
            ['code' => 'SVC', 'isoCode' => 222],
            ['code' => 'SYP', 'isoCode' => 760],
            ['code' => 'SZL', 'isoCode' => 748],
            ['code' => 'THB', 'isoCode' => 764],
            ['code' => 'TJS', 'isoCode' => 972],
            ['code' => 'TMT', 'isoCode' => 934],
            ['code' => 'TND', 'isoCode' => 788],
            ['code' => 'TOP', 'isoCode' => 776],
            ['code' => 'TRY', 'isoCode' => 949],
            ['code' => 'TTD', 'isoCode' => 780],
            ['code' => 'TWD', 'isoCode' => 901],
            ['code' => 'TZS', 'isoCode' => 834],
            ['code' => 'UAH', 'isoCode' => 980],
            ['code' => 'UGX', 'isoCode' => 800],
            ['code' => 'USD', 'isoCode' => 840],
            ['code' => 'USN', 'isoCode' => 997],
            ['code' => 'UYI', 'isoCode' => 940],
            ['code' => 'UYU', 'isoCode' => 858],
            ['code' => 'UZS', 'isoCode' => 860],
            ['code' => 'VEF', 'isoCode' => 937],
            ['code' => 'VND', 'isoCode' => 704],
            ['code' => 'VUV', 'isoCode' => 548],
            ['code' => 'WST', 'isoCode' => 882],
            ['code' => 'XAF', 'isoCode' => 950],
            ['code' => 'XAG', 'isoCode' => 961],
            ['code' => 'XAU', 'isoCode' => 959],
            ['code' => 'XBA', 'isoCode' => 955],
            ['code' => 'XBB', 'isoCode' => 956],
            ['code' => 'XBC', 'isoCode' => 957],
            ['code' => 'XBD', 'isoCode' => 958],
            ['code' => 'XCD', 'isoCode' => 951],
            ['code' => 'XDR', 'isoCode' => 960],
            ['code' => 'XOF', 'isoCode' => 952],
            ['code' => 'XPD', 'isoCode' => 964],
            ['code' => 'XPF', 'isoCode' => 953],
            ['code' => 'XPT', 'isoCode' => 962],
            ['code' => 'XSU', 'isoCode' => 994],
            ['code' => 'XTS', 'isoCode' => 963],
            ['code' => 'XUA', 'isoCode' => 965],
            ['code' => 'XXX', 'isoCode' => 999],
            ['code' => 'YER', 'isoCode' => 886],
            ['code' => 'ZAR', 'isoCode' => 710],
            ['code' => 'ZMW', 'isoCode' => 967],
            ['code' => 'ZWL', 'isoCode' => 932],
        ];
    }
}
