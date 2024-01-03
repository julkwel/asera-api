
[![GitHub Actions](https://github.com/api-platform/core/workflows/CI/badge.svg)](https://github.com/api-platform/core/actions?workflow=CI)

# ASERA API

## Requirements :
```
    - Docker & docker compose installed
```

## Pre-install
```
    - Verify docker compose file, you can personalise yours
    - Verify all secret key (database, mercure ... ) in .env
```

## Installation :
```
    - clone this repository
    - run : docker compose build
    - run : docker compose up --wait
```

## Post-install
```
    - Generate JWT Token by : docker exec -T php bin/console lexik:jwt:generate-keypair
    - Verify docker container health
    - Verify HTTP accessibility
    - Try running unit test
    - Go to : https://localhost/
```


*Code for fun :heart: !*

## Credits

Created by [Julien Rajerison](https://github.com/julkwel).
