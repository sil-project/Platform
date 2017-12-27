
wget -O ${HOME}/bin/phpunit https://phar.phpunit.de/phpunit-6.phar
chmod +x ${HOME}/bin/phpunit
${HOME}/bin/phpunit --version

wget -O ${HOME}/bin/phpcs https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
chmod +x ${HOME}/bin/phpcs
${HOME}/bin/phpcs --version

wget -O ${HOME}/bin/phpcbf https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar
chmod +x ${HOME}/bin/phpcbf
${HOME}/bin/phpcbf --version

wget -O ${HOME}/bin/phploc https://phar.phpunit.de/phploc.phar
chmod +x ${HOME}/bin/phploc
${HOME}/bin/phploc --version

wget -O ${HOME}/bin/pdepend http://static.pdepend.org/php/latest/pdepend.phar
chmod +x ${HOME}/bin/pdepend
${HOME}/bin/pdepend --version

wget -O ${HOME}/bin/phpmd http://static.phpmd.org/php/latest/phpmd.phar
chmod +x ${HOME}/bin/phpmd
${HOME}/bin/phpmd --version

wget -O ${HOME}/bin/phpcpd https://phar.phpunit.de/phpcpd.phar
chmod +x ${HOME}/bin/phpcpd
${HOME}/bin/phpcpd --version

wget -O ${HOME}/bin/phpdox http://phpdox.de/releases/phpdox.phar
chmod +x ${HOME}/bin/phpdox
${HOME}/bin/phpdox --version




#### As ant will try to run test ...
bin/ci-scripts/create_travis_database_test.sh
bin/ci-scripts/install_test.sh
