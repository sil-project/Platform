<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\bundle\MenuBundle\Tests\Functional;

use Blast\Bundle\TestsBundle\Functional\BlastTestCase;
use Blast\Bundle\MenuBundle\Model\Item;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoaderTestCase extends BlastTestCase
{
    /**
     * @var array
     */
    private $data;

    public function testParametersLoaderLoad()
    {
        $parameterLoader = $this->container->get('blast.menu_loader');

        $parameterLoader->setParameters($this->getTestParameter());

        $output = $parameterLoader->load();

        $this->assertEquals($output, $this->getOuputMenuTree());
    }

    private function initBaseTestParameters()
    {
        $this->data = [];

        for ($i = 4; $i > 0; --$i) {
            $key = 'item_' . $i;
            $this->data[$key] = [
                'label' => $key,
                'order' => ($i * 10),
                'icon'  => 'icon-' . $i,
                'route' => 'route-' . $i,
            ];
        }
    }

    public function getTestParameter()
    {
        $this->initBaseTestParameters();

        return [
            'root' => [
                'item_1' => [
                    'icon'     => $this->data['item_1']['icon'],
                    'order'    => $this->data['item_1']['order'],
                    'children' => [
                        'item_2' => [
                            'label' => 'item_2_custom_label',
                            'icon'  => $this->data['item_2']['icon'],
                            'route' => $this->data['item_2']['route'],
                            'order' => $this->data['item_2']['order'],
                        ],
                    ],
                ],
            ],
            'settings' => [
                'item_3' => [
                    'icon'     => $this->data['item_3']['icon'],
                    'order'    => $this->data['item_3']['order'],
                    'children' => [
                        'item_4' => [
                            'icon'  => $this->data['item_4']['icon'],
                            'route' => $this->data['item_4']['route'],
                            'order' => $this->data['item_4']['order'],
                            'roles' => [
                                'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getOuputMenuTree()
    {
        $this->initBaseTestParameters();

        $sil_menu_root = new Item('sil_menu_root');

        $root = new Item('root');

        $item1 = new Item('item_1');
        $item1->setIcon($this->data['item_1']['icon']);
        $item1->setOrder($this->data['item_1']['order']);

        $item1_subitem1 = new Item('item_2');
        $item1_subitem1->setLabel('item_2_custom_label');
        $item1_subitem1->setIcon($this->data['item_2']['icon']);
        $item1_subitem1->setRoute($this->data['item_2']['route']);
        $item1_subitem1->setOrder($this->data['item_2']['order']);

        $item1->addChild($item1_subitem1);

        $root->addChild($item1);

        $settings = new Item('settings');

        $item2 = new Item('item_3');
        $item2->setIcon($this->data['item_3']['icon']);
        $item2->setOrder($this->data['item_3']['order']);

        $item2_subitem1 = new Item('item_4');
        $item2_subitem1->setIcon($this->data['item_4']['icon']);
        $item2_subitem1->setRoute($this->data['item_4']['route']);
        $item2_subitem1->setOrder($this->data['item_4']['order']);
        $item2_subitem1->addRole('ROLE_SUPER_ADMIN');

        $item2->addChild($item2_subitem1);

        $settings->addChild($item2);

        $sil_menu_root->addChild($root);
        $sil_menu_root->addChild($settings);

        return $sil_menu_root;
    }
}
