Using Blast Dashboard
=====================

Enable Blast Dashboard
----------------------

Import blast core config file in your application config file :
```yml
# app/config/config.yml

imports:
    # [ ... ]
    - { resource: "@BlastDashboardBundle/Resources/config/config.yml" }
    # [ ... ]

    # Your configuration below
```
This configuration file sets sonata_blocks and sonata_admin.dashboard base block

Create your dashboard block class
---------------------------------

Your class must/should extends class `Blast\Bundle\DashboardBundle\Block\AbstractBlock`

```php
<?php

namespace MyBundle\Dashboard;

use Blast\Bundle\DashboardBundle\Block\AbstractBlock;

class MyBundleDashboardBlock extends AbstractBlock
{
    public function handleParameters()
    {
        $this->templateParameters = [
            'myParameter'=> 'MyValue',
        ];
    }
}
```

Define your class as a service
------------------------------

```yml
services:
    my_bundle.dashboard.main:
        parent: blast_dashboard.block
        class: MyBundle\Dashboard\MyBundleDashboardBlock
        arguments:
            - 'MyBundle:Dashboard:my_dashboard.html.twig'
        tags:
            - { name: blast.dashboard_block }
```

Create your block template
--------------------------

```twig
<strong>My Block</strong>
{{ myParameter }}
```

And Voila !
