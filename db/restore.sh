#!/bin/bash

# Cargar variables de entorno
if [ -f .env ]; then
    export $(cat .env | xargs)
fi

# Verificar que las variables existan
if [[ -z "$DB_NAME" || -z "$DB_USER" || -z "$DB_PASS" ]]; then
    echo "Error: Faltan variables en el archivo .env"
    exit 1
fi

# Restaurar la base de datos
mysql -u $DB_USER -p$DB_PASS $DB_NAME < swapit.sql