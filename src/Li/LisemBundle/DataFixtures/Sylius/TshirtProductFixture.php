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

namespace LisemBundle\DataFixtures\Sylius;

use Sylius\Bundle\CoreBundle\Fixture\ProductAttributeFixture;
use Sylius\Bundle\CoreBundle\Fixture\ProductOptionFixture;
use Sylius\Bundle\CoreBundle\Fixture\TaxonFixture;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Attribute\AttributeType\TextAttributeType;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
final class TshirtProductFixture extends AbstractFixture
{
    /**
     * @var TaxonFixture
     */
    private $taxonFixture;

    /**
     * @var ProductAttributeFixture
     */
    private $productAttributeFixture;

    /**
     * @var ProductOptionFixture
     */
    private $productOptionFixture;

    /**
     * @var ProductFixture
     */
    private $productFixture;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    /**
     * @param TaxonFixture            $taxonFixture
     * @param ProductAttributeFixture $productAttributeFixture
     * @param ProductOptionFixture    $productOptionFixture
     * @param ProductFixture          $productFixture
     */
    public function __construct(
        TaxonFixture $taxonFixture,
        ProductAttributeFixture $productAttributeFixture,
        ProductOptionFixture $productOptionFixture,
        ProductFixture $productFixture
    ) {
        $this->taxonFixture = $taxonFixture;
        $this->productAttributeFixture = $productAttributeFixture;
        $this->productOptionFixture = $productOptionFixture;
        $this->productFixture = $productFixture;

        $this->faker = \Faker\Factory::create();
        $this->optionsResolver =
            (new OptionsResolver())
                ->setRequired('amount')
                ->setAllowedTypes('amount', 'int')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'lisem_tshirt_product';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $options): void
    {
        $options = $this->optionsResolver->resolve($options);

        $this->taxonFixture->load(['custom' => [[
            'code'     => 'category',
            'name'     => 'Catégorie',
            'children' => [
                [
                    'code'     => 't_shirts',
                    'name'     => 'T-Shirts',
                    'slug'     => 't-shirts',
                    'children' => [
                        [
                            'code' => 'mens_t_shirts',
                            'name' => 'Homme',
                        ],
                        [
                            'code' => 'womens_t_shirts',
                            'name' => 'Femme',
                        ],
                    ],
                ],
            ],
        ]]]);

        $this->productAttributeFixture->load(['custom' => [
            ['name' => 'Marque', 'code' => 't_shirt_brand', 'type' => TextAttributeType::TYPE],
            ['name' => 'Collection', 'code' => 't_shirt_collection', 'type' => TextAttributeType::TYPE],
            ['name' => 'Matériau', 'code' => 't_shirt_material', 'type' => TextAttributeType::TYPE],
        ]]);

        $this->productOptionFixture->load(['custom' => [
            [
                'name'   => 'Couleur',
                'code'   => 't_shirt_color',
                'values' => [
                    't_shirt_color_red'   => 'Rouge',
                    't_shirt_color_black' => 'Noir',
                    't_shirt_color_white' => 'Blanc',
                ],
            ],
            [
                'name'   => 'Taille',
                'code'   => 't_shirt_size',
                'values' => [
                    't_shirt_size_s'   => 'S',
                    't_shirt_size_m'   => 'M',
                    't_shirt_size_l'   => 'L',
                    't_shirt_size_xl'  => 'XL',
                    't_shirt_size_xxl' => 'XXL',
                ],
            ],
        ]]);

        $products = [];
        $productsNames = $this->getUniqueNames($options['amount']);
        for ($i = 0; $i < $options['amount']; ++$i) {
            $categoryTaxonCode = $this->faker->randomElement(['mens_t_shirts', 'womens_t_shirts']);

            $products[] = [
                'name'               => sprintf('T-Shirt "%s"', $productsNames[$i]),
                'code'               => sprintf('TSH-%04d', $i + 1),
                'main_taxon'         => $categoryTaxonCode,
                'taxons'             => [$categoryTaxonCode],
                'product_attributes' => [
                    't_shirt_brand'      => $this->faker->randomElement(['Nike', 'Adidas', 'JKM-476 Streetwear', 'Potato', 'Centipede Wear']),
                    't_shirt_collection' => sprintf('Sylius %s %s', $this->faker->randomElement(['Été', 'Hiver', 'Printemps', 'Automne']), mt_rand(1995, 2012)),
                    't_shirt_material'   => $this->faker->randomElement(['Centipede', 'Laine', 'Centipede 10% / Laine 90%', 'Pomme de terre 100%']),
                ],
                'product_options' => ['t_shirt_color', 't_shirt_size'],
                'images'          => [
                    [sprintf('%s/../../Resources/fixtures/%s', __DIR__, 't-shirts.jpg'), 'main'],
                    [sprintf('%s/../../Resources/fixtures/%s', __DIR__, 't-shirts.jpg'), 'thumbnail'],
                ],
            ];
        }

        $this->productFixture->load(['custom' => $products]);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->integerNode('amount')->isRequired()->min(0)->end()
        ;
    }

    /**
     * @param int $amount
     *
     * @return string
     */
    private function getUniqueNames($amount)
    {
        $productsNames = [];

        for ($i = 0; $i < $amount; ++$i) {
            $name = $this->faker->word;
            while (in_array($name, $productsNames)) {
                $name = $this->faker->word;
            }
            $productsNames[] = $name;
        }

        return $productsNames;
    }
}
