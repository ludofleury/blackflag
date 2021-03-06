# Contributing to Black Flag

## Tests

### PHPunit

Acceptable coverage baseline: **80%**

Locally run the tests suite with `make sure` or:

```bash
  docker-compose exec php vendor/bin/phpunit --testdox
```

## Mutation testing

### Infection

* Acceptable Mutation Score Indicator (MSI): **80%**
* Acceptable Covered Code Mutation Score Indicator: **90%**

Locally run the tests suite with `make bulletproof` or:

```bash
  docker-compose exec php vendor/bin/infection
```

## Code quality

### phpstan

* :1st_place_medal: standard: [level 8](https://phpstan.org/user-guide/rule-levels)
* configuration: [php/phpstan.neon](php/phpstan.neon)

### psalm

* :2nd_place_medal: standard: [level 2](https://psalm.dev/docs/running_psalm/error_levels/)
* configuration: [php/psalm.xml](php/psalm.xml)

Locally run the  static analysis with `make better` or:

```bash
  docker-compose exec php vendor/bin/phpstan analyse
  docker-compose exec php vendor/bin/psalm
```