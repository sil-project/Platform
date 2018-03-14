Configuration
=============

Le bundle permet de configurer plusieurs aspects des interfaces :

------
Thèmes
------

.. code-block:: yaml

    # app/config/config.yml

    blast_ui:
        themes:
            - default
            - dark
        defaultTheme: default

Sous la clé ``blast_ui.themes`` est définis un tableau des thèmes autorisés / gérés. Ce tableau est principalement utilisé pour le sélecteur de thèmes présent dans la top bar.

------
Assets
------

.. code-block:: yaml

    # app/config/config.yml

    blast_ui:
        sidebar:
            title: 'SIL Platform'
            logo: /bundles/blastui/img/li-logo.png

Il est possible de définir par le biais de cette configuration le logo et le nom de l'application qui apparaissent dans la barre du menu principal.

---------
Templates
---------

.. code-block:: yaml

    # app/config/config.yml

    blast_ui:
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
            widget_step_nav: '@BlastUI/Widget/Step/nav.html.twig'
            widget_step_header: '@BlastUI/Widget/Step/header.html.twig'
            widget_table: '@BlastUI/Widget/Table/table.html.twig'

Pour chaque template utilisé par des widgets et des éléments de formulaires simples (hors thème de formulaire), il est possible de redéfinir les templates qui seront utilisés par défaut de manière générale.

Cela permet de faire des surcharges générales tout en conservant les surcharges ponctuelles faites lors des appels aux widgets.

--------------------------
Configuration de référence
--------------------------

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
            widget_step_nav: '@BlastUI/Widget/Step/nav.html.twig'
            widget_step_header: '@BlastUI/Widget/Step/header.html.twig'
            widget_table: '@BlastUI/Widget/Table/table.html.twig'
