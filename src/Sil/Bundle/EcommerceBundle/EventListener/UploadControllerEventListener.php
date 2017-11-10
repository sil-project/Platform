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

use Symfony\Component\EventDispatcher\GenericEvent;
use Doctrine\ORM\EntityManager;
use Sil\Bundle\MediaBundle\Events\UploadControllerEventListener as BaseUploadControllerEventListener;
use Sil\Bundle\EcommerceBundle\Entity\ProductImage;
use Sil\Bundle\MediaBundle\Entity\File;

class UploadControllerEventListener extends BaseUploadControllerEventListener
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function preGetEntity(GenericEvent $event)
    {
        $repo = $this->em->getRepository('SilEcommerceBundle:ProductImage');

        $productImage = $repo->findOneBy(['id'=>$event->getSubject()['context']['id']]);

        if ($productImage) {
            $file = $productImage->getRealFile();
            if ($file === null) {
                $file = new File();
            }
            $file->isCover = $productImage->getType() === ProductImage::TYPE_COVER;
            $event->setArgument('file', $file);
        }
    }

    public function postGetEntity(GenericEvent $event)
    {
    }

    public function removeEntity(GenericEvent $event)
    {
        $file = $event->getSubject();
        $repo = $this->em->getRepository('SilEcommerceBundle:ProductImage');
        $productImage = $repo->findOneBy(['realFile' => $file]);

        if ($productImage !== null) {
            $this->em->remove($productImage);
            $this->em->flush($productImage);
        }
    }
}
