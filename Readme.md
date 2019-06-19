### Requirements
1. PHP >= 7.3
2. Mysql >= 8.0
3. Yarn
4. 
### Install
1. Composer install
    ```bash
    comoposer install
    ```
2. Setup env file, copy env.example and fill vars.
3. Create database:
    ```
    php bin/console doctrine:database:create
    ```
4. Run migrations:
    ```bash
    php bin/console migrate
    ```
5. Install ckeditor:
    ```bash
    php bin/console ckeditor:instal
    ```
6. Install assets:
    ```bash
    php bin/console assets:install --symlink
    ```
7. Install node_modules:
    ```bash
    yarn install
    ```
8. Build frontend:
    ```bash
    yarn encore {env}
    ```
    where env: dev|test|prod
9. Load fixtures (if needed):
    ```bash
    php bin/console doctrine:fixtures:load
    ```
    If you want only append data:
    ```bash
    php bin/console doctrine:fixtures:load --append
    ```

### Utils
1. php-cs-fixer run:

    Windows
    ```bash
    vendor\bin\php-cs-fixes.bat fix src
    ```
    Unix
    ```bash
    ./vendor/bin/php-cs-fixes fix src
    ```

### Commands
1. Make user admin:
    ```bash
    php bin\console user:set-user-admin {userId}
    ```
    userId - id пользователя

### Tests
1. Run
    ```bash
    php bin/phpunit
    ```
