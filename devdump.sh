#!/bin/bash

USAGE="Usage: `basename $0` -u user [-p] [-f filename]"

function error()
{
    if [ "$1" ]
    then
        echo "ERROR: $1"
    fi
    echo $USAGE >&2
    exit 1
}

date=`date +%Y-%m-%d`
file="db/backup/dump_${date}.sql"

while getopts hu:f:p OPT; do
    case "$OPT" in
        h)
            echo $USAGE
            exit 0
            ;;
        u)
            user=$OPTARG
            ;;
        f)
            file=$OPTARG
            ;;
        p)
            getPass=1
            ;;
        \?)
            echo $USAGE >&2
            exit 1
            ;;
    esac
done

if [ -z "${user}" ]
then
    error "Missing -u user option"
fi

if [ "${getPass}" == "1" ]
then
    read -s -p "MySQL password: " password
    passParam="-p${password}"
else
    passParam=""
fi

echo

echo "Dumping production database..."
mysqldump --routines --single-transaction -u ${user} ${passParam} stratus > ${file}

echo "Creating stratus-dev database..."
mysql -u ${user} ${passParam} -e "drop database if exists \`stratus-dev\`;"
mysql -u ${user} ${passParam} -e "create database \`stratus-dev\`;"

echo "Sourcing stratus-dev with production dump..."
mysql -u ${user} ${passParam} stratus-dev -e "source ${file};"

echo "Applying update.sql..."
mysql -u ${user} ${passParam} stratus-dev -e "source db/update.sql;"

echo "Complete."