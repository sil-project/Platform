<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace  Sil\Bundle\ProductBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Blast\Bundle\TestsBundle\Functional\BlastWebTestCaseTrait;

/**
 * Needed as php allows only one session per process.
 *
 * @runTestsInSeparateProcesses
 */
class ProductTest extends WebTestCase
{
    use BlastWebTestCaseTrait;

    protected $productName = 'PHPUnitWebTestCase';

    public function test_product_pages()
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $this->runForRoute('sil_product_create', [], 'sil.product.product.create.page_header.title');
        $this->runForRoute('sil_product_list', [], 'sil.product.product.list.page_header.title');
        $this->runForRoute('sil_product_homepage', [], 'sil.product.product.list.page_header.title');
    }

    /**
     * @depends test_product_pages
     */
    public function test_product_create_without_options()
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $crawler = $this->goToRoute('sil_product_create');

        $form = $crawler->filter('form[name="product_create"]')->form();
        $form->setValues(array(
            'product_create[name]' => $this->productName,
        ));

        $crawler = $this->client->submit($form);

        if ($this->client->getResponse()->isRedirect()) {
            $crawler = $this->client->followRedirect();
        }

        $ref = trim($crawler->filter('table.ui.table.show.data > tbody > tr')->eq(1)->filter('td')->eq(1)->text());

        $this->assertEquals(
            $this->container->get('translator')->trans('sil.product.product.show.page_header.title', ['%ref%' => $ref]),
            trim(preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $crawler->filter('h1.ui.header > div.content')->html()))
        );

        $this->assertEquals(
            $this->productName,
            trim(preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $crawler->filter('table.ui.table.show.data > tbody > tr')->first()->filter('td')->eq(1)->text()))
        );

        $this->getDeleteCreatedProducts();
    }

    private function getDeleteCreatedProducts(): void
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $productRepository = $this->container->get('sil.repository.product');
        $productVariantRepository = $this->container->get('sil.repository.product_variant');

        $products = $this->container->get('sil.repository.product')->findBy(['name' => $this->productName]);

        foreach ($products as $product) {
            foreach ($product->getVariants() as $variant) {
                $productVariantRepository->remove($variant);
            }
            $productRepository->remove($product);
        }
    }
}
