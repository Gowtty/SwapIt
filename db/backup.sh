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

# Generar backup
mysqldump -u $DB_USER -p$DB_PASS --databases $DB_NAME > swapit.sql

# Subir cambios a GitHub
git add backup.sql
git commit -m "Backup automático de la base de datos"
git push origin main
