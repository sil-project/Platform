InvoiceItem
-----------

Les éléments d'une facture sont représentés par le modèle InvoiceItem.

+-----------------+-------------------------------------------------------------------------+
| Property        | Description                                                             |
+=================+=========================================================================+
| label           | Libellé du produit ou service                                           |
+-----------------+-------------------------------------------------------------------------+
| description     | Description                                                             |
+-----------------+-------------------------------------------------------------------------+
| unitPrice       | Prix unitaire                                                           |
+-----------------+-------------------------------------------------------------------------+
| quantity        | Quantité                                                                |
+-----------------+-------------------------------------------------------------------------+
| taxRate         | Taux de TVA                                                             |
+-----------------+-------------------------------------------------------------------------+

.. note::

  Les proriétés ``label``, ``unitPrice``, ``quantity`` et ``taxRate`` sont indispensables à la création d'un objet InvoiceItem.
