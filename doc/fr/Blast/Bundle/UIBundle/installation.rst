Installation
============

------------
Via composer
------------

.. code-block:: bash

    $ composer require blast-project/ui-bundle


Ajouter au kernel le bundle

.. code-block:: php

    <?php

    class AppKernel {

        public function registerBundles()
        {
            $bundles = [
                // ...

                new \Blast\Bundle\UIBundle\BlastUIBundle(),

                // ...
            ];
        }
    }

-------------
Configuration
-------------

Dans le fichier de configuration ``app/config/config.yml``, définir la configuration selon vos besoins

.. code-block:: yaml

    blast_ui:
        sidebar:
            title: 'My platform'
            logo: /bundles/mybundle/img/mylogo.png
        themes:
            - default
            - dark
        defaultTheme: default

La configuration complète (avec les valeurs par défaut) :

.. code-block:: yaml

    blast_ui:
        sidebar:
            title: 'SIL Platform'
            logo: /bundles/blastui/img/li-logo.png
        themes:
            - default
            - dark
        defaultTheme: default
        templates:
            form_type_button: '@BlastUI/Form/Type/button.html.twig'
            form_type_checkbox: '@BlastUI/Form/Type/checkbox.html.twig'
            form_type_checkboxes: '@BlastUI/Form/Type/checkboxes.html.twig'
            form_type_datetime: '@BlastUI/Form/Type/datetime.html.twig'
            form_type_hidden: '@BlastUI/Form/Type/hidden.html.twig'
            form_type_link: '@BlastUI/Form/Type/link.html.twig'
            form_type_number: '@BlastUI/Form/Type/number.html.twig'
            form_type_select: '@BlastUI/Form/Type/select.html.twig'
            form_type_submit: '@BlastUI/Form/Type/submit.html.twig'
            form_type_text: '@BlastUI/Form/Type/text.html.twig'
            form_type_textarea: '@BlastUI/Form/Type/textarea.html.twig'
            widget_datacard_card: '@BlastUI/Widget/DataCard/card.html.twig'
            widget_field_form_group: '@BlastUI/Widget/Field/form_group.html.twig'
            widget_field_show_group: '@BlastUI/Widget/Field/show_group.html.twig'
            widget_panel: '@BlastUI/Widget/Panel/panel.html.twig'
            widget_simple_panel: '@BlastUI/Widget/Panel/simple_panel.html.twig'
            widget_step_nav: '@BlastUI/Widget/Step/nav.html.twig'
            widget_step_header: '@BlastUI/Widget/Step/header.html.twig'
            widget_table: '@BlastUI/Widget/Table/table.html.twig'
            widget_modal: '@BlastUI/Widget/Modal/modal.html.twig'

------
Tester
------

Ajouter la route de test à app/config/routing_dev.yml

.. code-block:: yaml

    blast_ui_test:
        path: /blast/ui/test
        defaults: { _controller: 'BlastUIBundle:Test:test' }

Cette route affichera le template situé dans ``src/Blast/Bundle/UIBundle/Resources/views/_test.html.twig``.
