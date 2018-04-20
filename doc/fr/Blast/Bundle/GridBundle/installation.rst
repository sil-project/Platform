
============
Installation
============

------------
Via composer
------------

.. code-block:: bash

    $ composer require blast-project/grid-bundle


Ajouter au kernel le bundle

.. code-block:: php

    <?php

    class AppKernel {

        public function registerBundles()
        {
            $bundles = [
                // ...

                new \Blast\Bundle\GridBundle\BlastGridBundle(),

                // ...
            ];
        }
    }

-------------
Configuration
-------------

Dans le fichier de configuration ``app/config/config.yml``, importer la configuration necessaire

.. code-block:: yaml

    imports:
        - { resource: '@BlastGrid/Resources/config/config.yml' }
