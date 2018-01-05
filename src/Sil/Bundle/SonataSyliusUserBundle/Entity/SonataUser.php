<?php

/*
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\SonataSyliusUserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\User\Model\User as SyliusUser;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class SonataUser extends SyliusUser implements SonataUserInterface
{
    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var Collection
     */
    protected $groups;

    /**
     * @var string
     */
    protected $localeCode;

    public function __construct()
    {
        parent::__construct();

        $this->roles = [SonataUserInterface::DEFAULT_ADMIN_ROLE];
        $this->users = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $names = [];
        if ($this->getFirstName()) {
            $names[] = $this->getFirstName();
        }
        if ($this->getLastName()) {
            $names[] = $this->getLastName();
        }
        if ($names) {
            return implode(' ', $names);
        } elseif ($this->getUsername()) {
            return (string) $this->getUsername();
        } else {
            return (string) $this->getEmail();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleCode()
    {
        return $this->localeCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocaleCode($code)
    {
        $this->localeCode = $code;
    }

    /**
     * Returns the user roles (including the roles provided by its groups).
     *
     * @return array The roles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * Gets the groups granted to the user.
     *
     * @return Collection
     */
    public function getGroups()
    {
        return $this->groups ?: $this->groups = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function getGroupNames(): array
    {
        $names = array();
        foreach ($this->getGroups() as $group) {
            $names[] = $group->getName();
        }

        return $names;
    }

    /**
     * @return bool
     */
    public function hasGroup($name)
    {
        return in_array($name, $this->getGroupNames());
    }

    /**
     * @param SonataGroupInterface $group
     *
     * @return self
     */
    public function addGroup(SonataGroupInterface $group)
    {
        if (!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }

        return $this;
    }

    /**
     * @param SonataGroupInterface $group
     *
     * @return self
     */
    public function removeGroup(SonataGroupInterface $group)
    {
        if ($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isLocked(): bool
    {
        return (bool) $this->locked;
    }

    /**
     * {@inheritdoc}
     */
    public function isExpired(): bool
    {
        return (bool) ($this->expiresAt !== null ? $this->expiresAt < new \DateTime() : false);
    }
}
