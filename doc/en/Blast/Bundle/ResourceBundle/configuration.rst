Configuration
=============

You can now declare your resource as follows:

.. code-block:: yaml

    # blast/resource.yml

    blast_resource:
        resource:
            contact: #the resource alias
                classes:
                    model: AcmeBundle\Entity\Contact
                    repository: AcmeBundle\Repository\ContactRepository
                    interfaces:
                        - AcmeBundle\Entity\Contact\Interface


What is done
------------

Once your resource is declared :

- Parameters are created for each classes using the following format: "%[app].[type].[name].class%"
- The repository is declared as a service using the following format: [app].repository.[name]
- The repository is injected as the default doctrine repository (reachable from the Doctrine EntityManager)
- Declared interfaces are used by doctrine to resolve mapping types and have to be implemented by the declared model
