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

namespace Sil\Bundle\MediaBundle\Twig\Extension;

class GenerateImgTagExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'generate_img_tag';
    }

    public function getFilters()
    {
        return [new \Twig_SimpleFilter('generateImgTag', array($this, 'generateTag'), array('is_safe' => array('html')))];
    }

    public function generateTag($file, $height = null, $width = null)
    {
        if (!$this->isImage($file)) {
            return;
        }

        $alt = explode('.', $file->getName())[0];

        $tag = '<img src="data:' . $file->getMimeType() . ';base64,' . $file->getBase64File() . '" alt="' . $alt . '"';

        if ($height) {
            $tag .= '" height="' . $height . '"';
        }
        if ($width) {
            $tag .= '" width="' . $width . '"';
        }

        $tag .= '/>';

        return $tag;
    }

    /**
     * Checks if the file is an image according to its mimetype.
     *
     * @param File $file
     *
     * @return bool
     */
    private function isImage($file)
    {
        if ($file && preg_match('!^image\/!', $file->getMimeType()) === 1) {
            return true;
        }

        return false;
    }
}
