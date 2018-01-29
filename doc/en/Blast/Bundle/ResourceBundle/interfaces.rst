Interfaces
==========


The ResourceInterface
---------------------

The ResourceInterface has to be implemented by your model class to be typed as a manageable resource.

.. code-block:: php

    <?php

    namespace AcmeBundle\Entity;

    use Blast\Component\Resource\Model\ResourceInterface;

    class Contact implements ResourceInterface
    {

    }

The ResourceRepositoryInterface
-------------------------------
