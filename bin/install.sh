#!/bin/bash

echo $1 $2 $3 ' -> echo $1 $2 $3'

git clone git://github.com/ARIPD/albatros.git
cd albatros
#rm -rf .git
rm bin/install.sh

cat > app/config/parameters.yml <<EOF
parameters:
    database.driver:   pdo_mysql
    database.host:     localhost
    database.port:     3306
    database.dbname:   albatros
    database.user:     albatros
    database.password: $1
    
    mailer_transport:   smtp #gmail
    #mailer_encryption: ssl
    #mailer_auth_mode:  login
    #mailer_port:       465
    mailer_host:        mail.domain.tld #smtp.gmail.com
    mailer_user:        alias@domain.tld
    mailer_password:    $2
    
    facebook.app_id: 465170176873781
    facebook.secret: cbc5265cce167a2d08d676e48adf9bb5
    
    themes: [$3]
    theme_active: $3
    
    locales: [tr,en]
    locale: tr
    secret: fd3f6e3cc52ea6c471e1eb77e300d0d59678afd6
    
    mail_sender_name:    ARIPD
    mail_sender_address: "%mailer_user%"
    administrators: [bilgi@aripd.com]
    
    aripd_config.configTemplate.sectionOrder: [image, advertisement, module, analytic]
EOF

sudo mkdir app/cache app/logs web/cache web/uploads/files
sudo chmod +a "staff allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs web/cache web/uploads
sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs web/cache web/uploads
#sudo setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs web/cache web/uploads
#sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs web/cache web/uploads

curl -s http://getcomposer.org/installer | php
php composer.phar install

sh bin/reset.sh