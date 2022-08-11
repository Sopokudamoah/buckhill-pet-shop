# Pet Shop API

An API for managing an online pet shop. The requirements can be found [here](https://buckhill.atlassian.net/wiki/spaces/BR/blog/2022/07/22/1690435585/Backend+Developer+Task+July+2022+Pet+Shop+eCommerce).

## Requirements

- PHP 8.1
- [OpenSSL](https://www.php.net/manual/en/openssl.requirements.php) for generating public and private keys for JWT
- [XDEBUG](https://xdebug.org/docs/code_coverage) with coverage enabled for test [optional].
## Setup

The following attributes in your `.env` file must be set and valid.

```dotenv
APP_URL=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```


## Installation
All installation steps can be found in the `deploy.sh` file. The script will 

* Install dependencies
* Set application key
* Migrate tables
* Create default admin account
* Seed database with data
* Generate private and public keys for JWT generation
* Regenerate API documentation using [scribe](https://scribe.knuckles.wtf/).

```bash
./deploy.sh
```

## API Documentation
The swagger docs can be found [here](https://app.swaggerhub.com/apis/SOPOKU22/pet-shop/1.0.0). A copy of the openAPI yaml file can be found in.
```
public/docs/openapi.yaml
```

## Test
All tests were written using [pest](https://pestphp.com/). Run tests with the command below.

```bash
./vendor/bin/pest --coverage
```

## License
[MIT](https://choosealicense.com/licenses/mit/)
