# ENT Groupe AB3

## Pour installer le site en local:

Installer le site en local :

- Installez XAMPP avec au minimum les modules Apache et MySQL.

- Ouvrez votre XAMPP et activer les serveurs Apache et MySQL.

- Ouvrir le dossier `htdocs`. (Dans XAMPP qui se trouve à la racine de votre disque).

- Déposer dans celui ci le dossier `ent-ab3` qui contient le code du site ainsi que le fichier de Base de Données.


## Pour installer la Base de Données en Local :

- Dans la barre d'URL de votre navigateur, tapez : [localhost/phpMyAdmin](localhost/phpMyAdmin).

- Créez une nouvelle base de donnée en cliquant sur `Nouvelle base de données`, en haut de la liste de vos bases de données, à gauche de la page.

- Cliquez sur la base de donnée que vous venez de créer.

- Allez dans l'onglet `Importer`.

- Sélectionnez le fichier `database.sql` qui se trouve dans le dossier `ent-ab3`.

- Cliquez sur `Importer`, en bas de la page.

- Ouvrez le fichier `database-sample.php`, qui se trouve dans le dossier `script` du site, avec un éditeur de texte quelquonque.

- Modifiez les lignes 3 à 6 avec le serveur de base de donnée (normalement localhost en local), le nom d'utilisateur phpMyAdmin, le mot de passe de l'utilisateur, et enfin le nom de la base de donnée que vous venez de créer.

- Renommez le fichier `database-sample.php` en `database.php`.


## Aller sur le site :

- Allez à l'adresse [localhost/ent-ab3/index.php/](localhost/ent-ab3/index.php/) dans votre navigateur pour arriver sur le site.

- La page d'accueil de connexion au site s'affichera.
