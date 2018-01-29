Les Interfaces
==============


ResourceInterface
-----------------

L'interface ResourceInterface permet de typer un modèle métier afin qu'il soit reconnu et gérer comme une ressource.

.. code-block:: php

    <?php

    namespace AcmeBundle\Entity;

    use Blast\Component\Resource\Model\ResourceInterface;

    class Contact implements ResourceInterface
    {

    }

ResourceRepositoryInterface
---------------------------
