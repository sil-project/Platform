# Blast Menu Bundle
Bundle that generates a sonata admin global menu via `blast_menu.yml` configuration file

Example of sonata config :

```yaml
sonata_admin:
    templates:

        # [...]

        knp_menu_template:          BlastMenuBundle:Menu:sonata_menu.html.twig

        # [...]

    dashboard:

        groups:

            App:
                provider: global_sidebar_menu
                icon: ' '
```
