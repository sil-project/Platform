<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Imagine\PathResolver;

use Sil\Bundle\MediaBundle\Imagine\PathResolver\PathResolverInterface;
use Sil\Bundle\MediaBundle\Imagine\PathResolver\DefaultResolver;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sil\Bundle\MediaBundle\Entity\File;

class ProductPathResolver extends DefaultResolver implements PathResolverInterface
{
    public function resolvePath($path)
    {
        try {
            if (!preg_match_all('/[0-9a-fA-F]*/', $path)) {
                // This requested path is not managed as ProductImage
                // ProductImages path are hexadecimals strings
                return parent::resolvePath($path);
            }

            $this->cacheFile = $this->findFile($path);

            if ($this->cacheFile !== null) {
                return $this->cacheFile->getFile();
            } else {
                // Cannot find the product image
                // So creating fake file for default product image
                $webPath = $this->webDir . '/' . $path;

                if (!is_file($webPath)) {
                    $webPath = $this->webDir . '/bundles/silecommerce/img/default-product-picture.png';
                }

                $fakeFile = new File();
                $fakeFile->setFile(base64_encode(file_get_contents($webPath)));
                $fakeFile->setMimeType(mime_content_type($webPath));

                $this->cacheFile = $fakeFile;
            }

            $webFilePath = $this->webDir . '/' . $path;

            if (is_file($webFilePath)) {
                $fakeFile = new File();
                $fakeFile->setFile(base64_encode(file_get_contents($webFilePath)));
                $fakeFile->setMimeType(mime_content_type($webFilePath));

                $this->cacheFile = $fakeFile;

                return $fakeFile->getFile();
            }

            if (null === $this->cacheFile) {
                throw new NotFoundHttpException(sprintf('File « %s » was not found', $path));
            } else {
                return $this->cacheFile->getFile();
            }
        } catch (\NotFoundHttpException $e) {
            throw new NotFoundHttpException(sprintf('File « %s » was not found', $path));
        }
    }

    protected function findFile($path)
    {
        $file = null;

        $qb = $this->em->createQueryBuilder();
        $subQb = $this->em->createQueryBuilder();

        $subQb
            ->select('f')
            ->from('SilEcommerceBundle:ProductImage', 'pi')
            ->join('SilMediaBundle:File', 'f', 'WITH', 'pi.realFile = f')
            ->where('pi.path = :path')
            ->setParameter('path', $path);

        $file = $subQb->getQuery()->getOneOrNullResult();

        if (!$file) {
            $qb
                ->select('f')
                ->from('SilMediaBundle:File', 'f')
                ->where('f.path = :path')
                ->setParameter('path', $path);

            $file = $qb->getQuery()->getOneOrNullResult();
        }

        return $file;
    }
}
