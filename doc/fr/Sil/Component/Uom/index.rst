Composant Uom
=============

.. toctree::
   :maxdepth: 2

   installation
   domain/index


Ce composant permet de représenter, de gérer et de convertir des unités de mesure physiques.
L'objectif est d'encapsuler des quantités physiques afin de maintenir la cohérence des données.

.. code-block:: php

  <?php
  use Sil\Component\Uom\Model\UomQty;
  use Sil\Component\Uom\Model\UomType;
  use Sil\Component\Uom\Model\UomQty;

  $mass = new UomType('Mass');
  $kg = new Uom($mass, 'Kg', 1);
  $g = new Uom($mass, 'mg', 1000);

  $kgQty = new UomQty($kg, 10);
  $gQty = new UomQty($g, 250);

  $newKgQty = $kgQty->increasedBy($gQty); // Returns 10.250 Kg

.. note::
  Les opérations sur les qantités ``UomQty`` ne sont possible que entre les unités d'une même famille d'unité ``UomType``.

  .. note::
    Une quantité est un objet immuable, ainsi le résultat des opérations sur les quantités est toujours une nouvelle instance de ``UomQty``.

------------------
Convertion d'unité
------------------

Au sein d'une même famille d'unité ``UomType``, il est possible de convertir une unité en une autre via la méthode ``UomQty::convertTo(Uom $uom):UomQty``

.. code-block:: php

  <?php
  use Sil\Component\Uom\Model\UomQty;
  use Sil\Component\Uom\Model\UomType;
  use Sil\Component\Uom\Model\UomQty;

  $mass = new UomType('Mass');
  $kg = new Uom($mass, 'Kg', 1);
  $g = new Uom($mass, 'mg', 1000);

  $kgQty = new UomQty($kg, 10);

  $newGramQty = $kgQty->convertTo($g); // Returns 10000 g


-----------------------
Comparaison de quantité
-----------------------

Les quantités peuvent être comparées entre elles.

+----------+-----------------------------------------+
| Operator | UomQty Equivalent Method                |
+==========+=========================================+
|    ==    | isEqualTo(UomQty $qty): bool            |
+----------+-----------------------------------------+
|    <     | isGreaterThan(UomQty $qty): bool        |
+----------+-----------------------------------------+
|    <=    | isGreaterOrEqualTo(UomQty $qty): bool   |
+----------+-----------------------------------------+
|    >     | isSmallerThan(UomQty $qty): bool        |
+----------+-----------------------------------------+
|    >=    | isSmallerOrEqualTo(UomQty $qty): bool   |
+----------+-----------------------------------------+
|    == 0  | isZero(): bool                          |
+----------+-----------------------------------------+


----------------------------
Opérations sur les quantités
----------------------------

+----------+-----------------------------------------+
| Operator | UomQty Equivalent Method                |
+==========+=========================================+
|    +     | increasedBy(UomQty $qty): UomQty        |
+----------+-----------------------------------------+
|    -     | decreasedBy(UomQty $qty): UomQty        |
+----------+-----------------------------------------+
|    *     | multipliedBy($value): UomQty            |
+----------+-----------------------------------------+
|    /     | dividedBy($value): UomQty               |
+----------+-----------------------------------------+
