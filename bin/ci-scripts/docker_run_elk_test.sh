#!/usr/bin/env bash
set -v


curl -X GET 'http://127.0.0.1:9200'
if [ $? -ne 0 ]
then
    #sudo sysctl -w vm.max_map_count=262144
    #docker run -p 5601:5601 -p 9200:9200 -p 5044:5044 -it --name elk sebp/elk:563
    docker container start elk
fi

