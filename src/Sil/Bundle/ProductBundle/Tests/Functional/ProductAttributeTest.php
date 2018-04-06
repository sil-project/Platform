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
 * Needed as php allow only one session by process.
 *
 * @runTestsInSeparateProcesses
 */
class ProductAttributeTest extends WebTestCase
{
    use BlastWebTestCaseTrait;

    protected $attributeTypeName = 'PHPUnitWebTestCase';

    public function test_attribute_pages()
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $this->runForRoute('sil_product_attribute_type_create', [], 'sil.product.attribute_type.create.page_header.title');
        $this->runForRoute('sil_product_attribute_type_list', [], 'sil.product.attribute_type.list.page_header.title');
        $this->runForRoute('sil_product_attribute_type_create', [], 'sil.product.attribute_type.create.page_header.title');
        $this->runForRoute('sil_product_attribute_type_homepage', [], 'sil.product.attribute_type.list.page_header.title');
    }

    /**
     * @depends test_attribute_pages
     */
    public function test_attribute_type_create()
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $crawler = $this->goToRoute('sil_product_attribute_type_create');

        $form = $crawler->filter('form[name="product_attribute_type_create"]')->form();
        $form->setValues(array(
            'product_attribute_type_create[name]'     => $this->attributeTypeName,
            'product_attribute_type_create[reusable]' => 1,
        ));

        $crawler = $this->client->submit($form);

        if ($this->client->getResponse()->isRedirect()) {
            $crawler = $this->client->followRedirect();
        }

        $this->assertEquals(
            $this->container->get('translator')->trans('sil.product.attribute_type.show.page_header.title'),
            trim(preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $crawler->filter('h1.ui.header > div.content')->html()))
        );

        $this->assertEquals(
            $this->attributeTypeName,
            trim(preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $crawler->filter('table.ui.table.show.data > tbody > tr')->first()->filter('td')->eq(1)->text()))
        );
    }

    /**
     * @depends test_attribute_type_create
     */
    public function test_attribute_create_for_specific_attribute_type()
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $crawler = $this->goToRoute('sil_product_attribute_type_list');

        $rows = $crawler->filter('table.ui.very.compact.small.celled.striped.stackable.table.selectable > tbody > tr');

        $linkUri = null;
        foreach ($rows as $row) {
            $name = null;
            $tds = $row->getElementsByTagName('td');

            $nameSpan = $tds->item(1)->getElementsByTagName('span');
            if ($nameSpan->length > 0) {
                $name = $nameSpan->item(0)->textContent;
            }

            if ($name === $this->attributeTypeName) {
                $linkUri = $tds->item($tds->length - 1)->getElementsByTagName('a')->item(0)->getAttribute('href');
            }
        }

        if ($linkUri !== null) {
            $crawler = $this->goToUri($linkUri);

            $createLinkText = $this->container->get('translator')->trans('sil.product.attribute_type.actions.create_new_attribute_for_this_type');

            $link = $crawler->filterXpath('//a[contains(.,"' . $createLinkText . '")]')->link();

            $crawler = $this->goToUri($link->getUri());

            $selectedattributeTypeName = $crawler->filter('select[name="product_attribute_create_reusable[attributeType]"] option[selected="selected"]')->text();

            $this->assertEquals(
                $this->attributeTypeName,
                $selectedattributeTypeName
            );
        } else {
            $this->fail(sprintf('Cannot find created attribute type named « %s »', $this->attributeTypeName));
        }
    }

    /**
     * @depends test_attribute_create_for_specific_attribute_type
     */
    public function test_delete_attribute_type()
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $crawler = $this->goToRoute('sil_product_attribute_type_list');

        $rows = $crawler->filter('table.ui.very.compact.small.celled.striped.stackable.table.selectable > tbody > tr');

        $linkUri = null;
        foreach ($rows as $row) {
            $name = null;
            $tds = $row->getElementsByTagName('td');

            $nameSpan = $tds->item(1)->getElementsByTagName('span');
            if ($nameSpan->length > 0) {
                $name = $nameSpan->item(0)->textContent;
            }

            if ($name === $this->attributeTypeName) {
                $linkUri = $tds->item($tds->length - 1)->getElementsByTagName('a')->item(0)->getAttribute('href');
            }
        }

        if ($linkUri !== null) {
            $crawler = $this->goToUri($linkUri);

            $deleteLinkText = $this->container->get('translator')->trans('sil.product.attribute_type.actions.remove');

            $link = $crawler->filterXpath('//a[contains(.,"' . $deleteLinkText . '")]')->link();

            $crawler = $this->goToUri($link->getUri());

            $listPageTitle = $this->container->get('translator')->trans('sil.product.attribute_type.list.page_header.title');

            $this->assertEquals(
                $listPageTitle,
                trim(preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $crawler->filter('h1.ui.header > div.content')->html()))
            );
        } else {
            $this->fail(sprintf('Cannot find created attribute type named « %s »', $this->attributeTypeName));
        }
    }
}
