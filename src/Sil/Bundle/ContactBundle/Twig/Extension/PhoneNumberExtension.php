<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Twig\Extension;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

/**
 * Phone number twig extension.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class PhoneNumberExtension extends \Twig_Extension
{
    /**
     * Phone formatting utility.
     *
     * @var PhoneNumberUtil
     */
    protected $phoneNumberUtil;

    /**
     * Current app locale.
     *
     * @var string
     */
    protected $locale;

    /**
     * @param PhoneNumberUtil $phoneNumberUtil
     */
    public function __construct(PhoneNumberUtil $phoneNumberUtil, string $locale)
    {
        $this->phoneNumberUtil = $phoneNumberUtil;
        $this->locale = strtoupper($locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('formatPhoneForCountry', array($this, 'formatPhoneForCountry')),
        );
    }

    /**
     *  Parse then format number for given country code.
     *
     * @param string $number
     * @param string $locale
     *
     * @return string
     */
    public function formatPhoneForCountry(string $number)
    {
        $phoneNumber = $this->phoneNumberUtil->parse($number, $this->locale);

        return $this->phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::NATIONAL);
    }
}
