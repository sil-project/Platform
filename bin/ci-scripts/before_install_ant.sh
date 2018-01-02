#!/usr/bin/env bash
set -ev

composer global config bin-dir ${HOME}/bin

for i in 'pdepend/pdepend' 'squizlabs/php_codesniffer' 'phploc/phploc' 'phpmd/phpmd' 'sebastian/phpcpd' 'theseer/phpdox'
do
    composer global require $i:@stable --prefer-dist --no-interaction
done

         
