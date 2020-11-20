# :pirate_flag: Black Flag

[![Build Status](https://github.com/ludofleury/blackflag/workflows/ci/badge.svg?branch=main)](https://github.com/ludofleury/blackflag/actions)
[![codecov](https://codecov.io/gh/ludofleury/blackflag/branch/main/graph/badge.svg?token=u7d7nhlwb8)](https://codecov.io/gh/ludofleury/blackflag)

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

2. build docker images, install deps & initialize the stack

```bash
  make local
```

3. run the stack

```bash
  make up
```

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
