<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ContactBundle\Tests\Functional\Form\Type;

use Symfony\Component\Form\FormFactory;
use Blast\Bundle\TestsBundle\Functional\BlastTestCase;
use Sil\Bundle\ContactBundle\Entity\Address;
use Sil\Bundle\ContactBundle\Entity\City;
use Sil\Bundle\ContactBundle\Entity\Country;
use Sil\Bundle\ContactBundle\Entity\Province;
use Sil\Bundle\ContactBundle\Form\Type\AddressType;

/**
 * @author Romain Sanchez <romain.sanchez@libre-informatique.fr>
 *
 * @coversDefaultClass \Sil\Bundle\ContactBundle\Form\Type\AddressType
 */
class AddressTypeTest extends BlastTestCase
{
    /**
     * Form factory.
     *
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var string
     */
    protected $province;

    /**
     * @var string
     */
    protected $country;

    public function setUp()
    {
        parent::setup();

        $this->formFactory = $this->container->get('form.factory');

        $this->launchCommand([
        'command'          => 'blast:import:csv',
        '--dir'            => $this->locator->locate('@SilContactBundle/Resources/import'),
        '--mapping'        => $this->locator->locate('@SilContactBundle/Resources/config/vendor/blast/import.yml'),
        '--no-interaction' => true,
        '--env'            => 'test',
        ]);

        $this->province = $this->container
            ->get('sil.repository.contact_province')
            ->findOneBy(['name' => 'Bretagne'])
        ;

        $this->country = $this->container
            ->get('sil.repository.contact_country')
            ->findOneBy(['name' => 'France'])
        ;
    }

    public function test_submit()
    {
        // Data that will be submitted
        $formData = [
            'street'     => 'foo street',
            'country'    => $this->country->getId(),
            'province'   => $this->province->getId(),
            'city'       => [
                'name'     => 'foo town',
                'postCode' => '55000',
            ],
            'type'       => Address::TYPE_HOME,
        ];

        // Object with wich the object returned by the form will be compared to
        $standard = new Address(
            $formData['street'],
            new City(
                $formData['city']['name'],
                $formData['city']['postCode']
            ),
            $this->country->getName()
        );

        $standard->setProvince($this->province->getName());
        $standard->setType($formData['type']);

        $form = $this->formFactory->create(AddressType::class, []);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($form->getData(), $standard);

        // https://github.com/symfony/symfony/issues/24524
        // $view = $form->createView();
        //
        // foreach (array_keys($formData) as $key) {
        //     $this->assertArrayHasKey($key, $view->children);
        // }
    }
}
