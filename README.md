# DWARF : Draw With Amazing Random Friends

## Equipe 7 - Membres :
- REMY Emeryck
- INZOUDDINE Ayache
- JHISTARRY Thomas
- SIGNORET Tanguy
- MARTIN Joris
- PAPPALARDO Mattéo

## DWARF :
L'application est dissponible sur [dwarf.jorismartin.fr](https://dwarf.jorismartin.fr).

Sur ce site web, vous trouverez la dernière version compilé et tester de notre projet.
Le fonctionnement de l'application est détaillé sur [l'aide](https://dwarf.jorismartin.fr/help).

## VPS :
### Accès :
> Pour accéder au VPS qui héberge notre application, il vous faut vous connecter à **jorismartin.fr** avec comme utilisateur **dwarf**.
> Le mot de passe vous a été donné lors de l'entretien de la troisième ittération.

### Base donnée :
> L'ensemble des données (excepté les images) sont stoqués dans notre base de données.

### Systeme de fichier :
> Notre système de fichiers est organisé comme suit :
```console
/var/www/dwarf/
├── api
|   ├── API de dwarf
|   └──Ensemble des controleurs-classes utilisé par l'api
├── cdn
|   ├── frames
|   |   └── Ensemble des images des pageset cases déssinés
|   ├── templates
|   |   └── Ensemble des templates implémentés
├── icons
|   └── Ensemble des icones utilisés par l'application
└── …
```

## Organisation du git
Notre git est organisé comme suit :
```console
rendus
├── api/
|   ├── Ensemble des controleurs-classes utilisé par notre api
├── cdn/templates/
|   ├── Ensemble des templates utilisés (fichiers json)
├── docs/
|   ├── Documents des différentes ittérations
│   └── README.md
├── dwarf-browser/
|   ├── Ensemble des composants utilisé par React pour la front-end
├── .gitattributes
├── .gitignore
├── .gitlab
├──  …
└── README.md
```