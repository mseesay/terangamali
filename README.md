## TERANGA v1

## Installation
To install this project, clone the repository and run the following commands:

```bash
composer install
```

Once the project is installed, configure it by creating a `.env` file in the root directory of the project. A sample `.env` file is provided in the `.env.example` file.
You must provide the following environment variables:

- ```SRV_LOCALHOST__DATABASE_BDDUAVFH``` is the name of the main database (default: ```maurlims```)
- ```SRV_LOCALHOST__USER_ARBO__USERNAME``` is the username of the main database user. Create one if necessary (```arbo``` is a good choice)
- ```SRV_LOCALHOST__USER_ARBO__PASSWORD``` is the password of the main database user. Choose whatever value you want.

## TERANGA v2
### How to use this framework ?
This framework provides code and folder structure to get started with you PHP application.

### Getting started
First, you need to edit configuration file for each hostname (domain). To acheive that, create a file with the same name as the host under ``/config/``.

You can also copy or rename `/config/sample.host.name.php`.

Edit these parameters :

**APP_NAME**, **APP_OWNER**, **BASE_URL**, **DATABASE_HOST**, **DATABASE_USER**, **DATABASE_NAME**, **DATABASE_PASSWORD**.

You may also change the other constants.