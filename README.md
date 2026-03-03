# EasyColoc

EasyColoc est une application web de gestion de colocation permettant de suivre les dépenses communes et de calculer automatiquement les soldes entre membres.

L’objectif principal est d’éviter les calculs manuels, réduire les conflits financiers et offrir une vision claire de **“qui doit quoi à qui”**.

---

# Objectifs

## Objectifs fonctionnels

- Gérer des colocations (création, annulation, départ/retrait de membres)
- Suivre les dépenses partagées
- Calculer automatiquement les soldes individuels
- Afficher une vue simplifiée des remboursements

## Objectifs techniques

- Architecture MVC (Laravel)
- Base de données relationnelle (MySQL / PostgreSQL)
- ORM Eloquent (relations hasMany / belongsToMany)
- Authentification Laravel Breeze / Jetstream
- Système de rôles (Admin global, Owner, Member)

---

# Architecture Technique

- Framework : Laravel
- Architecture : MVC Monolithique
- ORM : Eloquent
- Base de données : MySQL
- Frontend : Blade + Tailwind CSS
- Authentification : Laravel Breeze / Jetstream
- Versionning : Git / GitHub

---

# Acteurs & Rôles

## Member
- Rejoint une colocation
- Ajoute des dépenses
- Consulte son solde
- Marque un paiement
- Peut quitter la colocation (sauf owner)

## Owner
- Crée la colocation
- Invite des membres
- Retire un membre
- Gère les catégories
- Peut annuler la colocation

## Global Admin
- Accède aux statistiques globales
- Bannit / débannit des utilisateurs
- Peut également être Owner ou Member

---

# Fonctionnalités

## Utilisateurs
- Inscription / Connexion
- Gestion du profil
- Premier inscrit promu Admin global automatiquement
- Blocage automatique des utilisateurs bannis

## Colocations
- Création automatique avec Owner
- Invitation par email/token
- Une seule colocation active par utilisateur
- Départ d’un membre (gestion `left_at`)
- Annulation d’une colocation

## Dépenses
- Ajout (titre, montant, date, catégorie, payeur)
- Suppression
- Historique des dépenses
- Statistiques par catégorie
- Filtrage par mois

## Balances & Dettes
- Calcul automatique :
  - Total payé
  - Part individuelle
  - Solde
- Vue synthétique “Qui doit à qui”
- Réduction des dettes via paiements

## Paiements simples
- Action “Marquer payé”

## Réputation
- Départ avec dette → -1
- Départ sans dette → +1
- Si un Owner retire un membre avec dette → dette imputée à l’Owner

## Administration
- Dashboard global
- Statistiques utilisateurs / colocations / dépenses
- Bannissement / Débannissement

---

# Scénarios d’implémentation

## Invitation
- Génération d’un token unique
- Envoi email
- Vérification email/token
- Blocage si colocation active existante
- Ajout comme Member

## Dépense commune
- Ajout d’une dépense
- Recalcul automatique des soldes
- Affichage synthétique des remboursements

## Départ avec dette
- Ajustement réputation
- Redistribution interne des dettes
- Cas spécifique : dette imputée à l’Owner si retrait forcé

## Blocage multi-colocation
- Impossible d’avoir plus d’une colocation active
- Blocage création / acceptation d’invitation

---

# Périmètre

## Inclus
- Authentification
- Gestion colocations
- Invitations
- Dépenses & catégories
- Calcul des balances
- Paiements simples
- Réputation
- Dashboard Admin
- Filtre mensuel

## Hors périmètre (Bonus)
- Paiement Stripe
- Notifications temps réel
- Calendrier
- Export de données

---

# Sécurité

- Protection CSRF (@csrf)
- Protection XSS via Blade `{{ }}`
- Validation côté serveur
- Validation HTML5 côté client
- Gestion des rôles et autorisations
- Utilisation de requêtes préparées via Eloquent
- Clés étrangères & contraintes

---

# UML

- Diagramme de cas d’utilisation
- Diagramme de classes

---

# Installation
