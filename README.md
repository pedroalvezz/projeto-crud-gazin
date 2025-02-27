1. Clone o repositório
Primeiro, clone o repositório para sua máquina local:

bash
Copiar
Editar
git clone <URL-DO-REPOSITORIO>
cd <nome-do-repositorio>

2. Copie o arquivo .env.example
O Laravel precisa de um arquivo .env para configurar variáveis de ambiente. Copie o arquivo .env.example para .env:

bash
Copiar
Editar
cp .env.example .env

3. Configure as variáveis de ambiente no .env
Abra o arquivo .env e configure as variáveis de ambiente conforme necessário. As configurações padrão de banco de dados devem ser adequadas para o Docker, mas você pode ajustá-las se necessário:

env
Copiar
Editar
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=api_laravel
DB_USERNAME=root
DB_PASSWORD=root

4. Suba os containers Docker
Com tudo configurado, você pode iniciar os containers utilizando o Docker Compose. No diretório raiz do projeto, execute:

bash

docker-compose up --build

O --build vai garantir que os containers sejam reconstruídos caso haja alguma modificação no Dockerfile ou nas configurações.

Isso irá:

Criar o container do MySQL.
Criar o container do Laravel.
Criar o container do nginx (se você optar por usá-lo futuramente).

5. Instalar as dependências do Laravel
Após os containers estarem em execução, você precisará instalar as dependências do Laravel dentro do container. O Docker Compose já está configurado para rodar o comando composer install automaticamente, mas caso queira rodar manualmente, entre no container:

bash
docker exec -it <nome-do-container-app> bash
Dentro do container, execute o comando:

bash
composer install

6. Acessar a aplicação
Agora, a aplicação deve estar rodando no endereço:

bash
http://localhost:8000