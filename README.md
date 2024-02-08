# Instructions

## Setup and running

### Setup the project
```sh
docker compose build --no-cache
docker compose exec php composer install
docker compose exec php php bin/console doctrine:migrations:migrate
```

### Running the tests
```sh
docker compose exec php composer run test:integration
docker compose exec php composer run test:unit
docker compose exec php composer run qa:mutation
```

### Running the checks
```sh
docker compose exec php composer run qa:stan
docker compose exec php composer run code:style
docker compose exec php composer run pkgs:audit
docker compose exec php composer run pkgs:licenses
docker compose exec php composer run pkgs:require-checker
docker compose exec php composer run pkgs:unused
```

### Running the project

DISCLAIMER: This project was developed through tests, which rely on fixtures' data.
It is not expected to be production ready in this stage.
Basic requests were tested with curl and browser before authentication was introduced.

```sh
docker compose up
```


- - -

## More docs
- [API](docs/API.md)
