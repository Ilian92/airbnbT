# TP Symfony — Bibliothèque

Projet Symfony sur le thème **Bibliothèque** (Livres, Genres, Auteurs, Discussions) avec authentification + rôles **ADMIN/USER/BANNED**, CRUD et gestion des utilisateurs bannis.

## Installation / Lancement

1. Installer les dépendances

    - `composer install`

2. Configurer la base de données (selon votre environnement)

    - Mettre à jour `DATABASE_URL` dans `.env` ou `.env.local`

3. Créer la base + exécuter les migrations

    - `php bin/console doctrine:database:create --if-not-exists`
    - `php bin/console doctrine:migrations:migrate --no-interaction`

4. Charger les fixtures

    - `php bin/console doctrine:fixtures:load --no-interaction`

5. Démarrer le serveur
    - `symfony serve` (si Symfony CLI)
    - ou `php -S 127.0.0.1:8000 -t public`

## Fixtures

-   Fichier principal des fixtures: [src/DataFixtures/AppFixtures.php](src/DataFixtures/AppFixtures.php)
-   Ce fichier crée:
    -   Utilisateurs (admin, users, banni)
    -   Genres
    -   Auteurs
    -   Livres (liés à auteurs + genres)
    -   Discussions (liées à livres + owners)

## Comptes de test

Après chargement des fixtures (commande `doctrine:fixtures:load`):

-   **Admin**

    -   Email: `admin@example.com`
    -   Mot de passe: `motdepasse`
    -   Rôle: `ROLE_ADMIN`

-   **Utilisateur normal**

    -   Email: `user1@example.com` (ou `user2@example.com` … `user5@example.com`)
    -   Mot de passe: `password123`
    -   Rôle: `ROLE_USER`

-   **Utilisateur banni**
    -   Email: `banned@example.com`
    -   Mot de passe: `mdp`
    -   Rôle: `ROLE_BANNED`

## Pages / URLs utiles

-   Accueil: `/` (route `app_home`)
-   Connexion: `/login` (route `app_login`)
-   Déconnexion: `/logout` (route `app_logout`)
-   Profil: `/profile` (route `app_profile`)
-   Page banni: `/banned` (route `app_banned`)
-   Recherche (bonus): `/search` (route `app_search`)

## CRUD (Admin)

Les CRUD sont accessibles via les routes `/admin/*`:

-   Livres: `/admin/book` (routes `app_book_*`)

    -   Controller: [src/Controller/BookController.php](src/Controller/BookController.php)
    -   Templates: [templates/book](templates/book)

-   Auteurs: `/admin/author` (routes `app_author_*`)

    -   Controller: [src/Controller/AuthorController.php](src/Controller/AuthorController.php)
    -   Templates: [templates/author](templates/author)

-   Genres: `/admin/genre` (routes `app_genre_*`)

    -   Controller: [src/Controller/GenreController.php](src/Controller/GenreController.php)
    -   Templates: [templates/genre](templates/genre)

-   Discussions: `/admin/discussion` (routes `app_discussion_*`)

    -   Controller: [src/Controller/DiscussionController.php](src/Controller/DiscussionController.php)
    -   Templates: [templates/discussion](templates/discussion)

-   Utilisateurs: `/admin/user` (routes `app_user_*`)
    -   Controller: [src/Controller/UserController.php](src/Controller/UserController.php)
    -   Templates: [templates/user](templates/user)

## Utilisateurs bannis (blocage global)

-   Listener/Subscriber: [src/EventSubscriber/BannedUserSubscriber.php](src/EventSubscriber/BannedUserSubscriber.php)
-   Comportement:
    -   Si l’utilisateur connecté a `ROLE_BANNED`, il est redirigé vers `/banned`.
    -   Exceptions autorisées: `/banned`, `/logout`, et les routes techniques `/_*`.

## Authentification / Sécurité

-   Configuration sécurité: [config/packages/security.yaml](config/packages/security.yaml)
-   Login controller: [src/Controller/SecurityController.php](src/Controller/SecurityController.php)
