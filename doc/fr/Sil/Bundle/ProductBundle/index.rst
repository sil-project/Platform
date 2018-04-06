================
SilProductBundle
================

----------------------
Fonctionnement général
----------------------


Contrôleurs
===========

Tous les contrôleurs sont déclarés en tant que service.

Dans les vues, dans le cas d'inclusion de rendus via la fonction ``controller()`` imbriquée dans la fonction ``render()``, la notation suivante est à favoriser :

``service_name`` ``:`` ``controller_action_full_name``.

**Exemple :**

.. code-block:: twig

    <div class="ui eight wide column">
        {{ render(controller('sil_product.controller.product:editAction', {
            'productId': product.id
        })) }}
    </div>

.. note::

    Le terme ``Action`` doit bien apparaître dans cet appel car la convention [1]_ ne s'applique pas pour les services.

.. [1] Voir les best-practices symfony https://symfony.com/doc/current/best_practices/controllers.html#controller-action-naming

Dépendances
-----------

Chaque contrôleur hérite d'un contrôleur basique se chargeant d’accueillir les injections de dépendances partagées ainsi que quelques méthodes communes à tous les contrôleurs.

+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| Attribut                 | Description                                                  | Service / Paramètre                          |
+==========================+==============================================================+==============================================+
| $perPage                 | Nombre d'éléments affichés par page (pour les vues de liste) |                                              |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $productClass            | Classe de l'entité ``Product``                               | ``%sil.model.product.class%``                |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $attributeClass          | Classe de l'entité ``Attribute``                             | ``%sil.model.product_attribute.class%``      |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $attributeTypeClass      | Classe de l'entité ``AttributeType``                         | ``%sil.model.product_attribute_type.class%`` |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $optionClass             | Classe de l'entité ``Option``                                | ``%sil.model.product_option.class%``         |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $optionTypeClass         | Classe de l'entité ``OptionType``                            | ``%sil.model.product_option_type.class%``    |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $productRepository       | Répository de l'entité ``Product``                           | ``@sil.repository.product``                  |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $attributeRepository     | Répository de l'entité ``Attribute``                         | ``@sil.repository.product_attribute``        |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $attributeTypeRepository | Répository de l'entité ``AttributeType``                     | ``@sil.repository.product_attribute_type``   |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $optionRepository        | Répository de l'entité ``Option``                            | ``@sil.repository.product_option``           |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $optionTypeRepository    | Répository de l'entité ``OptionType``                        | ``@sil.repository.product_option_type``      |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $breadcrumbBuilder       | Service de création du fil d'ariane                          | ``@blast_ui.service.breadcrumb_builder``     |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+
| $formFactory             | Factory des formulaire de Symfony                            | ``@form.factory``                            |
+--------------------------+--------------------------------------------------------------+----------------------------------------------+

Méthodes communes
-----------------

+--------------------------------------------------------------------+-------------------------------------------------------+
| Méthode                                                            | Description                                           |
+====================================================================+=======================================================+
| findProductOr404(string $productId): ResourceInterface             | Méthode *raccourci* pour récupérer un produit         |
+--------------------------------------------------------------------+-------------------------------------------------------+
| findAttributeOr404(string $attributeId): ResourceInterface         | Méthode *raccourci* pour récupérer un attribut        |
+--------------------------------------------------------------------+-------------------------------------------------------+
| findAttributeTypeOr404(string $attributeTypeId): ResourceInterface | Méthode *raccourci* pour récupérer un type d'attribut |
+--------------------------------------------------------------------+-------------------------------------------------------+
| findOptionOr404(string $optionId): ResourceInterface               | Méthode *raccourci* pour récupérer une option         |
+--------------------------------------------------------------------+-------------------------------------------------------+
| findOptionTypeOr404(string $optionTypeId): ResourceInterface       | Méthode *raccourci* pour récupérer un type d'option   |
+--------------------------------------------------------------------+-------------------------------------------------------+

Routing
=======

Chaque contrôleur possède une route ``*_homepage`` représentant la route par défaut qui sera utilisable de façon automatique.

.. code-block:: yaml

    sil_product_homepage:
        path:     /
        defaults: { _controller: sil_product.controller.product:indexAction }

    sil_product_list:
        path:     /list
        defaults: { _controller: sil_product.controller.product:listAction }

.. code-block:: php

    <?php

    /**
     * Default route.
     *
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->redirectToroute('sil_product_list');
    }

La plupart du temps, cette route redirigera sur la liste de la resource gérée par le contrôleur.

Formulaires
===========

Les formulaires étendent tous d'un formulaire abstrait permettant l'ordonnancement des champs au travers de la propriété ``fieldsOrder``.

L'ordonnancement se réalise à partir d'un remplacement de clé de tableau et d'une fusion de ce dernier pour ajouter les champs manquants.

**Exemple:**

.. code-block:: php

    <?php

    class CreateReusableType extends CreateType
    {
        protected $fieldsOrder = [
            'attributeType',
            'value',
        ];

        /**
         * {@inheritdoc}
         */
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            parent::buildForm($builder, $options);

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $form
                    ->remove('specificName')
                    ->remove('attributeType')
                    ->add('attributeType', EntityType::class, [
                        'required'     => true,
                        'class'        => $this->attributeTypeClass,
                        'choice_label' => 'name',
                        'choices'      => $this->attributeTypeRepository->findBy(['reusable' => true]),
                        'constraints'  => [
                            new NotBlank(),
                        ],
                        'data' => $data['attributeType'] !== null ? $data['attributeType'] : $options['attributeType'],
                        'attr' => [
                            'autocomplete' => 'off',
                        ],
                    ]);
            });
        }
    }

La méthode ``buildForm`` retire 2 champs et en rajoute un. Cette action change l'ordre d'affichage de base des champs (le champs **attributeType** sera affiché en dernier).

Grâce à la définition de la propriété ``fieldsOrder``, le champ **attributeType** sera affiché en premier lors de l'utilisation de la fonction ``form()`` dans la vue Twig.

.. note::

    L'ordre est bien entendu gérable depuis la vue. Cette fonction à été créée dans le cadre de l'utilisation d'un widget issus du **BlastUIBundle**.

-------------------------
Fonctionnement spécifique
-------------------------

Formulaires
===========

L'affichage du formulaire gérant les attributs d'un produit est réalisé via 2 méthodes distinctes, se chargeant uniquement du rendu du formulaire:

- ``ProductController:selectAttributeForProductAction``
- ``ProductController:updateAttributeForProductAction``

La soumission du formulaire est réalisée via l'action ``ProductController:updateAttributesAction``. La gestion de la soumission est faite manuellement.

A cause de la gestion différente d'attributs simples et réutilisables, un formulaire ``CollectionType`` ne pouvait répondre à ce cas particulier.

La méthode ``ProductController:handleUpdateAttributesRawForm`` se charge donc de boucler sur chaque éléments du formulaire et applique la mise à jour des éléments selon leur type (attribut simple ou réutilisable).
Cette distinction est faite sur le champ ``specificName`` qui n'est pas présent pour le cas d'un attribut réutilisable.
