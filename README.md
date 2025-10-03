# EXAM MANAGER

## Description

EXAM MANAGER est une application de gestion des examens destinée pour les enseignants.

## Technologies Utilisées

- **Frontend :** Angular 19.2.17, TypeScript, TailwindCSS pour le style moderne et responsive  
- **Backend :** Symfony 7, API Platform 4, Doctrine pour TypeORM Base de données
- **Base de données :** PostgreSQL 14  
- **API & Authentification :** JWT (JSON Web Token) pour sécuriser les endpoints  
- **Conteneurisation :** Docker et Docker Compose pour un environnement isolé et reproductible  

## Architecture

### 2. Bonnes pratiques de développement

- **SOLID Principles** : code maintenable et évolutif  
- **Clean Code** : lisible, compréhensible et testable  
- **Modularité** : chaque composant et service Angular est indépendant et réutilisable  
- **Signal + RxJS** pour gestion moderne et réactive des états  

### 3. Sécurité

- **JWT Authentication** pour sécuriser les endpoints, avec gestion de l'access_token et du refresh_token  
- Les mots de passe utilisateurs sont **hachés** et ne sont jamais stockés en clair


### 5. Conteneurisation et déploiement

- Frontend Angular servi par **Nginx**  
- Backend Symfony API Platform  
- Base de données PostgreSQL  

---

## Installation et Lancement

### Prérequis

- Docker & Docker Compose installés
- Git

### Étapes

1. Cloner le projet :

```bash
git clone https://github.com/Jedidiaamy-2427/gestion_examen.git

cd gestion_examen
```

2. Lancer les containers Docker :

```bash
docker compose up --build
```

3. Accéder à l’application :

- Frontend Angular: <http://localhost:4200>
- Backend Symfony: <http://localhost:8000/api/...>
- PostgreSQL sera créé automatiquement avec les tables nécessaires et des fixtures de données étudiants

## API Documentation

La documentation de l'API est générée automatiquement et disponible via API Platform basé par swagger. Pour y accéder, démarrez l'application et ouvrez votre navigateur :

```bash
http://localhost:8000/api/docs
```

