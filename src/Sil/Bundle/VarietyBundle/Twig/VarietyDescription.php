<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\VarietyBundle\Twig;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Sil\Bundle\VarietyBundle\Entity\Variety;
use Sil\Bundle\VarietyBundle\Entity\VarietyDescription as VarietyDescriptionEntity;

class VarietyDescription extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    private $varietyConfig;

    /**
     * @var VarietyDescriptionEntity
     */
    private $fieldDescription;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getDescription', [$this, 'getDescription'], ['is_safe'=>['html']]),
            new \Twig_SimpleFunction('getDescriptionValue', [$this, 'getDescriptionValue'], ['is_safe'=>['html']]),
            new \Twig_SimpleFunction('sortVarietyDescriptions', [$this, 'sortVarietyDescriptions'], ['is_safe'=>['html']]),
            new \Twig_SimpleFunction('displayVarietyDescriptionLabel', [$this, 'displayVarietyDescriptionLabel'], ['is_safe'=>['html']]),
            new \Twig_SimpleFunction('displayVarietyDescriptionValue', [$this, 'displayVarietyDescriptionValue'], ['is_safe'=>['html']]),
        ];
    }

    public function getDescription($variety, $fieldSet, $field)
    {
        $propertyAccessor = new PropertyAccessor();
        $descriptions = $propertyAccessor->getValue($variety, $fieldSet . '_descriptions');

        $description = $descriptions->filter(function ($desc) use ($field) {
            return $desc->getField() === $field;
        })->first();

        return $description;
    }

    public function getDescriptionValue($variety, $fieldSet, $field)
    {
        if ($variety === null) {
            return null;
        }

        $value = null;
        $desc = $this->getDescription($variety, $fieldSet, $field);

        if ($desc) {
            $value = $desc->getValue();
        }

        return $value;
    }

    public function sortVarietyDescriptions(Variety $variety, string $fieldSet): string
    {
        $config = $this->getVarietyConfiguration();

        if (isset($config[$fieldSet])) {
            $keys = array_flip(array_keys($config[$fieldSet]));
            $sortedArray = [];
            $propertyAccessor = new PropertyAccessor();
            $descriptions = $propertyAccessor->getValue($variety, $fieldSet . '_descriptions')->toArray();

            array_walk($descriptions, function ($item) use ($keys, &$sortedArray) {
                $field = $item->getField();
                $sortedArray[$keys[$field]] = $item;
            });

            ksort($sortedArray);

            $propertyAccessor->getValue($variety, $fieldSet . '_descriptions')->clear();

            foreach ($sortedArray as $desc) {
                $propertyAccessor->setValue($variety, 'add' . $fieldSet . '_description', $desc);
            }
        }

        return '';
    }

    /**
     * @param VarietyDescriptionEntity $varietyDescription
     *
     * @return string
     */
    public function displayVarietyDescriptionLabel(VarietyDescriptionEntity $varietyDescription): string
    {
        return $this->displayVarietyDescription($varietyDescription, true);
    }

    /**
     * @param VarietyDescriptionEntity $varietyDescription
     *
     * @return string
     */
    public function displayVarietyDescriptionValue(VarietyDescriptionEntity $varietyDescription): string
    {
        return $this->displayVarietyDescription($varietyDescription, false);
    }

    /**
     * @param VarietyDescriptionEntity $varietyDescription
     * @param bool                     $label
     *
     * @return string
     */
    private function displayVarietyDescription(VarietyDescriptionEntity $varietyDescription, $displayLabel = false): string
    {
        $this->initFieldDescription($varietyDescription);

        if ($displayLabel) {
            $help = $this->translator->trans('sil.help.' . $varietyDescription->getField(), [], 'messages');
            $label = $this->translator->trans('sil_description_' . $this->varietyDescription->getFieldset() . '_' . $varietyDescription->getField(), [], 'messages');

            if ($help != ' ') {
                $label .= ' <small>(' . $help . ')</small>';
            }

            return $label;
        } else {
            return $this->handleValueWidget();
        }
    }

    private function handleValueWidget(): string
    {
        $config = $this->getVarietyFieldConfiguration();
        $field = $this->varietyDescription->getField();
        $value = $this->varietyDescription->getValue();

        if (isset($config[$field]['options'])) {
            $options = $config[$field]['options'];

            if (isset($options['multiple']) && $options['multiple'] === true && strpos($value, '||') !== false) {
                $list = explode('||', $value);

                $list = is_array($list) ? $list : [];

                $out = '<div class="ui list divided">';

                foreach ($list as $item) {
                    $out .= '<div class="item">';
                    $out .= '    <i class="caret right icon"></i> ' . $item;
                    $out .= '</div>';
                }

                return $out .= '</div>';
            }
        }

        return (string) $value;
    }

    private function initFieldDescription(VarietyDescriptionEntity $varietyDescription)
    {
        $varietyDescription->setFieldset($varietyDescription->getFieldset());

        $this->varietyDescription = $varietyDescription;
    }

    /**
     * @return array|null
     */
    private function getVarietyFieldConfiguration(): ?array
    {
        $config = $this->getVarietyConfiguration();

        if (array_key_exists($this->varietyDescription->getFieldset(), $config)) {
            $configFieldset = $config[$this->varietyDescription->getFieldSet()];

            if (array_key_exists($this->varietyDescription->getField(), $configFieldset)) {
                return [
                    $this->varietyDescription->getField() => $configFieldset[$this->varietyDescription->getField()],
                ];
            }

            return null;
        }

        return null;
    }

    private function getVarietyConfiguration()
    {
        return $this->varietyConfig['variety_descriptions'];
    }

    public function setVarietyConfig($config)
    {
        $this->varietyConfig = $config;
    }

    /**
     * @param TranslatorInterface translator
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
