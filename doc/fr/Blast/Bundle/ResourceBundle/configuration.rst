Configuration
=============

Une ressource est déclarée de la manière suivante :

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


Ce qui est réalisé pour une ressource déclarée
----------------------------------------------

Une fois une ressource déclarée :

- Des paramètres Symfony sont créés pour chaque classe en utilisant le format suivant : "%[app].[type].[name].class%"
- Le Repository est déclarer en tant que service Symfony en utilisant le format suivant : [app].repository.[name]
- Le Repository est injecté en tant que repository par défaut pour l'entité Doctrine (accessible via l'EntityManager de Doctrine)
- Declared interfaces are used by doctrine to resolve mapping types and have to be implemented by the declared model
- Les interfaces déclarées sont utilisées par Doctrine pour la résolution du mapping des entités
