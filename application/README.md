# Shiningami Laser

## 1. Installation

```sh
git clone https://gitlab.com/lowkick/lasergame.git
```

## 2. Deploiement

### 2.1 Avec Docker

#### Télécharger et installer docker

- [Docker for windows](https://docs.docker.com/docker-for-windows/install/#download-docker-for-windows) (Windows 10 Pro)

- [Docker Toolbox](https://docs.docker.com/toolbox/toolbox_install_windows/) (Windows 7, 8, 10)


#### Image Docker
Télécharger l'image [edyan/full-lamp](https://github.com/edyan/docker-full-lamp)
```sh
docker pull edyan/full-lamp
```

#### Créer un conteneur Docker
Sur Window 10 Pro :
```sh
docker run -d -p 80:80 -p 3306:3306 -v "path/to/your/local_app:/var/www/html" -v "path/to/your/local_db:/var/lib/mysql" -w "/var/www/hml" edyan/full-lamp
```

Sur Windows < 10 Pro :
- S'assurer que la virtualisation est bien activée sur votre machine (voir le BIOS)
- Lancer `VirtualBox` installé par défaut avec `Docker ToolBox`
- Ajouter le répertoire racine de votre serveur local comme dossier partagé et donner lui un alias (ex : VM_Docker)
- Lancer la commande :
```sh
docker run -d -p 80:80 -p 3306:3306 -v /VM_Docker/local_app:/var/www/html -v "/VM_Docker/local_db:/var/lib/mysql -w /var/www/hml edyan/full-lamp
```

**En utilisant docker sur Windows inférieur au 10 Pro, l'IP du serveur est accessible sur : [`http://192.168.99.100`](http://192.168.99.100)**

### 2.2 Sans Docker
Vous pouvez utiliser des serveurs tels que [Wampserver](http://www.wampserver.com/en/download-wampserver-64bits/) ou [Xampp](https://www.apachefriends.org/fr/index.html), et suivre la documentation d'installation


## Configuration

**Accès dev**
- Sous `Docker` modifier le fichier `/web/app_dev.php` et ajouter l'IP de la machine Docker : `192.168.99.1`

**Serveur mail**
- Modifier parameters.yml en y mettant votre l'IP local de votre PC sur le réseau local : `mailer_host: 192.168.1.xx`

**Bases de données (par défaut sur le driver `sqlite`)**
- Créér la base de données principale : 
```sh
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```
- Créér la base de données de l'API : 
```sh
php bin/console doctrine:database:create --connection=api
php bin/console doctrine:schema:update --force --em=api
```

## Utilisation de Maildev

Installer maildev sous docker : 
```sh
docker pull djfarrelly/maildev
```
Puis créer un conteneur
```sh
docker run -d -p 25:25 -p:1080:80 djfarrelly/maildev
```

Sur votre nagigateur, accéder à la mailbox via http://ip_server:1080


## 3. Prise en main

### Création d'un compte `admin`

L'application **Shiningami Laser** ne contient par défaut aucun utilisateur.

Pour créer un utilisateur `admin`, ouvrir la console et taper :
```sh
php bin/console app:create:admin
```
Puis renseigner les champs demandé :
- un identifiant, par défaut : admin

```sh
Username [admin] :
```

- Un email valide

```sh
Email :
```

Si tous les pré-requis sont validés, le compte administrateur est créé et un récapitulatif des informations est affiché en console. 