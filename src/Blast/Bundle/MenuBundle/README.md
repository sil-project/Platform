# Blast Menu Bundle

Bundle that generates a sonata admin global menu via `blast_menu.yml` configuration file

## Requirements

You must have defined the `sonata_admin` configuration as below :

```yaml
sonata_admin:
    templates:

        # [...]

        knp_menu_template:          BlastMenuBundle:Menu:sonata_menu.html.twig

        # [...]

    dashboard:

        groups:

            App:                                  # This group name could be any string
                provider: global_sidebar_menu     # « global_sidebar_menu » is the BlastMenuBundle menu provider
```

## Usage

Here an example of using blast_menu :

```yaml
blast_menu:

    root:                        # The main menu element

        my.menu.item:
            icon: add                       # Any FontAwesome icon class name (without the « fa- »)

            children:

                my.menu.sub_item:
                    icon: cloud-upload
                    route: my_menu_upload   # any valid route name (without mandatory parameters)
                    order: 10               # order will be applyed in current menu depth only

                my.menu.sub_item_2:
                    icon: cloud-download
                    route: my_menu_download
                    order: 11

                my.menu.sub_item_3:
                    icon: cloud-download
                    route: my_menu_download
                    order: 12
                    display: false          # Hide this menu item

    settings:                    # The settings menu element

        my.menu.item_settings:
            icon: gear
            route: my_menu_settings
```

## Overriding an existing menu

Supposing that the previous menu example is used in your application, you can, from any other bundle, override any item of the `MyMenu` bundle.

_Note : Your bundle **must be included after** the bundle you want to override._

You can override any attribute of any item (`icon`, `route`, `order`, `display`, `label`). You can't override `children` but you can append elements into an existing `children` attribute. You can't remove item from `children` but it's possible to hide item by setting `display` to `false`.

### Overrinding icon

```yaml
blast_menu:

    root:

        my.menu.item:
            icon: remove                    # The item icon will be overriden
```

### Hidding item

```yaml
blast_menu:

    root:

        my.menu.item:
            children:

                my.menu.sub_item:
                    display: false          # This item will be hidden
```

### Reordering item

```yaml
blast_menu:

    root:

        my.menu.item:
            children:

                my.menu.sub_item_3:
                    order: 5               # « sub_item_3 » will be displayed before « my.menu.sub_item »
                    display: true          # Displaying the hidden item
```
