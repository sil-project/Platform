<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\BaseEntitiesBundle\Search;

use Behat\Transliterator\Transliterator;

class SearchAnalyser
{
    /**
     * @param string $text
     *
     * @return array
     */
    public static function analyse($text)
    {
        // to lowercase
        $text = mb_strtolower(trim($text), 'utf-8');

        // remove accents
        $text = Transliterator::unaccent($text);

        // considering very special chars as spaces
        $text = str_replace(array(
          '@',
          '.', ',', '¿',
          '♠', '♣', '♥', '♦',
          '-', '+',
          '←', '↑', '→', '↓',
          "'", '’', '´',
          '●', '•',
          '¼', '½', '¾',
          '“', '”', '„',
          '°', '™', '©', '®',
          '³', '²',
        ), ' ', $text);

        // remove multiple spaces
        $text = preg_replace('/\s+/', ' ', $text);

        if ($text) {
            $words = explode(' ', $text);
            if (!is_array($words)) {
                $words = [$text];
            }

            return $words;
        }

        return [];
    }
}
