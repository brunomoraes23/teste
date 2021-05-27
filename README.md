
## instalação local:
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs


## criar .env
sudo cp .env.example .env


## token
adicione a token na chave APP_AUTHORIZATION no arquivo .env


## rodando local:
./vendor/bin/sail up -d


## executando as migrations:
./vendor/bin/sail artisan migrate


## iniciando o scheduler local:
./vendor/bin/sail artisan schedule:work


## parando de rodar local:
./vendor/bin/sail down