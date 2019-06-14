### Install
n - 2. Install ckeditor:
```bash
php bin/console ckeditor:instal
```
n - 1. Install assets:
```bash
php bin/console assets:install --symlink
```
n. Load fixtures (if needed):
```bash
php bin/console doctrine:fixtures:load
```
If you want only append data:
```bash
php bin/console doctrine:fixtures:load --append
```


