Using BlastPatcher command tools for patches
============================================

A custom command has been created to manage custom patches on vendors (awaiting official release of patches)

##### Generating a patch file

Put your patched file into any folder you want in your Symfony project (should be in your src/ or in app/)

Execute the following command :

`$ bin/console blast:patchs:generate path/to/original-file path/to/your/modified-file path/to/target-file`

`path/to/original-file` can be a github URL (raw file URL) or any URL, or a path to a local file. This file is used to be compared when generating the patch file.

`path/to/your/modified-file` can be a github URL (raw file URL) or any URL, or a path to a local file. This file contains your code modification to be applied.

`path/to/target-file` must be a relative path to the file that will be patched (relative to the Symfony project root (the folder containing app/, src/, vendor/ ...))

This command creates the patch file (e.g. : `1447941862.txt`) under `PatcherBundle\Patches\` directory.
It adds an entry into `patches.yml` under `PatcherBundle\Patches\` directory.

##### Listing patch files managed by the tool

Execute the following command :

`$ bin/console blast:patchs:list` to see all managed patches by the tool.

E.g. :

```
[INFO]
Listing available patches:


[INFO]   - - - -
[INFO] id: 1507293335
[INFO] enabled: true
[INFO] targetFile: vendor/codeception/codeception/src/Codeception/Subscriber/ErrorHandler.php
[INFO] patchFile: 1507293335.txt
[INFO]   - - - -
[INFO] id: 1498652336
[INFO] enabled: true
[INFO] targetFile: vendor/sylius/sylius/src/Sylius/Bundle/CoreBundle/Doctrine/ORM/ProductRepository.php
[INFO] patchFile: 1498652336.txt
[INFO]   - - - -
```

##### Apply patches

Execute the following command :

`$ bin/console blast:patchs:apply`

It will apply patches by parsing `patches.yml`.

##### Apply patches when using composer.phar

A composer command has been created in order to automate the patching process when doing composer updates and installations.

To enable this feature, just add this line in your `composer.json` :

```json
{
    "name": "you/your-project",
    # ...

    "scripts": {
        "post-install-cmd": [

            # ...

            "Blast\\Bundle\\PatcherBundle\\Composer\\Patcher::applyPatches"
        ],
        "post-update-cmd": [

            # ...

            "Blast\\Bundle\\PatcherBundle\\Composer\\Patcher::applyPatches"
        ]
    },

    # ...

}
```
