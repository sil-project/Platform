InvoiceAdjustment
-----------------

Les ajustements sur facture sont représentés par le modèle InvoiceAdjustment

.. note::

  Tous les propriétés sont obligatoires à la construction d'un objet ``InvoiceAdjustment``.

+-----------------+-------------------------------------------------------------------------+
| Propriété       | Description                                                             |
+=================+=========================================================================+
| label           | Libellé de l'opération d'ajustement                                     |
+-----------------+-------------------------------------------------------------------------+
| value           | Valeur                                                                  |
+-----------------+-------------------------------------------------------------------------+
| type            | Type d'ajustement                                                       |
+-----------------+-------------------------------------------------------------------------+

InvoiceAdjustmentType
---------------------

Le type d'ajustement est représenté par le modèle InvoiceAdjustmentType qui à pour seule propriété ``value``.
Les trois types d'ajustement sont:

* positif (majoration, arriéré, ....)
* négatif (réduction, avoir, ...)
* neutre (cas particuliers, mentions spéciales)

Le constructeur de la classe ``InvoiceAdjustmentType`` ne peut être appelé.Les constructeurs nommés pour chaque type devront être appelés.

+-----------------+-------------------------------------------------------------------------+
| Méthode         | Description                                                             |
+=================+=========================================================================+
| plus            | Retourne une nouvelle instance de type positif                          |
+-----------------+-------------------------------------------------------------------------+
| minus           | Retourne une nouvelle instance de type négatif                          |
+-----------------+-------------------------------------------------------------------------+
| neutral         | Retourne une nouvelle instance de type neutre                           |
+-----------------+-------------------------------------------------------------------------+
