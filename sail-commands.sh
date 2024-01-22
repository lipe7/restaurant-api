#!/bin/bash

# Verificar se o ambiente Sail está em execução
if [ -f /.dockerenv ]; then

    php artisan config:clear
    php artisan optimize
    php artisan migrate
    php artisan storage:link
    php artisan db:seed

else
    echo "Execute o script dentro do ambiente Sail."
fi
