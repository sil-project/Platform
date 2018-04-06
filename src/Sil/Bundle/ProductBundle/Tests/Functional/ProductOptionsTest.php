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
class ProductOptionsTest extends WebTestCase
{
    use BlastWebTestCaseTrait;

    protected $optionTypeName = 'PHPUnitWebTestCase';

    public function test_option_type_pages()
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $this->runForRoute('sil_product_option_create', [], 'sil.product.option.create.page_header.title');
        $this->runForRoute('sil_product_option_type_list', [], 'sil.product.option_type.list.page_header.title');
        $this->runForRoute('sil_product_option_type_create', [], 'sil.product.option_type.create.page_header.title');
        $this->runForRoute('sil_product_option_type_homepage', [], 'sil.product.option_type.list.page_header.title');
    }

    /**
     * @depends test_option_type_pages
     */
    public function test_option_type_create()
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $crawler = $this->goToRoute('sil_product_option_type_create');

        $form = $crawler->filter('form[name="product_option_type_create"]')->form();
        $form->setValues(array(
            'product_option_type_create[name]' => $this->optionTypeName,
        ));

        $crawler = $this->client->submit($form);

        if ($this->client->getResponse()->isRedirect()) {
            $crawler = $this->client->followRedirect();
        }

        $this->assertEquals(
            $this->container->get('translator')->trans('sil.product.option_type.show.page_header.title'),
            trim(preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $crawler->filter('h1.ui.header > div.content')->html()))
        );

        $this->assertEquals(
            $this->optionTypeName,
            trim(preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $crawler->filter('table.ui.table.show.data > tbody > tr')->first()->filter('td')->eq(1)->text()))
        );
    }

    /**
     * @depends test_option_type_create
     */
    public function test_option_create_for_specific_option_type()
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $crawler = $this->goToRoute('sil_product_option_type_list');

        $rows = $crawler->filter('table.ui.very.compact.small.celled.striped.stackable.table.selectable > tbody > tr');

        $linkUri = null;
        foreach ($rows as $row) {
            $name = null;
            $tds = $row->getElementsByTagName('td');

            $nameSpan = $tds->item(1)->getElementsByTagName('span');
            if ($nameSpan->length > 0) {
                $name = $nameSpan->item(0)->textContent;
            }

            if ($name === $this->optionTypeName) {
                $linkUri = $tds->item($tds->length - 1)->getElementsByTagName('a')->item(0)->getAttribute('href');
            }
        }

        if ($linkUri !== null) {
            $crawler = $this->goToUri($linkUri);

            $createLinkText = $this->container->get('translator')->trans('sil.product.option_type.actions.create_new_option_for_this_type');

            $link = $crawler->filterXpath('//a[contains(.,"' . $createLinkText . '")]')->link();

            $crawler = $this->goToUri($link->getUri());

            $selectedOptionTypeName = $crawler->filter('select[name="product_option_create[optionType]"] option[selected="selected"]')->text();

            $this->assertEquals(
                $this->optionTypeName,
                $selectedOptionTypeName
            );
        } else {
            $this->fail(sprintf('Cannot find created option type named « %s »', $this->optionTypeName));
        }
    }

    /**
     * @depends test_option_create_for_specific_option_type
     */
    public function test_delete_option_type()
    {
        $this->markTestSkipped(
            'work only if there is no login in the application'
        );
        $crawler = $this->goToRoute('sil_product_option_type_list');

        $rows = $crawler->filter('table.ui.very.compact.small.celled.striped.stackable.table.selectable > tbody > tr');

        $linkUri = null;
        foreach ($rows as $row) {
            $name = null;
            $tds = $row->getElementsByTagName('td');

            $nameSpan = $tds->item(1)->getElementsByTagName('span');
            if ($nameSpan->length > 0) {
                $name = $nameSpan->item(0)->textContent;
            }

            if ($name === $this->optionTypeName) {
                $linkUri = $tds->item($tds->length - 1)->getElementsByTagName('a')->item(0)->getAttribute('href');
            }
        }

        if ($linkUri !== null) {
            $crawler = $this->goToUri($linkUri);

            $deleteLinkText = $this->container->get('translator')->trans('sil.product.option_type.actions.remove');

            $link = $crawler->filterXpath('//a[contains(.,"' . $deleteLinkText . '")]')->link();

            $crawler = $this->goToUri($link->getUri());

            $listPageTitle = $this->container->get('translator')->trans('sil.product.option_type.list.page_header.title');

            $this->assertEquals(
                $listPageTitle,
                trim(preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $crawler->filter('h1.ui.header > div.content')->html()))
            );
        } else {
            $this->fail(sprintf('Cannot find created option type named « %s »', $this->optionTypeName));
        }
    }
}
