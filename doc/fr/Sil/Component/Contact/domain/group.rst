Group
-----

Le modèle Group représente des groupes permettant d'organiser des entités.
Les groupes peuvent être organisés de manière hiérarchique.

.. note::

  Pour pouvoir être ajoutée à un groupe, une entité doit implémenter l'interface ``GroupMemberInterface``.

+-----------------+-----------------------------------------------------------------------------+
| Propriété       | Description                                                                 |
+=================+=============================================================================+
| name            | Nom du groupe                                                               |
+-----------------+-----------------------------------------------------------------------------+
| members         | Collection des membres du groupe                                            |
+-----------------+-----------------------------------------------------------------------------+
| parentGroup     | Groupe parent                                                               |
+-----------------+-----------------------------------------------------------------------------+
| childrenGroups  | Collection des sous groupes                                                 |
+-----------------+-----------------------------------------------------------------------------+
