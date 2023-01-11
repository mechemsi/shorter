#!/usr/bin/env sh

set -eu

pip install --no-cache-dir docker-compose
docker-compose -v
