# Scenario

- Un centre peut demander un lot de cartes de fidélité

**Sans API :**

- Le centre recupere les cartes provenant du fournisseur pour peupler la base de Card
    
**Avec API :**

- Le centre appelle une API fournisseur via un formulaire avec son `code_centre` et le nombre de cartes
- l'API recoit la demande et génère des cartes et retourne une reference de commande
- Le centre appelle l'API avec son code_centre et la reference commande
- L'API retourne la liste des cartes
- Le centre enregistre les cartes reçues si elle ne sont pas en base
	
- Les cartes sont en base immédiatement, non actives
- Un centre reçoit des cartes de fidélité physique avec:
    - un barre composé de 
        - code centre
        - code carte (unique pour ce centre)
        - un checksum
        
- Un `client` qui réserve une `partie` de laser game, peut avoir une `carte` de fidelité
- Le `client` doit créer un `compte` obligatoirement pour avoir une `carte`
    - L'inscription crée un `compte client` et un `compte utilisateur`
- Une `carte` doit être rattachée au `compte client` pour être créditée
    - la carte est ainsi activée
- Un `client` peut avoir plusieurs cartes, mais pas 2 carte actives en même temps
- Une `carte` perdue doit êtres désactivé
- Le gérant peut créé une `partie`
- Chaque `partie` est enregistrée sur le compte `client`
- Un `client` peut avoir `plusieurs parties` jouées sur son compte
- Une `partie` peut etre jouée par plusieurs clients (2..n)
- Au bout de `"count" parties`, une `offre` peut etre ajoutée au compte client
- Un `client` peut avoir plusieurs offre
- Une `offre` peut etre sur plusieurs clients attachés
- Une `offre` liée à un `client` peut être utilisée
- Une `offre` utilisée ne peut plus être utilisée

