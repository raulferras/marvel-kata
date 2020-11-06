marvel-kata
===========

Una primera aproximación a la marvel-kata, mediante TDD y usando mountebank
http://nikeyes.github.io/MarvelKata/#/portada

## How to use it

1. Start mountebank (`docker-compose -f docker-compose.yaml -f docker-compose-test.yaml up -d`)
1. Run the tests (`docker-compose run -e API_HOST=mountebank:3016 api /app/vendor/bin/phpunit`)
1. Profit

## Adding a dependency
`docker run --rm --interactive --tty --volume $PWD:/app composer require a-dependency`