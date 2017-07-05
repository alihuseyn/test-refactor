#!/usr/bin/env bash

docker build -t alihuseyn13/php-composer ./docker
docker run -it -v $(pwd):/app alihuseyn13/php-composer /bin/bash
