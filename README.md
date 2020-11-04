marvel-kata
===========

Una primera aproximación a la marvel-kata, mediante TDD
http://nikeyes.github.io/MarvelKata/#/portada

## Install

```
docker run --rm --interactive --tty \
  --volume $PWD:/app \
  composer install
```

Run tests
```
 docker-compose run api /app/vendor/bin/phpunit      
```