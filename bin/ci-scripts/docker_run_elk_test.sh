#!/usr/bin/env bash
set -v


max_map_count=$(sysctl -n vm.max_map_count)
if [ $max_map_count -lt 262144 ]
then
    echo "Please run :sudo sysctl -w vm.max_map_count=262144"
    exit 42
fi

curl -X GET 'http://127.0.0.1:9200'
if [ $? -ne 0 ]
then
    #sudo sysctl -w vm.max_map_count=262144
    #docker run -p 5601:5601 -p 9200:9200 -p 5044:5044 -it --name elk sebp/elk:563
    docker container start elk
fi

for i in $(curl -s -XGET "http://127.0.0.1:9200/_cat/indices" | grep open | cut -f3 -d' ');
do
    curl -s -XDELETE "http://127.0.0.1:9200/$i";
done
