# Rest-Api-project

Projet d'API REST réalisé dans le cadre du BUT Informatique 2  
Le but etant de réaliser un blog accessible via une API.  
Il est nécessaire de se connecter pour réaliser certaines actions comme ajouter un post ou y le liker.  
Chaque utilisateur a un rôle (moderator ou publisher) définissant ses accès.

🔒 L'authentification est garantie par un jeton JWT

## Structure
### code
Contient le code des services API

### libs
Contient les bibliothèques et sous programmes utilisés dans code (ex : deliver_response )

### samples
Contient une base de données pré remplies d'utilisateurs, de posts et de réaction ansi qu'une collection de requète pour tester l'api

