#!/bin/sh

php app/console doctrine:generate:entities ARIPD
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load

php app/console assetic:dump --env=prod --no-debug
php app/console translation:extract tr --config=app --output-format=xliff
php app/console translation:extract en --config=app --output-format=xliff
sudo chmod -R 777 app/Resources/translations/*

sudo rm -rf app/cache/* app/logs/* web/cache/* web/uploads/files/*

#php bin/sami.php update bin/sami_config.php -v

#sudo apachectl restart
#sudo service apache2 restart
