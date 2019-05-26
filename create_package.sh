#!/usr/bin/env bash

composer install

rm -rv build/*

mkdir build/BestItBugsnag

cp -rv Resources Source vendor LICENSE README.md BestItBugsnag.php build/BestItBugsnag/

cd ./build && zip -r ./BestItBugsnag.zip ./BestItBugsnag
