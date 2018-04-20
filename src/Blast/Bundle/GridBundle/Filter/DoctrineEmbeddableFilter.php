<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\GridBundle\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filter\StringFilter;

class DoctrineEmbeddableFilter implements FilterInterface
{
    /**
     * Apply filter.
     *
     * @param DataSourceInterface $dataSource
     * @param string              $name
     * @param [type]              $data
     * @param array               $options
     */
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options): void
    {
        if (!array_key_exists('embbedable_property', $options)) {
            throw new \LogicException('The grid filter option « embbedable_property » must be set');
        }
        if (!array_key_exists('type', $data)) {
            $data['type'] = StringFilter::TYPE_CONTAINS;
        }

        $expression = $this->getExpression(
            $dataSource->getExpressionBuilder(),
            $data['type'],
            'o.' . $name . '.' . $options['embbedable_property'],
            $data['value']
        );

        $dataSource->restrict($expression);
    }

    /**
     * Picked from https://github.com/Sylius/Sylius/blob/master/src/Sylius/Component/Grid/Filter/StringFilter.php.
     *
     * @param ExpressionBuilderInterface $expressionBuilder
     * @param string                     $type
     * @param string                     $field
     * @param mixed                      $value
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    private function getExpression(
        ExpressionBuilderInterface $expressionBuilder,
        string $type,
        string $field,
        $value
    ) {
        switch ($type) {
            case StringFilter::TYPE_EQUAL:
                return $expressionBuilder->equals($field, $value);
            case StringFilter::TYPE_NOT_EQUAL:
                return $expressionBuilder->notEquals($field, $value);
            case StringFilter::TYPE_EMPTY:
                return $expressionBuilder->isNull($field);
            case StringFilter::TYPE_NOT_EMPTY:
                return $expressionBuilder->isNotNull($field);
            case StringFilter::TYPE_CONTAINS:
                return $expressionBuilder->like($field, '%' . $value . '%');
            case StringFilter::TYPE_NOT_CONTAINS:
                return $expressionBuilder->notLike($field, '%' . $value . '%');
            case StringFilter::TYPE_STARTS_WITH:
                return $expressionBuilder->like($field, $value . '%');
            case StringFilter::TYPE_ENDS_WITH:
                return $expressionBuilder->like($field, '%' . $value);
            case StringFilter::TYPE_IN:
                return $expressionBuilder->in($field, array_map('trim', explode(',', $value)));
            case StringFilter::TYPE_NOT_IN:
                return $expressionBuilder->notIn($field, array_map('trim', explode(',', $value)));
            default:
                throw new \InvalidArgumentException(sprintf('Could not get an expression for type "%s"!', $type));
        }
    }
}
