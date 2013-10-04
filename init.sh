#!/bin/bash
if ! type npm; then
    echo "You must have node / npm installed. Once completed, run this script again."
    exit
fi

BASEDIR=$(dirname $0)

cd $BASEDIR
npm install
./node_modules/bower/bin/bower install

echo "Finished injecting dependencies."
