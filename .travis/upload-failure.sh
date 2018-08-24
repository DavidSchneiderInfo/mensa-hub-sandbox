#!/usr/bin/env sh

# -e = exit when one command returns != 0, -v print each command before executing
set -ev

# change to build directory / protected
cd ${TRAVIS_BUILD_DIR}/protected

# find _output folder and add to zip
find humhub/ -type d -name "_output" -exec zip -r --exclude="*.gitignore" failure.zip {} +

# add logs to failure.zip
zip -ur failure.zip runtime/logs

# upload file
curl --upload-file ./failure.zip https://transfer.sh/humhub-travis-${TRAVIS_JOB_NUMBER}.zip

# delete zip
rm failure.zip