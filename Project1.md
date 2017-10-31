Projet n°1 : Gestion de commandes
=============================

## 1. Durée
Le projet est à réaliser sur 3 jours.

La date de rendu est fixée au dimanche 5 novembre 2017.

## 2. Objectifs

- Valider ses acquis sur Symfony
- Savoir créer un site web simple
- Savoir créer une interface sécurisée d'administration
- Savoir se documenter par soi-même à travers la documentation officielle de Symfony
- Savoir rester synthétique dans son travail

## 3. Réalisation

Le projet est à versionner sur GitLab.

Ce projet sera à réaliser seul. Bien sûr, un coup de main d'un collègue est toujours le bienvenu !   ;)

> Notez également que nous demandons par ce projet un travail à hauteur de votre expérience et de votre connaissance sur Symfony. Il ne s'agit pas d'un travail scolaire, mais d'une validation de ses acquis. Allez-y donc par étape, nous n'exigerons jamais ici une couverture du sujet à 100%, mais le sujet décrit ci-dessous couvre un contexte assez complet pour la compréhension de la problématique.

## 4. Evaluation

L'évaluation sera réalisée individuellement en classe avec une présentation des réalisations :

* graphique ;
* technique.

L'évaluation portera sur les fonctionnalités, certes, mais aussi sur la clarté et la qualité du code de manière générale.

L'évaluation des projets sera réalisée le lundi 6 novembre 2017 ; le lendemain permettra une correction en classe.

## 5. Sujet

### Contexte

Notre entreprise « SutekinaBox » a comme activité l'envoi de colis trop mignons à nos clients. Dans nos box envoyées tous les mois, nous essayons d'envoyer toutes sortes de produits.

Pour cela, nous avons besoin de valider un ensemble d'étapes avant que nos « box » soient disponibles.

A travers une interface d'administration, chaque mois, notre service marketing pourra sélectionner le contenu d'une box avec une enveloppe budgétaire fixe. Afin d'alimenter les box de nos clients, nous aurons la possibilité de demander à un fournisseur de nous envoyer une liste de produits. Ces produits seront réceptionnés par un responsable des achats et celui-ci s'occupera de valider leur conformité.
Une fois tous nos produits reçus et conformes, un « go » sera donné pour signifier que ces produits sont prêts à être conditionner avant envoi aux clients. Inversement, si nos fournisseurs ne peuvent pas nous délivrer les produits souhaités, il nous faudra revalider le contenu des box.

A chaque étape validée, il serait cool que le prochain service concerné reçoive un email histoire de valider la prochaine étape du processus. Dans l'absolu, une petite notification sur notre interface d'administration (pas forcément en temps réel pour autant) serait un plus.

### Application à réaliser

1. Réaliser l'interface web permettant le suivi de ce processus (**requis**)
	- Essayer d'intégrer le composant Workflow de Symfony (**recommandé**)
2. Essayer de gérer les notifications de tâches en attente :
	- par envoi d'emails ; (**bonus**)
	- par une icone simple de notification. (**bonus**)


