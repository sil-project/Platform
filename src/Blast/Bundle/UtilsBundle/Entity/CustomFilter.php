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

namespace Blast\UtilsBundle\Entity;

use Blast\BaseEntitiesBundle\Entity\Traits\BaseEntity;
use Blast\CoreBundle\Model\UserInterface;

/**
 * CustomFilter.
 */
class CustomFilter
{
    use BaseEntity;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $routeName;

    /**
     * @var string
     */
    private $routeParameters;

    /**
     * @var string
     */
    private $filterParameters;

    /**
     * @var mixed|UserInterface
     */
    private $user;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set routeName.
     *
     * @param string $routeName
     *
     * @return CustomFilter
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }

    /**
     * Get routeName.
     *
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Set routeParameters.
     *
     * @param string|mixed $routeParameters
     *
     * @return CustomFilter
     */
    public function setRouteParameters($routeParameters)
    {
        if (!is_string($routeParameters)) {
            $this->routeParameters = json_encode($routeParameters);
        } else {
            $this->routeParameters = $routeParameters;
        }

        return $this;
    }

    /**
     * Get routeParameters.
     *
     * @return string
     */
    public function getRouteParameters()
    {
        return is_string($this->routeParameters) ? (array) json_decode($this->routeParameters) : $this->routeParameters;
    }

    /**
     * Set filterParameters.
     *
     * @param string|mixed $filterParameters
     *
     * @return CustomFilter
     */
    public function setFilterParameters($filterParameters)
    {
        if (!is_string($filterParameters)) {
            $this->filterParameters = json_encode($filterParameters);
        } else {
            $this->filterParameters = $filterParameters;
        }

        return $this;
    }

    /**
     * Get filterParameters.
     *
     * @return string
     */
    public function getFilterParameters()
    {
        return is_string($this->filterParameters) ? (array) json_decode($this->filterParameters) : $this->filterParameters;
    }

    /**
     * @return mixed|UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed|UserInterface user
     *
     * @return self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
