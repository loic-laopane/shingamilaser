# Projet 2 : Gestion de cartes de délité

## Sujet n°1

### 1. Objectif

- Réaliser un projet de qualité, testé et documenté à présenter sur une soutenance. 
- Proposer une couverture unitaire et fonctionnelle à la réalisation. 
- Créer un diagrammme UML afin de visualiser les modèles de votre application.

### 2. Contexte

"Shinigami Laser" est un laser game assez connu en Bourgogne. Malheureusement, le suivi des joueurs est aujourd'hui assez chaotique avec des ches papier sur lesquelles sont écrits le nom, le surnom, l'adresse, le numéro de téléphone et la date de naissance d'un joueur. Ces ches sont devenues totalement obsolètes, aussi nous aimerions nous doter d'un système de cartes de délité, cartes physiques ou numériques (avec une application sur smartphone comme interface).

Pour ce faire, nous avons contacté un fournisseur de cartes qui pourrait nous imprimer des cartes avec une puce NFC/RFID, un QR code optionnel, et bien sûr des informations précieuses sur notre club.

La carte elle-même aura un numéro. Celui-ci servira pour nos références internes, mais aussi à nos clients qui pourront les rattacher à leur compte en ligne pour suivre différentes informations : leurs visites, leurs scores, leurs offres, etc.

###3. Réalisation

- Proposer cette gestion de carte de délité, sachant que :
    - Une carte physique possèdera un motif : 
        - CODE_CENTRE CODE_CARTE CHECKSUM 
            - CODE_CENTRE : 3 chiffres décrivant un établissement 
            - CODE_CARTE : 6 chiffres décrivant un client 
            - CHECKSUM : somme des chiffres précédents modulo 9 
        - Exemple : 123 142121 8 
        
- Réaliser une plate-forme web permettant les interactions suivantes : 
    - Un client pourra ouvrir son compte ; 
    - Un client pourra rattacher une carte de délité délivrée dans nos locaux ; 
    - Un client pourra pourra afficher des informations sur ses cartes de délité ; 
    - Un membre du staff pourra trouver un client d'après un numéro de carte.
        
Note :

Nous ne souhaitons pas dans l'immédiat gérer les informations relatives aux services associés à ces cartes (affichage des visites, scores ou autres) bien que cela soit évidemment un "plus" 