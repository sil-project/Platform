
======
Champs
======

``SyliusGridBundle`` fournit par défaut 3 types de champs :

- string
- datetime
- twig

----
Twig
----

Le type le plus souple est le type ``twig`` car il rend directement un template qui sera passé en paramètre.

Exemple
=======

On suppose que le champ ``myField`` est une collection doctrine.

.. code-block:: yaml

    sylius_grid:
        grids:
            my_grid:
                fields:
                    myField:
                        type: twig
                        label: My twig field
                        options:
                            template: '@MyApp/MyBundle/my_twig_field.html.twig'

La valeur du champ courant est accessible sous la variable ``value``.

.. code-block:: twig

    {% for element in value %}
        <span>{{ element.aProperty }}</span>
        <i>{{ element.anotherProperty }}</i>
    {% else %}
        <span>No items</span>
    {% endfor %}

Il y a plusieurs variables exposées dans un template de champs :

+--------------+---------------------------------------------------------+
| Variable     | Type / Valeur                                           |
+==============+=========================================================+
| "definition" | ``Sylius\\Component\\Grid\\Definition\\Grid``           |
+--------------+---------------------------------------------------------+
| "data"       | ``Pagerfanta\\Pagerfanta``                              |
+--------------+---------------------------------------------------------+
| "path"       | "/app_dev.php/currentPath" [1]_                         |
+--------------+---------------------------------------------------------+
| "row"        | ``MyApp\\MyBundle\\Entity\\MyEntity`` [1]_              |
+--------------+---------------------------------------------------------+
| "field"      | ``Sylius\\Component\\Grid\\Definition\\Field`` [1]_     |
+--------------+---------------------------------------------------------+
| "fieldName"  | "myField" [1]_                                          |
+--------------+---------------------------------------------------------+
| "value"      | ``Doctrine\\Common\\Collections\\ArrayCollection`` [1]_ |
+--------------+---------------------------------------------------------+

.. [1] Ces valeurs correspondent à l'exemple et ne seront pas les même dans vos cas d'utilisation.

-----------------
Type personnalisé
-----------------

Se référer à la documentation du bundle SyliusGridBundle_

.. _SyliusGridBundle: http://docs.sylius.com/en/1.1/components_and_bundles/bundles/SyliusGridBundle/custom_field_type.html
