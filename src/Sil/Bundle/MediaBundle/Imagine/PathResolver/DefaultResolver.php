<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\MediaBundle\Imagine\PathResolver;

use Doctrine\ORM\EntityManager;
use Sil\Bundle\MediaBundle\Entity\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultResolver implements PathResolverInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var File
     */
    protected $cacheFile;

    /**
     * @var string
     */
    protected $webDir;

    public function resolvePath($id)
    {
        try {
            $repo = $this->em->getRepository('SilMediaBundle:File');

            if (!$this->cacheFile) {
                /* @var $this->cacheFile File */
                $this->cacheFile = $repo->find($id);
            }

            return $this->cacheFile->getFile();
        } catch (\Exception $e) {
            throw new NotFoundHttpException(sprintf('File « %s » was not found', $id));
        }
    }

    public function resolveMime($id)
    {
        try {
            $this->resolvePath($id);

            return $this->cacheFile->getMimeType();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * setEm(EntityManager $em).
     *
     * @param EntityManager $em
     *
     * @return $this
     */
    public function setEm(EntityManager $em)
    {
        $this->em = $em;

        return $this;
    }

    /**
     * setWebDir.
     *
     * @param string $webDir
     */
    public function setWebDir($webDir)
    {
        $this->webDir = $webDir;

        return $this;
    }
}
