#!/usr/bin/env sh
set -ev

######### TOOLS
if [ $# -eq 0 ]
then
    # start fake x
    /sbin/start-stop-daemon --start --quiet --pidfile /tmp/xvfb_99.pid --make-pidfile --background --exec /usr/bin/Xvfb -- :99 -ac -screen 0 1680x1050x16
    export DISPLAY=:99
fi

#export PATH=$PATH:./bin
sel_start_date=$(date)
#bin/selenium-server-standalone -debug -enablePassThrough false > selenium.log 2>&1  &
java -jar ${HOME}/bin/selenium-server-standalone.jar -debug -enablePassThrough false  > selenium.log 2>&1  &

set +e
while [ $(netstat -an | grep LISTEN | grep 4444| wc -l) -eq 0 ]
do
    echo $(date) " wait for selenium start... (since " $sel_start_date ")";
    ps -eaf | grep selenium;
    netstat -an | grep LISTEN | grep 4444;
    sleep 10;
done

echo $(date) " it look like selenium is started (waiting since " $sel_start_date ")";



# start server as prod for travis timeout on dev...
# bin/console server:stop
bin/console cache:clear --no-interaction #--env=prod
bin/console server:start --no-interaction 127.0.0.1:8042 #--env=prod

