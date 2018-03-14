==========
Vues types
==========

Plusieurs vues type sont disponibles :

----
Show
----

Cette vue est dédiée à l'affichage d'une resource et éventuellement son édition au travers des widgets ``card``.

Exemple
=======

.. code-block:: twig

    {% extends '@BlastUI/Views/Show/show.html.twig' %}

    {% block page_header_title %}
        Titre d'une resource
    {% endblock page_header_title %}

    {% block page_header_icon %}
        barcode
    {% endblock page_header_icon %}

    {% block page_header_description %}
        Affichage d'une resource
    {% endblock page_header_description %}

    {% block page_content %}
        Affichage du contenu de la resource
    {% endblock page_content %}

Rendu
=====

.. image:: img/show-01.png

----
List
----

Cette vue est dédiée au listing d'un type de resource.

Exemple
=======

.. code-block:: twig

    {% extends '@BlastUI/Views/List/list.html.twig' %}

    {% block page_header_title %}
        Liste d'une resource
    {% endblock page_header_title %}

    {% block page_header_icon %}
        list
    {% endblock page_header_icon %}

    {% block page_header_description %}
        Resources disponibles
    {% endblock page_header_description %}

    {% block page_content %}
        {{ parent() }}
    {% endblock %}

.. code-block:: php

    <?php

    // [...]

    list = [
        'filters'  => [],
        'elements' => [
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
        'headers'  => [
            [
                'name'  => 'field_1',
                'label' => 'Field 1',
            ], [
                'name'  => 'field_2',
                'label' => 'Field 2',
            ], [
                'name'  => 'field_3',
                'label' => 'Field 3',
            ],
        ],
        'actions'  => [
            [
                'label' => 'Voir',
                'icon'  => 'eye',
            ], [
                'label' => 'Supprimer',
                'icon'  => 'trash',
            ],
        ],
    ];

    // [...]

    return $this->render(..., ['list' => $list]);

Rendu
=====

.. image:: img/list-01.png
