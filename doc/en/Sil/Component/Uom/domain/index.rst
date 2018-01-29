Les Unités de mesure
====================

Dans certains cas, il est nécessaire de gérer différentes unités de mesure pour un produit.


-------
Modèles
-------

Uom
---

Le modèle **Uom** représente une unité de mesure spécifique appartenant à un **UomType**.

+-----------------+-----------------------------------------------------------------------------+
| Property        | Description                                                                 |
+=================+=============================================================================+
| name            | Le symbole de l'unité de mesure                                             |
+-----------------+-----------------------------------------------------------------------------+
| type            | La famille d'unité de mesure (ex: masse)                                    |
+-----------------+-----------------------------------------------------------------------------+
| factor          | Le facteur de multiplication pour obtenir l'Uom de référence (avec factor=1)|
+-----------------+-----------------------------------------------------------------------------+
| rounding        | La précision d'arrondie (ex: 1.0 pour la gestion à l'unité)                 |
+-----------------+-----------------------------------------------------------------------------+
| active          | la disponibilité de l'unité de mesure                                       |
+-----------------+-----------------------------------------------------------------------------+


UomType
-------

Chaque **Uom** doit appartenir à une famille d'unité de mesure : **UomType**.

Un **UomType** défini les **Uom** qui sont convertibles entre elles.

+-----------------+-----------------------------------------------------------------------------+
| Property        | Description                                                                 |
+=================+=============================================================================+
| name            | Le nom du type d'unité de mesure (ex: Mass)                                 |
+-----------------+-----------------------------------------------------------------------------+

UomQty
------

Il est parfois utile de pouvoir manipuler une quantité associée à son unité de mesure.


+-----------------+-----------------------------------------------------------------------------+
| Property        | Description                                                                 |
+=================+=============================================================================+
| uom             | L'unité de mesure utilisée                                                  |
+-----------------+-----------------------------------------------------------------------------+
| qty             | La quantité                                                                 |
+-----------------+-----------------------------------------------------------------------------+
