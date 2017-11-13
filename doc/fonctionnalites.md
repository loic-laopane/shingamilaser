#Fonctionnalités

##Front
- Formulaire de connexion
- Formulaire d'inscription
- Profil :
    -Informations personnels, 
    - points de fidelité, 
    - ses cartes de fidelité
    - ses scores (otpion)
    - ses visites
    - ses offres
    - associer une carte physique à son compte (par le n°)

## Back
- Gestion des clients
    - Creation de client
    - Mise jour de client
    
- Gestion des cartes
    - Formulaire de Demande de carte son fournisseur
    - Formulaire de recuperation des cartes du fournisseur
    
- Trouver un client d'apres son numero de carte

## Passif
- Generateur de QRCode
- Lecteur de QRCode

## API fournisseur
- Enregistrer une demande
- Générer des cartes
- Listes les cartes liées à un centre et à une demande

==================================
## Entités de l'Application

- User
    - id
    - username (string)
    - password (string)
    - email (string)
    - created_at (datetime)
    - active (bool)
    - roles (array)
    
- Customer
    - id
    - firstname (string)
    - lastname (string)
    - society
    - nickname (string)
    - address (string)
    - zipcode (string 5)
    - city (string)
    - birthdate (date)
    - user (OneToOne)
    - games (ManyToMany Game)
    - offers (ManyToMany Offer)

- Center
    - id
    - code (string 3)
    - name
    - address (string)
    - zipcode (string 5)
    - city (string)
    
- Card
    - id
    - center (ManyToOne)
    - code (string 6, unique)
    - customer (ManyToOne Customer)
    - checksum int (1)
    - activated_at (datetime)
    - active (bool)
    - order (ManyToOne Order)

- Game
    - id
    - created_at (datetime)
    - host (ManyToOne Customer)
    - players (ManyToMany)

- CustomerGame
    - customer (ManyToOne)
    - game (ManyToOne)
    - score (int)
    
- Offer
    - id
    - title (string)
    - description (text)
    - count (int) //nombre de partie debloquant l'offre
    - expired_at (datetime)

- CustomerOffer
    - customer (ManyToOne Customer)
    - offer (ManyToOne Offer)
    - used_at (datetime)

- Order
    - id
    - reference (string)
    - quantity (int)
    - created_at (datetime)
    - updated_at (datetime)
    
## Entités de l'API fournisseur

- Order
    - id
    - reference
    - quantity
    - created_at
    - customer
    
- Card
    - id
    - code (string 6 unique)
    - order (ManyToOne Order)
    
Customer
    - id
    - code (string 3)
    - name (string)