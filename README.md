# Projet Cabinet Médical
Projet développé dans le cadre de mon projet de deuxième année de BTS. Essentiellement Backend.

**⚠️ Le développement de ce projet a été stoppé à la fin de mon BTS. J'en développe actuellement une version améliorée utilisant le framework Symfony 6 dans un autre repository.**

*Chaque fonctionnalité est testée dans le plus grand nombre de scénarios d'utilisation possibles avant d'être portée dans ce dépôt.*

## Description :
Interface sous forme d'applicatif web destinée à la gestion d'un cabinet médical, ainsi qu'à la commande à distance d'une carte programmable Arduino.

## Objectifs principaux :
- [x] Commander à distance (via une carte Arduino) de l'ouverture de la porte d'entrée du cabinet.
    - Déverrouillage depuis l'extérieur grâce à un digicode.
    - Déverrouillage depuis l'extérieur grâce à badge ou carte RFID.
    - Déverrouillage depuis l'intérieur via un bouton sur l'interface web.
    - Déverrouillage et ouverture automatique depuis l'intérieur grâce à un autre bouton sur l'interface web.
- [x] Gestion de la liste des patients.
- [x] Gestion de la liste du personnel.
- [x] Gestion de la liste des salles.
- [x] Gestion de la liste des badges.
- [x] Gestion des plannings des médecins.
- [x] Récupération du flux vidéo des caméras (vidéosurveillance).
- [x] Possibilité pour les médecins de consulter leur planning.

## Matériel Arduino requis :
- 1 carte Arduino Mega 2560
- 1 Ethernet Shield 2
- 1 module lecteur RFID
- 1 module clavier digicode
- 1 module caméra
- 1 module bouton
- 2 modules relais

## Installation :
Sur un système d'exploitation Linux, installez `Docker`, `Docker-compose` et les autres dépendances. :

##### Debian :
```
# apt install docker docker-compose git
```

##### Archlinux :
```
# pacman -S docker docker-compose git
```

##### Fedora :
```
# dnf install docker docker-compose git
```

##### OpenSUSE :
```
# zypper install docker docker-compose git
```
---
Clonez ce depôt avec l'outil Git. :
```
$ git clone https://github.com/FrozenCell239/Projet_Cabinet_Medical.git
```

Puis rendez vous dans le sous-dossier `src`. Entrez ensuite cette commande pour démarrer les conteneurs et installer les modules Node JS. :
```
# docker-compose up -d
```

## Utilisation
- Pour accéder au site web, rendez-vous sur http://localhost:1080/pages/index.php.
- PhpMyAdmin, notre interface Web de notre base de données, est accessible sur http://localhost:1088.

Vous avez la possibilité de changer les ports de connexion (1080 et 1088) à votre convenance en éditant le fichier `docker-compose.yml`.
