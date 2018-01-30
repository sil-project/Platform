Products
========

This component is a starting pont to be used for manipulating Product objects with basic options


------
Models
------

Product
-------

A **Product** is defined with a **name** and a unique field **code**. a **Product** is not a real product, It's a virtual product.

+-----------------+------------------------------------------------------------------+
| Property        | Description                                                      |
+=================+==================================================================+
| name            | The product name                                                 |
+-----------------+------------------------------------------------------------------+
| code            | Unique code                                                      |
+-----------------+------------------------------------------------------------------+
| enabled         | The enabled state of the product                                 |
+-----------------+------------------------------------------------------------------+
| attributes      | A collection of attributes that describes the product            |
+-----------------+------------------------------------------------------------------+
| optionTypes     | A collection of family of option'sthat will by used for variants |
+-----------------+------------------------------------------------------------------+
| variants        | A collection of variants                                         |
+-----------------+------------------------------------------------------------------+


ProductVariant
--------------

Each **Product** can have zero or many variants.

+-----------------+-----------------------------------------------+
| Property        | Description                                   |
+=================+===============================================+
| name            | The product variant  name                     |
+-----------------+-----------------------------------------------+
| code            | Unique code composed with parent product code |
+-----------------+-----------------------------------------------+
| enabled         | The enabled state of the product variant      |
+-----------------+-----------------------------------------------+
| product         | The associated product                        |
+-----------------+-----------------------------------------------+


Attribute
---------

An **Attribute** is an object used to describe a physical (or virtual) property of a product. It can be used to build filter based on product attribute values.

A **Product** have its own collection of attribute, but in some cases, attributes can be shared across products (see **reusable** of **AttributeType**).

+-----------------+-----------------------------------------------------------------------------------------+
| Property        | Description                                                                             |
+=================+=========================================================================================+
| name            | The overriden name of the attribute (if null, the attribute type name will be returned) |
+-----------------+-----------------------------------------------------------------------------------------+
| value           | The value assigned to the current attribute.                                            |
+-----------------+-----------------------------------------------------------------------------------------+
| attributeType   | The type of attribute                                                                   |
+-----------------+-----------------------------------------------------------------------------------------+
| products        | A collection of products that uses the attribute                                        |
+-----------------+-----------------------------------------------------------------------------------------+


AttributeType
-------------

An **AttributeType** is a family of attributes. It has a data type to help display and manipulations of children attributes.

Some attributes can be flagged as **reusable** in order to share attributes across products.

+-----------------+--------------------------------------------------------------+
| Property        | Description                                                  |
+=================+==============================================================+
| name            | The name of the attribute type                               |
+-----------------+--------------------------------------------------------------+
| type            | The data type of children attributes                         |
+-----------------+--------------------------------------------------------------+
| resusable       | A boolean flag that holds attribute sharing between products |
+-----------------+--------------------------------------------------------------+
| attributes      | A collection of children attributes                          |
+-----------------+--------------------------------------------------------------+


Option
------

An **Option** is linked to a product variant and holds a value associated to an **OptionType**.

+-----------------+--------------------------------------------------------+
| Property        | Description                                            |
+=================+========================================================+
| value           | The value of the option                                |
+-----------------+--------------------------------------------------------+
| optionType      | The option's family                                    |
+-----------------+--------------------------------------------------------+
| variants        | A collection of product variants that uses this option |
+-----------------+--------------------------------------------------------+


OptionType
-----------

An **OptionType** is linked to a product and holds a kind of option that can be used by variants.

+-----------------+--------------------------------------------------------------+
| Property        | Description                                                  |
+=================+==============================================================+
| name            | The name of the option type                                  |
+-----------------+--------------------------------------------------------------+
| options         | A collection of option linked to this option type            |
+-----------------+--------------------------------------------------------------+
| products        | A collection of products that uses this type of option       |
+-----------------+--------------------------------------------------------------+
