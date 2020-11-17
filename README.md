#installation guide

1. Composer install
2. For migrations used phinx
3. Configure phinx.yml file 
4. Configure App/Config.php 
5. Phinx migrate
6. Routes registered on public/index.php file
7. Used ORM Eloquent
8. Used fro validation : Rakit Validation - PHP Standalone Validation Library
    [Link](https://github.com/rakit/validation) 
9. For templates used Twig
   [Link](https://twig.symfony.com/)
10. For migration please run `"./vendor/bin/phinx" migrate`
11. For seed please run `"./vendor/bin/phinx" seed:run`
12. admin username/pass -> `admin/123`