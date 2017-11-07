<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Controller;

use Sil\Bundle\MediaBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sil\Bundle\EcommerceBundle\Entity\Product;
use Sil\Bundle\EcommerceBundle\Entity\ProductImage;

/**
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class ProductCRUDController extends CRUDController
{
    /**
     * Generate product variant, based on product options.
     *
     * @todo !!
     */
    public function generateVariantsAction(Request $request)
    {
    }

    public function generateProductSlugAction(Request $request)
    {
        $name = (string) $request->query->get('name');

        return new JsonResponse(
            [
            'slug' => $this->get('sylius.generator.slug')->generate($name),
            ]
        );
    }

    protected function handleFiles($object)
    {
        $request = $this->getRequest();
        /* @var $product Product */
        $product = $object;

        $rc = new \ReflectionClass($object);
        $className = $rc->getShortName();

        $repo = $this->manager->getRepository('SilMediaBundle:File');

        if (null !== $remove = $request->get('remove_files')) {
            foreach ($remove as $key => $id) {
                $file = $repo->find($id);

                if ($file) {
                    if (method_exists($product, 'removeLibrinfoFile')) {
                        $product->removeLibrinfoFile($this->getProductImageEntity($file, $product));
                        $this->manager->remove($file);
                    } elseif (method_exists($product, 'setLibrinfoFile')) {
                        $product->setLibrinfoFile($this->getProductImageEntity($file, $product));
                        $this->manager->remove($file);
                    } else {
                        throw new \Exception('You must define ' . $className . '::removeLibrinfoFile() method or ' . $className . '::setFile() in case of a one to one');
                    }
                }
            }
        }

        if (null !== $ids = $request->get('add_files')) {
            foreach ($ids as $key => $id) {
                $file = $repo->find($id);

                if ($file) {
                    if (method_exists($product, 'addLibrinfoFile')) {
                        $product->addLibrinfoFile($this->getProductImageEntity($file, $product));
                        $file->setOwned(true);
                    } elseif (method_exists($product, 'setLibrinfoFile')) {
                        $product->setLibrinfoFile($this->getProductImageEntity($file, $product));
                        $file->setOwned(true);
                    } else {
                        throw new \Exception('You must define ' . $className . '::addLibrinfoFile() method or ' . $className . '::setLibrinfoFile() in case of a one to one');
                    }
                }
            }
        }
    }

    protected function getProductImageEntity($file, $product)
    {
        $productImage = new ProductImage();
        $productImage->setRealFile($file);
        $productImage->setOwner($product);
        $productImage->setPath($product->getSlug());

        return $productImage;
    }

    public function setAsCoverImageAction(Request $request)
    {
        $imageId = $request->request->get('imageId', null);

        $image = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('SilEcommerceBundle:ProductImage')
            ->findOneBy(['realFile' => $imageId]);

        if ($image) {
            $product = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('SilEcommerceBundle:Product')
                ->findOneBy(['id' => $image->getOwner()]);

            foreach ($product->getImages() as $img) {
                $img->setType(ProductImage::TYPE_THUMBNAIL);
            }

            $image->setType(ProductImage::TYPE_COVER);

            $this
                ->getDoctrine()
                ->getManager()
                ->flush();

            return new JsonResponse(['success' => $product]);
        } else {
            return new JsonResponse(['error' => 'Image not found']);
        }
    }
}
