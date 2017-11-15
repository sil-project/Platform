#!/usr/bin/env sh
set -ev

# start server as prod for travis timeout on dev...
# bin/console server:stop
bin/console cache:clear --no-interaction --env=prod
bin/console server:start --no-interaction 127.0.0.1:8042 --env=prod

