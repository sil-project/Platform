Contact
------------

When an Address or Phone is added to a Contact, it is set as default.
When the object is removed from the collection of the Contact the default will be unset as well.If another entry remains in the collection it will be set as default.

+-----------------+-----------------------------------------------------------------------------+
| Property        | Description                                                                 |
+=================+=============================================================================+
| firstName       | First name of the contact                                                   |
+-----------------+-----------------------------------------------------------------------------+
| lastName        | Last name of the contact                                                    |
+-----------------+-----------------------------------------------------------------------------+
| title           | Title of the contact                                                        |
+-----------------+-----------------------------------------------------------------------------+
| email           | Email address of the contact                                                |
+-----------------+-----------------------------------------------------------------------------+
| position        | Position of the contact (job)                                               |
+-----------------+-----------------------------------------------------------------------------+
| defaultAddress  | Reference to the address that will be used as default for the contact       |
+-----------------+-----------------------------------------------------------------------------+
| addresses       | Collection of addresses for the contact                                     |
+-----------------+-----------------------------------------------------------------------------+
| defaultPhone    | Reference to the phone number that will be used as default for the contact  |
+-----------------+-----------------------------------------------------------------------------+
| phones          | Collection of phones for the contact                                        |
+-----------------+-----------------------------------------------------------------------------+
