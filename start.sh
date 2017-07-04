if docker images -q alihuseyn13/php-composer
then
    docker run -it -v $(pwd):/app alihuseyn13/php-composer /bin/bash
else
    docker build -t alihuseyn13/php-composer ./docker
    docker run -it -v $(pwd):/app alihuseyn13/php-composer /bin/bash
fi