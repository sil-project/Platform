=============
ContactBundle
=============

Le ContactBundle utilise le Composant Contact.

-------
Entités
-------

Les entités sont déclarées en tant que 'MappedSuperclass' pour doctrine, le BlastResourceBundle se charge de selectionner la classe concrète à utiliser en fonction de sa configuration.
Tous les identifiants unique en base de donnnées sont des UUIDs.

La gestion des téléphones, groupes et adresses d'un contact se font directement depuis sa fiche (vue show).

La gestion de la hiérachie des groupes (Créadition, édition, suppression, organisation) se fait depuis la vue de liste des groupes.

-----------
Contrôleurs
-----------

Chaque contrôleur fournit les actions de CRUD de base pour une entité.
Il est définit en tant que service avec une dépendance sur le repository de l'entité à manipuler.

Les actions des contrôleurs Address, Group et Phone ont un paramètre 'list'.Si ce paramètre est renseginé l'action renverra la liste des adresses, téléphones ou groupe du contact associé.

Par défault les actions create et edit renvoient une vue complète du formulaire décoré.Si la requête est de type Ajax, seul le formulaire nu sera retourné.

-----------
Formulaires
-----------

Pour la création et l'édition de chaque un entité, un type de formulaire spécifique a été créé.

Les constructeurs des modèles Phone, Group et Address du composant Contact ayant des paramètres obligatoires, à l'initialisation des formulaires se fait avec un tableau vide.
Il y a donc un DataTransformer permettant de transformer les tableau de données en instance du modèle.

Un DataTransformer pour récupérer un contact en base de données depuis son id, les id de contacts pour les sous formulaires, Address, Phone et Group ne renseignant que l'id du contact parent.
La valeur du champ id pour ces formulaires est renseignée via javascript.

Un DataTransformer pour retrouver un Pays ou une Province depuis son nom; les adresses ne persistant que le nom de leur province et pays.

--------
Services
--------

Un service GroupMemberValidator permet de vérifier qu'un groupe n'est pas déjà membre d'un autre groupe de la même branche de la hiérarchie.

----------
Javascript
----------
La création de groupes, téléphones et adresses depuis la fiche d'un contact se font côté client.Le formulaire est soumis via une requête ajax puis le contenu est mis à jour avec la liste retournée par le contrôleur.

Lors de l'utilisation de la vue de liste des groupes, un cookie gardera les sections de la hierarchie ayant été déployées, afin de les réouvrir lors du rafraichissement du contenu.

-----
Tests
-----

Des tests unitaires ont été réalisés sur les DataTransformers.
Des test fonctionnels ont été réalisé sur le GroupMemberValidator et sur les Types de formulaires.
