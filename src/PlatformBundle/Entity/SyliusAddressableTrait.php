<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Entity;

use Sylius\Component\Core\Model\AddressInterface;
use Doctrine\Common\Collections\Collection;

trait SyliusAddressableTrait
{
    /**
     * @var AddressInterface
     */
    protected $defaultAddress;

    /**
     * @var Collection|AddressInterface[]
     */
    protected $addresses;

    /**
     * @param AddressInterface $defaultAddress
     *
     * @return self
     */
    public function setDefaultSyliusAddress(AddressInterface $defaultAddress = null)
    {
        $this->defaultAddress = $defaultAddress;

        if (null !== $defaultAddress) {
            $this->addAddress($defaultAddress);
        }

        return $this;
    }

    /**
     * @return AddressInterface
     */
    public function getDefaultAddress(): ?AddressInterface
    {
        return $this->defaultAddress;
    }

    /**
     * @param AddressInterface $defaultAddress
     *
     * @return self
     */
    public function setDefaultAddress(?AddressInterface $defaultAddress = null): void
    {
        $this->defaultAddress = $defaultAddress;

        if (null !== $defaultAddress) {
            $this->addAddress($defaultAddress);
        }
    }

    /**
     * @param AddressInterface $address
     *
     * @return self
     */
    public function addAddress(AddressInterface $address): void
    {
        if (!$this->hasAddress($address)) {
            if ($this->isIndividual()) {
                if ($address->getFirstName() === null || $address->getLastName() === null) {
                    $address->setFirstName($this->getFirstName());
                    $address->setLastName($this->getLastName());
                }
            } else {
                if ($address->getLastName() === null) {
                    $address->setLastName($this->getName());
                }
            }

            $this->addresses->add($address);
            $address->setCustomer($this);

            if (!$this->getDefaultAddress()) {
                $this->setDefaultAddress($address);
            }
        }
    }

    /**
     * @param AddressInterface $address
     *
     * @return self
     */
    public function removeAddress(AddressInterface $address): void
    {
        $this->addresses->removeElement($address);

        if ($address !== null && $this->defaultAddress !== null && $address->getId() == $this->defaultAddress->getId()) {
            if ($this->addresses->count() > 0) {
                $this->defaultAddress = $this->addresses[0];
            } else {
                $this->defaultAddress = null;
            }
        }
    }

    /**
     * @param AddressInterface $address
     *
     * @return bool
     */
    public function hasAddress(AddressInterface $address): bool
    {
        return $this->addresses->contains($address);
    }

    /**
     * @return Collection|AddressInterface[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }
}
