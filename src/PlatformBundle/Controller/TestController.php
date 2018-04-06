<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace PlatformBundle\Controller;

use DateInterval;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function testAction(Request $request)
    {
        $breadcrumbBuilder = $this->container->get('blast_ui.service.breadcrumb_builder');

        $breadcrumbBuilder
            ->addItem('<i class="home icon"></i>', '/')
            ->addItem('Resource', $this->generateUrl('blast_ui_test'))
        ;

        $properties = [
            'headers'        => [
                [
                    'name'  => '[field_1]',
                    'label' => 'Field 1',
                ], [
                    'name'  => '[field_2]',
                    'label' => 'Field 2',
                ], [
                    'name'  => '[field_3]',
                    'label' => 'Field 3',
                ],
            ],
            'data'           => [
                [
                    'field_1' => 'Valeure 1',
                    'field_2' => 'Valeure 2',
                    'field_3' => 'Valeure 3',
                ], [
                    'field_1' => 'Valeure 1.0',
                    'field_2' => 'Valeure 2.0',
                    'field_3' => 'Valeure 3.0',
                ],
            ],
            'actions'        => [
                [
                    'label' => 'Voir',
                    'icon'  => 'eye',
                ], [
                    'label' => 'Supprimer',
                    'icon'  => 'trash',
                ],
            ],
            'filters' => [
            ],
            'allowSelection' => true,
        ];

        $dataTypes = [
            'booleans' => [true, false],
            'integer'  => 42,
            'double'   => 0.512,
            'string'   => 'string',
            'array'    => ['prop' => 'value'],
            'object'   => json_decode(json_encode(['prop' => 'value'])),
            'null'     => null,
            'unknown'  => fopen(__FILE__, 'r'),
        ];

        return $this->render('@Platform/_test.html.twig', [
            'form'       => $this->getExampleFormType()->createView(),
            'smallForm'  => $this->getExampleSmallFormType()->createView(),
            'entity'     => $this->getFakeEntity(),
            'properties' => $properties,
            'list'       => [
                'filters'  => [],
                'elements' => $properties['data'],
                'headers'  => $properties['headers'],
                'actions'  => $properties['actions'],
            ],
            'dataTypes'  => $dataTypes,
        ]);
    }

    private function getExampleSmallFormType()
    {
        $formBuilder = $this->createFormBuilder($this->getFakeEntity());

        $formBuilder
            ->add('name', Type\TextType::class, [])
            ->add('description', Type\TextareaType::class, []);

        return $formBuilder->getForm();
    }

    private function getExampleFormType()
    {
        $formBuilder = $this->createFormBuilder($this->getFakeEntity());

        $formBuilder
            ->add('name', Type\TextType::class, [])
            ->add('description', Type\TextareaType::class, [])
            ->add('email', Type\EmailType::class, [])
            ->add('age', Type\IntegerType::class, [])
            ->add('earnedMoney', Type\MoneyType::class, ['currency' => 'EUR', 'grouping' => true])
            ->add('petsNumber', Type\NumberType::class, [])
            ->add('password', Type\PasswordType::class, [])
            ->add('generalAchievements', Type\PercentType::class, [])
            ->add('siteUrl', Type\UrlType::class, [])
            ->add('moneyAlert', Type\RangeType::class, ['attr' => [
                'min' => 0,
                'max' => 50,
            ]])
            ->add('phone', Type\TelType::class, [])
            ->add('preferedColor', Type\ColorType::class, [])
            ->add('sexe', Type\ChoiceType::class, ['choices' => ['Homme' => 'male', 'Femme' => 'female']])
            ->add('country', Type\CountryType::class, [])
            ->add('language', Type\LanguageType::class, [])
            ->add('locale', Type\LocaleType::class, [])
            ->add('timezone', Type\TimezoneType::class, [])
            ->add('preferedCurrency', Type\CurrencyType::class, [])
            ->add('creationDate', Type\DateType::class, ['widget' => 'single_text'])
            ->add('refreshNotifications', Type\DateIntervalType::class, [
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => false,
                'with_hours'  => true,
            ])
            ->add('updatedDate', Type\DateTimeType::class, ['date_widget' => 'single_text', 'time_widget' => 'single_text'])
            ->add('finishTime', Type\TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
            ])
            ->add('birthday', Type\BirthdayType::class, ['widget' => 'single_text'])
            ->add('isReal', Type\CheckboxType::class, [])
            ->add('avatar', Type\FileType::class, [])
            ->add('newsletter', Type\RadioType::class, [])

            ->add('button', Type\ButtonType::class, [])
            ->add('reset', Type\ResetType::class, [])
            ->add('submit', Type\SubmitType::class, [])
        ;

        return $formBuilder->getForm();
    }

    private function getFakeEntity(): array
    {
        return [
            'name'                     => 'My entity',
            'description'              => 'A fake entity to test forms and whole UI',
            'email'                    => 'example@sil.eu',
            'age'                      => '42',
            'earnedMoney'              => '512256',
            'petsNumber'               => 1,
            'password'                 => 'password',
            'generalAchievements'      => 0.85,
            'siteUrl'                  => 'http://www.libre-informatique.fr/',
            'moneyAlert'               => 20,
            'phone'                    => '+33 (0)2 30 96 06 49',
            'preferedColor'            => '#0085AD',
            'sexe'                     => 'male',
            'country'                  => 'FR',
            'language'                 => 'fr',
            'locale'                   => 'fr_FR',
            'timezone'                 => 'Europe/Paris',
            'preferedCurrency'         => 'EUR',
            'creationDate'             => new DateTime('20180101'),
            'refreshNotifications'     => new DateInterval('PT2H'),
            'updatedDate'              => new DateTime('20180101T12:12:42Z'),
            'finishTime'               => new DateTime('20180101T12:12:42Z'),
            'isReal'                   => false,
            'avatar'                   => null,
            'newsletter'               => false,
        ];
    }
}
