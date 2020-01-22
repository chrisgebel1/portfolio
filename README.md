# Portfolio

Projet personnel pour la réalisation de mon site personnel sous Symfony4.
Cela me permet de mettre en pratique les notions vues lors de ma formation (WebForce3, 490h, développeur/intégrateur Web, décembre 2019)

Le but de ce site est de présenter les différents travaux réalisés lors de mes précédentes expériences.

Il est possible de rajouter et modifier des travaux (projets) en leur attribuant des propriétés :
  => nom de projet
  => type de projet (Print/retouche ou Web)
  => catégorie en fonction du type de projet
  => ajout d'une image
  => une description courte (attribut html alt et title)
  => une description plus longue décrivant le projet
  
En plus de cette première entité PROJET, il existe deux entités TYPE et CATEGORIE :
  => type : nom du type
  => catégorie : nom de la catégorie, nom du type de catégorie

Enfin, pour gérer le site une entité USER existe :
  => nom
  => mot de passe
  => role (ROLE_USER par défaut)

Un espace back-office existe afin d'avoir accès à la partie administration et création des projets grâce au ROLE_ADMIN.
En tant que simple ROLE_USER, il est simplement possible de modifier son nom et son mot de passe.

A la date du 20 janvier 2020, le site n'est pas encore en ligne.
