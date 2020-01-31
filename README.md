# Portfolio

Projet personnel concernant la réalisation d'un site personnel à l'aide de Symfony 4.<br>
Cela me permet de mettre en pratique les notions vues lors de ma formation (WebForce3, 490h, développeur/intégrateur Web, décembre 2019).<br>
<br>
Le but de ce projet est de présenter les différents travaux réalisés lors de mes précédentes expériences.<br>
<br>
Le site est organisé de la façon suivante :<br>
  => Une page d'accueil, avec une barre de navigation, permet d'avoir accès aux différentes rubriques de cette page (présentation, formation, compétences, expérience et projets professionels, puis contact).<br>
  => Les projets sont organiés par type. Un projet par catégorie sera affiché de façon aléatoire.<br>
  => Une section contact se trouve en bas de la page d'acceuil.<br>
  => La partie back-office se trouve bien évidemment sur d'autres pages avec barre de navigation secondaire.<br>
<br>
Dans la partie back-office, il est possible de créer/modifier/supprimer des projets, catégories, types et utilisateurs.<br>
Il y a également un affichage de différentes informations (nombre d'inscrits total et pour chaque rôle; nombre de projets total et pour chaque type).
<br>
Un objet PROJECT aura les propriétés suivantes (creation/modification/suppression par Admin uniquement) :<br>
  => Nom de projet<br>
  => Type de projet (Print/retouche ou Web)<br>
  => Catégorie en fonction du type de projet<br>
  => Ajout d'une ou plusieurs images<br>
  => Une description courte (attribut html alt et title pour les images)<br>
  => Une description plus longue concernant le projet<br>
  <br>
Il existe deux entités TYPE et CATEGORIE permettant de classer les projets:<br>
  => Type : permet de regrouper des catégories sous un même type (ex: web, retouche image...)<br>
  => Catégorie : permet de définir une catégorie à laquelle on attribuera un seul type <br>
<br>
Enfin, pour gérer le site, une entité USER existe (creation/modification/suppression par Admin uniquement) :<br>
  => Pseudo (unique)<br>
  => Mot de passe<br>
  => Rôle (ROLE_USER par défaut)<br>
  => Date d'enregistrement/inscription<br>
  => Date de dernier login<br>
  => Date de dernier logout<br>
  <br>
  => Seul la modification du rôle et la suppression de l'utilisateur est possible par un Admin<br>
  => Un Admin ne pourra pas supprimer/modifier son propre rôle
<br>
En tant que simple ROLE_USER, il est simplement possible de modifier son pseudo et son mot de passe sur une page privée.<br>
<br><br>

Le site n'est pas encore en ligne !.