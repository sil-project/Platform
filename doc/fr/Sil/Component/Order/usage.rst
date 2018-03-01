Exemples d'utilisation
======================

-------------------------
Créer un code de commande
-------------------------

Le générateur de code à besoin du dépôt des commandes pour récupérer le prochain numéro disponible.

.. code-block:: php

    <?php

    use Sil\Component\Order\Generator\OrderCodeGenerator;
    use Sil\Component\Order\Model\OrderInterface;
    use Sil\Component\Order\Tests\Unit\Repository\OrderRepository;

    // [...]

    $orderRepository = new OrderRepository(OrderInterface::class);
    $codeGenerator = new OrderCodeGenerator($orderRepository);

    $code = $codeGenerator->generate();


------------------
Créer une commande
------------------

2 méthodes de création sont possibles, `Manuellement`_ et via une `Factory`_.

Par rapport à la méthode manuelle, la factory se charge simplement de générer le code de la commande avant de l'instancier.

Manuellement
------------

.. code-block:: php

    <?php

    use Sil\Component\Order\Generator\OrderCodeGenerator;
    use Sil\Component\Order\Model\Currency;
    use Sil\Component\Order\Model\Order;
    use Sil\Component\Order\Model\OrderInterface;
    use Sil\Component\Order\Tests\Unit\Repository\OrderRepository;

    use Sil\Component\Account\Model\Account;

    // [...]

    // Création du code de la commande

    $orderRepository = new OrderRepository(OrderInterface::class);
    $codeGenerator = new OrderCodeGenerator($orderRepository);

    $code = $codeGenerator->generate();

    // Création du compte associé à la commande

    $account = new Account('Compte client', 'CLIENT001');

    // Récupération / Création de la devise

    $currency = new Currency();
    $currency->setCode('EUR');

    // Création de la commande

    $order = new Order($code, $account, $currency);


Factory
-------

.. code-block:: php

    <?php

    use Sil\Component\Account\Model\Account;
    use Sil\Component\Order\Factory\OrderFactory;
    use Sil\Component\Order\Model\Currency;
    use Sil\Component\Order\Model\OrderInterface;
    use Sil\Component\Order\Tests\Unit\Repository\OrderRepository;

    // [...]

    // Création du repository

    $orderRepository = new OrderRepository(OrderInterface::class);

    // Création du compte associé à la commande

    $account = new Account('Compte client', 'CLIENT001');

    // Récupération / Création de la devise

    $currency = new Currency();
    $currency->setCode('EUR');

    // Appel de la factory

    $order = OrderFactory::createOrder($orderRepository, $account, $currency);

---------------------
Modifier une commande
---------------------

Créer un élément de commande
----------------------------

.. code-block:: php

    <?php

    use Sil\Component\Order\Model\OrderItem;
    use Sil\Component\Uom\Model\UomQty;

    // [...]

    // Initialisation de la quantité (Voir le composant Uom pour plus de détails: https://github.com/sil-project/Uom)

    $quantity = new UomQty($uom, 5.0);

    // Création du prix

    $price = new Price($currency, 19.99);

    // Création de l'élément

    $item = new OrderItem($order, 'Un élément de la commande', $quantity, $price);

Ajouter un élément de commande
------------------------------

**C'est déjà fait !**

Lors de l'instanciation d'un élément de commande, celui-ci s'ajoute auprès de la commande. Il est donc conseillé de gérer l'exception ``DomainException`` si jamais vous tentez d'ajouter un élément déjà présent dans la commande.

--------------------
Valider une commande
--------------------

Une commande implémente l'interface ``OrderStateAwareInterface``. Cette interface propose des méthodes de raccourci pour faire évoluer les états de la commande.

.. code-block:: php

    <?php

    // Valider une commande en OrderState::DRAFT

    $order = $orderRepository->findOneBy(['code.value' => 'FA00000001']);

    // Appel du raccourci de validation

    $order->beValidated();

A noter qu'à partir du moment où une commande n'est plus en état ``OrderState::DRAFT``, toute modification des données de celle-ci (données, éléments, totaux) lèvera une exception ``DomainException``.

.. code-block:: php

    <?php

    $order = $orderRepository->findOneBy(['code.value' => 'FA00000001']);
    $order->beValidated();

    $order->setTotal($price);

    // => DomainException : The Order's data cannot be changed when in state validated

Cette gestion est faite à l'aide de la méthode ``OrderState::allowDataChanges``. Pour affiner la gestion des modification de données d'une commande, c'est cette méthode qu'il faut redéfinir pour répondre à des besoins spécifiques.

Pour protéger une donnée particulière dans une commande, il faut explicitement appeler la méthode depuis la commande

.. code-block:: php

    <?php

    namespace App/Order;

    use Sil\Component\Order\Model\Order;

    class MyOrder extends Order
    {
        /**
         * my data
         *
         * @var string
         */
        private $myData;

        /**
         * sets my data
         *
         * @param string $myData
         *
         * @throws DomainException
         */
        public function setMyData(string $myData): void
        {
            $this->getState()->allowDataChanges();

            $this->myData = $myData;
        }
    }
