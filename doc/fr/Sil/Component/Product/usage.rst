Exemples d'utilisation
======================

------------------------
Créer un code de produit
------------------------

Tout produit doit avoir un code unique l'identifiant.

.. code-block:: php

    <?php

    use Sil\Component\Product\Generator\ProductCodeGenerator;

    // [...]

    $codeGenerator = new ProductCodeGenerator();
    $code = $codeGenerator->generate('Produit d\'exemple');

----------------
Créer un produit
----------------

Le produit
----------

.. code-block:: php

    <?php

    use Sil\Component\Product\Factory\CodeFactory;
    use Sil\Component\Product\Model\Product;

    // [...]

    $productName = 'My product';
    $productCode = CodeFactory::generateProductCode($productName);

    $product = new Product($productCode, $productName);

La variante
-----------

.. code-block:: php

    <?php

    use Sil\Component\Product\Factory\CodeFactory;
    use Sil\Component\Product\Model\Product;

    // [...]

    $variantName = $product->getName();

    $option = ... // Voir partie option

    $variantCode = CodeFactory::generateProductVariantCode($product, [$option]);

    $variant = new ProductVariant($product, $variantCode, $variantName);

------------------------------
Créer un attribut / une option
------------------------------

Pour pouvoir créer un attribut, il faut avant tout créer le type d'attribut (la famille d'attribut).

Le principe est le même pour les options.

Créer un type d'attribut
------------------------

.. code-block:: php

    <?php

    use Sil\Component\Product\Model\AttributeType;

    // [...]

    $attributeType = new AttributeType('Marque');

    $attributeType->setReusable(true); // Marquer le type d'attribut comme réutilisable

Un attribut réutilisable partagera ses attributs avec tous les produits l'utilisant.

Créer un attribut
-----------------

.. code-block:: php

    <?php

    use Sil\Component\Product\Model\Attribute;

    // [...]

    $attribute = new Attribute($attributeType, 'Une marque plus ou moins connue');
