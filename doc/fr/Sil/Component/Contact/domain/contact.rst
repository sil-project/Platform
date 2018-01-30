Contact
------------
Les Contacts sont représentés par le modèle Contact.

.. note::

  Lorsqu'une Address ou un Phone est associé au contact, celui-ci deviendra l'addresse ou le téléphone par défaut du Contact.
  Lorsque l'objet par défaut est retiré de la collection du Contact, celui par défaut sera réinitilisé en même temps.
  Si il reste un autre objet dans la collection, ce dernier deviendra l'objet par défaut du Contact.

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
