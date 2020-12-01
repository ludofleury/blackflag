# :pirate_flag: Black Flag

[![php version](https://img.shields.io/badge/php-v8.0-blue?style=flat&logo=php)](php/composer.json#L6)
[![build Status](https://github.com/ludofleury/blackflag/workflows/ci/badge.svg?branch=main)](https://github.com/ludofleury/blackflag/actions)
[![codecov](https://codecov.io/gh/ludofleury/blackflag/branch/main/graph/badge.svg?token=u7d7nhlwb8)](https://codecov.io/gh/ludofleury/blackflag)
[![phpstan](https://img.shields.io/badge/phpstan-level%208-brightgreen.svg?style=flat)](CONTRIBUTING.md#phpstan)
[![psalm](https://img.shields.io/badge/psalm-level%202-brightgreen.svg?style=flat)](CONTRIBUTING.md#psalm)
[![mutation testing](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fludofleury%2Fblackflag%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/ludofleury/blackflag/main)

PHP application designed to assist role-playing game session for the "Pavillon Noir" game.

Showcasing usage of:

  - Domain Driven Design
  - CQRS
  - Event Sourcing

## Setup

### Requirements

- docker
- docker-compose

### Bootstrap

1. Setup local docker-compose configuration, adjust any parameters in `docker-compose.override.yml`

```bash
  cp docker-compose.dev.yml docker-compose.override.yml
```

2. build docker images, install deps & initialize the stack `make local`

3. run the stack `make up`


## Development
### Dependency management with composer

1. run your docker-compose stack

```
make up
```

2. run the following commands

```
docker-compose exec php composer install
docker-compose exec php composer require xxx/yyyy
```

See [CONTRIBUTING](CONTRIBUTING.md) for tests & quality standards
