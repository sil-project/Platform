<?php

declare(strict_types=1);

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\ProductBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Sil\Component\Product\Model\ProductInterface;
use Sil\Component\Product\Repository\ProductRepositoryInterface;
use Blast\Component\Code\Repository\CodeAwareRepositoryInterface;
use Blast\Component\Code\Model\CodeInterface;
use Blast\Component\Resource\Model\ResourceInterface;
use Blast\Bundle\ResourceBundle\Doctrine\ORM\Repository\ResourceRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;

class ProductRepository extends ResourceRepository implements ProductRepositoryInterface, CodeAwareRepositoryInterface
{
    /**
     * Returns a paginated (KnpPaginator) list of resource.
     *
     * @return PaginationInterface
     */
    public function findAllPaginated(int $page = 1, int $perPage = 20): PaginationInterface
    {
        $qb = $this->createQueryBuilder('o');
        $qb->select('o');

        return $this->paginator->paginate($qb->getQuery(), $page, $perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function findProductByCode(string $productCode): ?ProductInterface
    {
        return $this->findOneBy(['code.value' => $productCode]);
    }

    /**
     * {@inheritdoc}
     */
    public function getEnabledProducts(): Collection
    {
        return $this->findBy(['enabled' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function getDisabledProducts(): Collection
    {
        return $this->findBy(['enabled' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByCodeValue(string $code): ?ResourceInterface
    {
        return $this->findProductByCode($code);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByCode(CodeInterface $code): ?ResourceInterface
    {
        return $this->findProductByCode($code->getValue());
    }
}
