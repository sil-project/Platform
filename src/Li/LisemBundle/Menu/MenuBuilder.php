<?php

/*
 * This file is part of the Lisem Project.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace LisemBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Extension for Sonata Admin sidebar menu.
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 *
 * @todo Put this in SilUIBundle
 */
class MenuBuilder
{
    private $factory;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'homepage'));
        // ... add more children

        return $menu;
    }

    /**
     * App settings sidebar menu.
     *
     * @param RequestStack $requestStack
     *
     * @return type
     *
     * @todo Import YML description instead of hardcoded menus
     */
    public function createParametersSidebarMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('lisem.menu_label.app_settings');

        // Admin settings

        // TODO (we are not using SilUserBundle any more. Adapt this for SonataSyliusUserBundle) :
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN', $this->tokenStorage->getToken()->getUser())) {
            $submenu = $menu->addChild('lisem.menu_label.admin_settings');
            $submenu->addChild('lisem.menu_label.user_users', ['route' => 'admin_platform_silsonatasyliususerbundle_sonatauser_list']);
            //$submenu->addChild('lisem.menu_label.user_groups', ['route' => 'admin_sil_user_group_list']);
            //$submenu->addChild('lisem.menu_label.user_roles', ['route' => 'lisem_not_implemented']);
        }

        // CRM settings
        $submenu = $menu->addChild('lisem.menu_label.crm_settings');
        $submenu->addChild('lisem.menu_label.crm_circles_list', ['route' => 'admin_sil_crm_circle_list']);
        // $submenu->addChild('lisem.menu_label.crm_categories_list', ['route' => 'admin_sil_crm_category_list']);
        $submenu->addChild('lisem.menu_label.position_types_list', ['route' => 'admin_sil_crm_positiontype_list']);
        // $submenu->addChild('lisem.menu_label.phone_types_list', ['route' => 'admin_sil_crm_phonetype_list']);

        // Varieties settings
        $submenu = $menu->addChild('lisem.menu_label.varieties_settings');
        $submenu->addChild('lisem.menu_label.families_list', ['route' => 'admin_platform_silvarietybundle_family_list']);
        $submenu->addChild('lisem.menu_label.genuses_list', ['route' => 'admin_platform_silvarietybundle_genus_list']);
        $submenu->addChild('lisem.menu_label.plant_categories_list', ['route' => 'admin_sil_variety_plantcategory_list']);

        // Seed batches settings
        $submenu = $menu->addChild('lisem.menu_label.seed_batches_settings');
        $submenu->addChild('lisem.menu_label.certifications_list', ['route' => 'admin_sil_seedbatch_certificationtype_list']);
        $submenu->addChild('lisem.menu_label.certificators_list', ['route' => 'admin_sil_seedbatch_certifyingbody_list']);

        // Shop settings
        $submenu = $menu->addChild('lisem.menu_label.shop_settings');
        $submenu->addChild('lisem.menu_label.channels_list', ['route' => 'admin_sil_ecommerce_channel_list']);
        $submenu->addChild('lisem.menu_label.taxon_list', ['route' => 'admin_sil_ecommerce_taxon_list']);
        $submenu->addChild('lisem.menu_label.product_attributes', ['route' => 'admin_sil_ecommerce_productattribute_list']);
        $submenu->addChild('lisem.menu_label.product_options', ['route' => 'admin_sil_ecommerce_productoption_list']);
        // $submenu->addChild('lisem.menu_label.rates_list', ['route' => 'lisem_not_implemented']);
        $submenu->addChild('lisem.menu_label.zones_list', ['route' => 'admin_sil_ecommerce_zone_list']);
        // $submenu->addChild('lisem.menu_label.customer_group', ['route' => 'admin_sil_ecommerce_customergroup_list']);
        $submenu->addChild('lisem.menu_label.payment_methods_list', ['route' => 'admin_sil_ecommerce_payment_method_list']);
        $submenu->addChild('lisem.menu_label.shipping_methods_list', ['route' => 'admin_sil_ecommerce_shipping_method_list']);
        $submenu->addChild('lisem.menu_label.tax_categories_list', ['route' => 'admin_sil_ecommerce_taxcategory_list']);
        $submenu->addChild('lisem.menu_label.tax_rates_list', ['route' => 'admin_sil_ecommerce_taxrate_list']);
        $submenu->addChild('lisem.menu_label.shop_user', ['route' => 'admin_sil_ecommerce_shop_user_list']);

        // Stock settings
        /*
        $submenu = $menu->addChild('sil.stock.menu_label.stock_management_settings');
        $submenu->addChild('sil.stock.menu_label.warehouses', ['route' => 'admin_stock_warehouse_list']);
        $submenu->addChild('sil.stock.menu_label.locations', ['route' => 'admin_stock_location_list']);
        $submenu->addChild('sil.stock.menu_label.uom_types', ['route' => 'admin_stock_uomtype_list']);
        $submenu->addChild('sil.stock.menu_label.uoms', ['route' => 'admin_stock_uom_list']);
        */

        return $menu;
    }

    /**
     * @param TokenStorage $tokenStorage
     */
    public function setTokenStorage(TokenStorage $tokenStorage): void
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param AuthorizationChecker $authorizationChecker
     */
    public function setAuthorizationChecker(AuthorizationChecker $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }
}
