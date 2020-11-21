# MyNews

## Installation
Use composer to install needed packages

```bash
composer install
```

## Usage
Tu use the fixtures data, and try the site, you'll need to create the database, make the migrations and load fixtures.

### Database
First, we'll create the database :
```bash
doctrine:database:create
````

### Tables and relations
Then, we'll make migrations, so that we can create the tables, relations, etc ...
```bash
php bin/console make:migration
````

```bash
php bin/console doctrine:migrations:migrate
```

### Fixtures
When the database and the tables are created, we can load the fixtures :
```bash
php bin/console doctrine:fixtures:load
```
