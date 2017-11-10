<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Form\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Bundle\CoreBundle\Form\Type\Customer\CustomerGuestType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CustomerGuestTypeExtension extends AbstractTypeExtension
{
    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * @var FactoryInterface
     */
    private $customerFactory;

    /**
     * @param string              $dataClass
     * @param array               $validationGroups
     * @param RepositoryInterface $customerRepository
     * @param FactoryInterface    $customerFactory
     */
    public function __construct(
        RepositoryInterface $customerRepository,
        FactoryInterface $customerFactory
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
                $data = $event->getData();
                $form = $event->getForm();

                if (!isset($data['email'])) {
                    return;
                }

                $customer = $this->customerRepository->findOneBy(['email' => $data['email']]);

                // assign existing customer or create a new one
                $form = $event->getForm();
                if (null !== $customer && null === $customer->getUser()) {
                    $form->setData($customer);

                    return;
                }

                $customer = $this->customerFactory->createNew();
                $customer->setEmail($data['email']);

                $addressType = $form->getParent()->get('billingAddress');
                /** @var AddressInterface $address */
                $address = $addressType->getData();

                $customer->setFirstname($address->getFirstName());
                $customer->setLastname($address->getLastName());
                $customer->setIsIndividual(true);
                $customer->setIsCustomer(true);

                $form->setData($customer);
            })
            ->setDataLocked(false)
        ;
    }

    public function getExtendedType()
    {
        return CustomerGuestType::class;
    }
}
