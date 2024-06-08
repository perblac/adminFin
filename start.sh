#!/bin/bash
# Initialize yii2 files
echo Initialize yii2 files
./init --env=Production --overwrite=All --delete=All

# Decrypt
echo Decrypt variables
echo "Ingrese la clave para descifrar las variables:"
read -s clave
if openssl enc -d -aes-256-cbc -salt -pbkdf2 -in domain-db-mail.enc -out domain-db-mail.decoded.php -k "$clave"; then
    echo "Archivo domain-db-mail.enc descifrado correctamente."
else
    echo "Clave incorrecta. No se pudo descifrar el archivo domain-db-mail.enc"
    rm domain-db-mail.decoded.php
fi
if openssl enc -d -aes-256-cbc -salt -pbkdf2 -in docker-compose.enc -out docker-compose.decoded.yml -k "$clave"; then
    echo "Archivo docker-compose.enc descifrado correctamente."
else
    echo "Clave incorrecta. No se pudo descifrar el archivo docker-compose.enc"
    rm docker-compose.decoded.yml
fi
if openssl enc -d -aes-256-cbc -salt -pbkdf2 -in populate.enc -out populate.decoded.sh -k "$clave"; then
    echo "Archivo populate.enc descifrado correctamente."
else
    echo "Clave incorrecta. No se pudo descifrar el archivo populate.enc"
    rm populate.decoded.sh
fi

if [ -f "domain-db-mail.decoded.php" ]; then
    echo Copying decoded file
    cp domain-db-mail.decoded.php common/config/main-local.php
else
    echo Copying domain-db-mail.php
    cp domain-db-mail.php common/config/main-local.php
fi

if [ -f "docker-compose.decoded.yml" ]; then
    echo Copying decoded file
    cp  docker-compose.decoded.yml docker-compose.yml
fi

# Build docker containers
echo Build docker containers
docker compose build
# Up containers
echo Up containers
./up.sh
#   get dependencies
echo getting dependencies
docker compose run --name deps frontend composer update
docker rm deps
#   make migrations in db
echo making migrations in db
docker compose run --name migration frontend php yii migrate --interactive=0
docker rm migration
#   create default admin user
echo creating default admin user
docker compose run --name createadmin frontend php yii admin/create -u admin -p 12345678
docker rm createadmin

if [ "$1" != "--new-db" ]; then
  echo populating database
  #   copy sql files
  docker compose cp dbdata/user.sql mysql:/user.sql
  docker compose cp dbdata/notification.sql mysql:/notification.sql
  docker compose cp dbdata/conversation.sql mysql:/conversation.sql
  docker compose cp dbdata/notification_conversation.sql mysql:/notification_conversation.sql

  #   populate db
  if [ -f populate.decoded.sh ]; then
    chmod +x populate.decoded.sh
    docker compose cp populate.decoded.sh mysql:/populate.sh
  else
    docker compose cp populate.sh mysql:/populate.sh
  fi
  docker compose exec mysql /bin/bash /populate.sh
else
  echo "Generando base de datos en blanco"
fi

#   display docker containers
docker compose ps
