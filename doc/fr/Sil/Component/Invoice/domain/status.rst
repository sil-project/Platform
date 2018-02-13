InvoiceStatus
-------------

Le modèle InvoiceStatus, comportant pour seule propriété ``value``, à pour rôle principal de représenter le statut de la facture en assurant le respect des règles de son cycle de vie.

+-----------------+-----------------------------------------------------------------------------+
| Méthode         | Description                                                                 |
+=================+=============================================================================+
| __construct     | Le contructeur force la valeur du statut à draft                            |
+-----------------+-----------------------------------------------------------------------------+
| beValidated     | Change le statut à validated seulement si le statut actuel est draft        |
+-----------------+-----------------------------------------------------------------------------+
| bePaid          | Change le statut à paid seulement si le statut actuel est validated         |
+-----------------+-----------------------------------------------------------------------------+
| isDraft         | Ces méthodes permettent de vérifier le statut actuel sans récupérer         |
+-----------------+ directement la valeur utilisée par la classe                                |
| isValidated     |                                                                             |
+-----------------+                                                                             |
| isPaid          |                                                                             |
+-----------------+-----------------------------------------------------------------------------+
