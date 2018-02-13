==========
Processeur
==========

Rôle
====

Le processeur est un service permettant de gérer les déclenchements des calculs des éléments d'une commande.

Méthodes exposées
=================

+------------------------------------------+---------------------------------------------------------------------------------------------------+
| Méthode                                  | Description                                                                                       |
+==========================================+===================================================================================================+
| process(OrderInterface $order): void     | Point d'entrée du service, applique les méthodes de calculs sur les éléments de la commande [1]_  |
+------------------------------------------+---------------------------------------------------------------------------------------------------+
| calculateOrderItemTotals(): void         | Se charge de recalculer les totaux des éléments de la commande [2]_                               |
+------------------------------------------+---------------------------------------------------------------------------------------------------+
| calculateOrderItemAdjustedTotals(): void | Appelle la stratégie d'ajustement de chaque élément de la commande qui met à jour le total ajusté |
+------------------------------------------+---------------------------------------------------------------------------------------------------+
| calculateOrderTotal(): void              | Calcule le total de la commande en se basant sur les totaux ajustés des éléments de commande      |
+------------------------------------------+---------------------------------------------------------------------------------------------------+
| calculateOrderAdjustedTotal(): void      | Applique le calcul d'ajustement sur la commande (en appelant la stratégie d'ajustement)           |
+------------------------------------------+---------------------------------------------------------------------------------------------------+

.. [1] C'est la seule méthode qu'il faut utiliser. Les autres méthodes sont exposées pour permettre leur surcharge.
.. [2] Lorsque la quantité d'élément d'une commande est modifié, le pré-calcul effectué lors de l'instanciation de la commande n'est plus juste. Il faut donc recalculer le total de l'élément après coup.

Processus de calcul
===================

Le calcul se fait en 2 grandes étapes :

- Calculs des éléments de la commande
- Calculs des ajustements de la commande

Pour le calcul des éléments de la commande, celui-ci se fait également en 2 étapes :

- Calcul du total de l'élément (sans les ajustements liés à l'élément)
- Calcul du total avec ajustements de l'élément

Les calculs d'ajustements sont réalisés  par les stratégies d'ajustement. Le processeur appelle simplement leur méthode ``adjust``.
