# FormApp
FormApp

Návod ke spuštění projektu: 

1) nejprve stáhnete soubor docker-compose.yml ten dáte samotné do složky, do stejné složky vložíte složky nginx a php
2) otevřete terminál a spustíte command: docker-compose up -d --build
3) v adresáři přibude složka app, do ktéré naklonujete repozitář: git clone https://github.com/kucky21/FormProject.git
4) v adresáři app bude podadresář FormProject, ze kterého odstraníme složky nginx a php a také soubor docker-compose.yml
5) v souboru .env přidejte svou DATABASE_URL například: DATABASE_URL="mysql://root:secret@database:3306/symfony_docker?serverVersion=8.0" (root je username, secret je heslo a symfony_docker název databáze)
6) v adresáři FormProject spustíte příkaz composer install
7) ve stejném adresáři poté spustíte: docker-compose exec php /bin/bash
8) v něm poté php bin/console doctrine:database:create pokud databáze již existuje je to v pořádku
9) dále: php bin/console doctrine:schema:update --force
10) a naposledy: php bin/console doctrine:fixtures:load
11) nyní po zadání adresy do prohlížeče localhost:8080 nebo localhost:80 se aplikace spustí

Něco o projektu: 
Projekt jako takový složitý není, zabral mi asi něco málo přes dvě hodiny.
Problém mi na druhou stranu dělal docker a zprovoznění projektu, to mi zabralo o dost více času.

Hodně jsem používal make (konkrétně: make:auth, make:form, make:user, make:controller)
