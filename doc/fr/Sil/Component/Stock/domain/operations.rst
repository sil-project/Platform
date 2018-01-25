Les opérations sur les stocks
=============================


Plusieurs types d'opération de stock sont disponibles.

- La réception
- L'expédition
- Le transfert interne
- L'inventaire

-----------------------------
Planification d'une opération
-----------------------------

Une **Opération de stock** est la planification de plusieurs **Mouvements de stock** en une seule opération.

Une **Opération de stock** comprend :

- un emplacement d'origine
- un emplacement de destination
- une liste d'articles et des quantités à déplacer
- la date prévue
- l'état de l'opération

Une **Opération de stock** peut avoir différents états :

- brouillon (draft)
- en attente de disponibilité (waiting_for_availability)
- partiellement disponible (partially_available)
- disponible (available)
- fait (done)
- annulé (cancel)


À sa création, une **Opération de stock** est dans l'état Brouillon.


Operation Workflow
==================


+----------------------+-----------------------------------+-----------------------------------------+
| Transition           | From                              | To                                      |
+======================+===================================+=========================================+
| planned              | draft                             | confirmed                               |
+----------------------+-----------------------------------+-----------------------------------------+
| back_to_draft        | confirmed                         | draft                                   |
+----------------------+-----------------------------------+-----------------------------------------+
| partially_available  | confirmed                         | partially_available                     |
+----------------------+-----------------------------------+-----------------------------------------+
| available            | confirmed                         | available                               |
|                      | partially_available               |                                         |
+----------------------+-----------------------------------+-----------------------------------------+
| done                 | available                         | done                                    |
+----------------------+-----------------------------------+-----------------------------------------+
| cancel               | confirmed                         | cancel                                  |
|                      | partially_available               |                                         |
|                      | available                         |                                         |
+----------------------+-----------------------------------+-----------------------------------------+
