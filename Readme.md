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
7. Load fixtures (if needed):
    ```bash
    php bin/console doctrine:fixtures:load
    ```
    If you want only append data:
    ```bash
    php bin/console doctrine:fixtures:load --append
    ```


