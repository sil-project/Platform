#!/usr/bin/env bash

for i in src/Blast/Bundle/* src/Sil/Bundle/*
do
    echo $i
    git subrepo pull $i
done