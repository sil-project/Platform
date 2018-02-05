========================
Account component domain
========================

-------
Models
-------

Account
-------

When a Contact is added to an Account, it is set as default.
When the contact is removed from the collection of the Account the default will be unset as well.If another Contact remains in the collection it will be set as default.

+-----------------+-------------------------------------------------------------------------+
| Property        | Description                                                             |
+=================+=========================================================================+
| name            | name of the Account                                                     |
+-----------------+-------------------------------------------------------------------------+
| code            | Account code                                                            |
+-----------------+-------------------------------------------------------------------------+
| defaultContact  | Reference to the Contact that will be used as default for the Account   |
+-----------------+-------------------------------------------------------------------------+
| contacts        | Collection of Contacts associated to the Account                        |
+-----------------+-------------------------------------------------------------------------+
