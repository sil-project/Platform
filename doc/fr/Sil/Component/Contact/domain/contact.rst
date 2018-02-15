Contact
------------
Les Contacts sont représentés par le modèle Contact.

.. note::

Lorsqu'une première adresse (modèle Address) est associé à un contact, celle-ci deviendra l'addresse par défaut du contact.
  
Lorsque l'addresse par défaut est supprimée, la première adresse disponible dans la liste des adresses associées au contact devient automatiquement l'adresse par défaut. Si aucune adresse n'est associée au contact, aucune adresse par défaut ne peut être désignée.

Le même fonctionnement est appliqué aux numéros de téléphone (modèle Phone).
  
+-----------------+-----------------------------------------------------------------------------+
| Propriété       | Description                                                                 |
+=================+=============================================================================+
| firstName       | Prénom                                                                      |
+-----------------+-----------------------------------------------------------------------------+
| lastName        | Nom                                                                         |
+-----------------+-----------------------------------------------------------------------------+
| title           | Civilité                                                                    |
+-----------------+-----------------------------------------------------------------------------+
| email           | Adresse mail                                                                |
+-----------------+-----------------------------------------------------------------------------+
| position        | Fonction, poste                                                             |
+-----------------+-----------------------------------------------------------------------------+
| defaultAddress  | Addresse par défaut du Contact                                              |
+-----------------+-----------------------------------------------------------------------------+
| addresses       | Collection des adresses du Contact                                          |
+-----------------+-----------------------------------------------------------------------------+
| defaultPhone    | Téléphone par défaut du Contact                                             |
+-----------------+-----------------------------------------------------------------------------+
| phones          | Collection des téléphones du Contact                                        |
+-----------------+-----------------------------------------------------------------------------+

.. note::

  Le modèle ``Contact`` implémente l'interface ``GroupMemberInterface``.
