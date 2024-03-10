# Desafio concluído com SUCESSO

Olá prezados!! 😃🚀🚀🚀

É com muita alegria que elaboro esse documento exibindo todos os resultados da construção da nossa aplicação de CALLCENTER.

Adorei cada etapa do processo, muito obrigado pelo espaço!!

## Tecnologias Utilizadas 

* PHP 8.3.3
* Docker 28.0
* Sonarqube
* PHPUnit 9
* Phinx (para geração de migrations)
* MySQL 8.0
* Nginx
* Supervisor de processos Linux
* GIT
* COMPOSER
* Xdebbug
* Visual Studio Code

## Get Started 

1 - Configure o seu arquivo hosts para acessar a aplicação pelo endereço correto configurado no sites-enabled do nginx: 127.0.0.1 callcenter.app.br

2 - Na pasta compactada o dump.sql está presente no diretório /docker/db/sql/dump.sql. Então o banco deve ser carregado sem problemas automaticamente, quando executado o build dos containers.

3 - Execute o docker-compose build -d na raiz do projeto (exatamente onde foi descompactado)

5 - Para acessar aos relatórios de cobertura de testes do Sonarqube, abra em http://localhost:9000 e como alternativa em caso de falha, eu deixei um html gerado dentro de /app/coverage, 

criado pelo próprio phpunit que mostra a exata mesma cobertura do sonarqube. 

6 - executa dentro do container backend o comando composer install dentro do diretório /var/www/app.

7 - para executar os testes do phpunit, execute o seguinte comando dentro do /var/www/app: /vendor/bin/phpunit /var/www/app/src/tests/unit (vai trazer o resultado de todos os testes)

8 - Você pode ver a tela inicial no endereço http://callcenter.app.br ou http://localhost/

9 - Para ver os resultados da API, basta acessar http://callcenter.app.br/api/ramais ou http://localhost/ramais

10 - Para melhor visualizar os dados no banco de dados, sugiro usar o PHPMyAdmin: http://localhost:8081 com as credenciais abaixo:

user dbuser

password 12345678

Ele logará e logo verá a base de dados sandbox (nela tem todas as tabelas do projeto). 

## Lista dos requisitos contemplados

* (OK) Os ramais offiline não são exibidos corretamente no painel, para corrigir você deverá exibir os ramais indisponiveis, fazendo com que o card do painel fique cinza e traga um icone circular no canto superior direito com a cor cinza mais escura. 
* (OK) Os ramais que estão em pausa no grupo de callcenter não estão sendo exibidos corretamente, para corrigir você deverá exibir os ramais que estão com com status de pausa, trazendo um icone circular no canto superior direito com a cor laranja.
* (OK) Os card deverão exibir os nomes dos agentes que estão no grupo de callcenter SUPORTE (arquivo lib\filas)

* (OK) Transformar o arquivo lib\ramais.php em uma classe e utiliza-lo neste sistema. Após a criação da classe o arquivo lib\ramais.php não deverá ser mais utilizado.
* (OK)Apesar dos registros serem estaticos, deverá ser criada uma base de dados utilizando mysql ou mariadb para armazenar as informações de cada ramal, como numero, nome, IP,  status do ramal no grupo de callcente (disponivel, pausa, offiline, etc).
* (OK) As informações da tela devem ser atualizadas a cada 10 segundos utilizando ajax e estas informações devem ser atualizadas na base de dados. Para verificar se está sendo atualizado na base de dados você poderá alterar as informações dos arquivos  lib\filas e lib\ramais (Nesse tópico eu construi algumas classes para gerar automaticamente relatórios diferentes)
* (OK) Ao concluir o teste, deverá ser encaminhado um arquivo .zip contendo todo o código, dump da base de dados e instruções de instalação e a lista das melhorias aplicadas.

## Resultados obtidos

![Tela de Monitor de Ramais](https://github.com/joseguilhermeromano/technical-test-l5-networks/tree/master/app/public/assets/img/resultados_telaapp.png)
![Retorno da API ramais](https://github.com/joseguilhermeromano/technical-test-l5-networks/tree/master/app/public/assets/img/resultados_api.png)
![Resultados Coverage PHPUnit](https://github.com/joseguilhermeromano/technical-test-l5-networks/tree/master/app/public/assets/img/resultados_coverage.png)
![Resultados do Sonar](https://github.com/joseguilhermeromano/technical-test-l5-networks/tree/master/app/public/assets/img/resultados_sonar.png)

## Muito Obrigado!!!

Deus abençoe o nosso match!! 🙏🙏🙏


# TESTE TÉCNICO L5 NETWORK (Descrição original)

## Teste analista junior

Neste teste você dispõe de um cenário fictício, onde há um painel de monitoramento de ramais que contem alguns bugs que precisam ser corrigidos. Este painel também deverá ser melhorado, o minimo de melhorias que deverá ser acrescentado serão 3. Abaixo uma relação dos itens que deverão ser corrigidos:

- Os ramais offiline não são exibidos corretamente no painel, para corrigir você deverá exibir os ramais indisponiveis, fazendo com que o card do painel fique cinza e traga um icone circular no canto superior direito com a cor cinza mais escura. 
- Os ramais que estão em pausa no grupo de callcenter não estão sendo exibidos corretamente, para corrigir você deverá exibir os ramais que estão com com status de pausa, trazendo um icone circular no canto superior direito com a cor laranja.
- Os card deverão exibir os nomes dos agentes que estão no grupo de callcenter SUPORTE (arquivo lib\filas)

### MELHORIAS  
Após a correção destes itens, você deverá aplicar ao menos 3 (três) melhorias neste sistema.

### OBRIGATÓRIO  
O teste também contará com algumas atividades obrigatórias:
- Transformar o arquivo lib\ramais.php em uma classe e utiliza-lo neste sistema. Após a criação da classe o arquivo lib\ramais.php não deverá ser mais utilizado.
- Apesar dos registros serem estaticos, deverá ser criada uma base de dados utilizando mysql ou mariadb para armazenar as informações de cada ramal, como numero, nome, IP,  status do ramal no grupo de callcente (disponivel, pausa, offiline, etc).
- As informações da tela devem ser atualizadas a cada 10 segundos utilizando ajax e estas informações devem ser atualizadas na base de dados. Para verificar se está sendo atualizado na base de dados você poderá alterar as informações dos arquivos  lib\filas e lib\ramais

### IMPORTANTE
0. Você não podera utilizar frameworks, o código terá de ser 100% seu.
1. O arquivo lib\filas simula as informações de um grupo de callcenter  
2. O arquivo lib\ramais simula as informações dos ramais  
3. Estes arquivos se completam  
4. Estes arquivos NÃO devem unidos em um só arquivo  
5. Estes arquivos poderão ser alterados apenas para teste do AJAX  
6. Ao concluir o teste, deverá ser encaminhado um arquivo .zip contendo todo o código, dump da base de dados e instruções de instalação e a lista das melhorias aplicadas.
