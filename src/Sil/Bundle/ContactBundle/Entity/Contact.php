<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Blast\Bundle\BaseEntitiesBundle\Entity\Traits\Guidable;
use Sil\Component\Contact\Model\Contact as BaseContact;
use Sil\Component\Contact\Model\Traits\GroupMemberTrait;

/**
 * Contact.
 *
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 */
class Contact extends BaseContact
{
    use Guidable;
    use GroupMemberTrait;

    const TITLE_MR = 'title_mr';
    const TITLE_MRS = 'title_mrs';

    public function __construct()
    {
        parent::__construct();

        $this->groups = new ArrayCollection();
    }

    public static function getTitleChoices()
    {
        $prefix = 'sil_contact.contact.choices.';

        return [
            $prefix . 'title_mr'  => self::TITLE_MR,
            $prefix . 'title_mrs' => self ::TITLE_MRS,
        ];
    }
}
