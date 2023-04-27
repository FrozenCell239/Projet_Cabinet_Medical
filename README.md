# Projet Cabinet Medical
Projet développé dans le cadre de mon projet de deuxième année de BTS. Essentiellement Backend.

**⚠️ Ce projet est toujours en cours de développement.**

*Chaque fonctionnalité est testée dans le plus grand nombre de scénarios d'utilisation possibles avant d'être portée dans ce dépôt.*

## Description:
Interface sous forme d'applicatif web destinée à la gestion d'un cabinet médical, ainsi qu'à la commande à distance d'une carte programmable Arduino.

## Objectifs :
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
- [ ] Récupération du flux vidéo des caméras (vidéosurveillance).
- [x] Possibilité pour les médecins de consulter leur planning.

## Matériel Arduino requis :
- 1 carte Arduino Mega 2560
- 1 Ethernet Shield 2
- 1 module lecteur RFID
- 1 module clavier digicode
- 1 module caméra
- 1 module bouton
- 2 modules relais