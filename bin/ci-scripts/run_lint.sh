#!/usr/bin/env bash

rm -f composer.lock

composer validate --no-check-lock

bin/ci-scripts/do_it_for_bundle.sh run lint
