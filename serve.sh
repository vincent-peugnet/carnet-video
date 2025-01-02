#!/bin/bash
#
# Lauch local server at http://localhost:8066

cd build || exit 1
php -S localhost:8066
