============================
Domaine du composant Contact
============================

-------
Modèles
-------

Account
-------

Un compte est représenté par le modèle Account

.. note::

  Lorsque le premier Contact est associé au compte, celui-ci deviendra également le contact par défaut.
  Lorsque le Contact est retiré de la collection, le Contact par défaut du compte sera vidé en même temps.
  Si il reste un autre Contact dans la collection, ce dernier deviendra le Contact par défaut.

+-----------------+-------------------------------------------------------------------------+
| Propriété       | Description                                                             |
+=================+=========================================================================+
| name            | Nom du compte                                                           |
+-----------------+-------------------------------------------------------------------------+
| code            | Code du compte                                                          |
+-----------------+-------------------------------------------------------------------------+
| defaultContact  | Contact par défaut du compte                                            |
+-----------------+-------------------------------------------------------------------------+
| contacts        | Collection des Contacts associés au compte                              |
+-----------------+-------------------------------------------------------------------------+
