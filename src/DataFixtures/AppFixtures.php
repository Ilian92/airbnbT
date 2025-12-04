<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Discussion;
use App\Entity\Genre;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // ==================== USERS ====================
        $users = [];

        // Créer un utilisateur admin
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'motdepasse')
        );
        $manager->persist($admin);
        $users[] = $admin;

        // Créer quelques utilisateurs normaux
        for ($i = 1; $i <= 5; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@example.com");
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'password123')
            );
            $manager->persist($user);
            $users[] = $user;
        }

        // Créer un utilisateur banni
        $banned = new User();
        $banned->setEmail('banned@example.com');
        $banned->setRoles(['ROLE_BANNED']);
        $banned->setPassword(
            $this->passwordHasher->hashPassword($banned, 'mdp')
        );
        $manager->persist($banned);

        // ==================== GENRES ====================
        $genres = [];
        $genresData = [
            ['name' => 'Science-Fiction', 'description' => 'Livres de science-fiction et futurisme'],
            ['name' => 'Fantasy', 'description' => 'Mondes imaginaires et magie'],
            ['name' => 'Roman', 'description' => 'Romans littéraires et contemporains'],
            ['name' => 'Thriller', 'description' => 'Suspense et action'],
            ['name' => 'Policier', 'description' => 'Enquêtes et mystères'],
            ['name' => 'Romance', 'description' => 'Histoires d\'amour'],
            ['name' => 'Horreur', 'description' => 'Histoires effrayantes'],
            ['name' => 'Biographie', 'description' => 'Vies et histoires vraies'],
        ];

        foreach ($genresData as $genreData) {
            $genre = new Genre();
            $genre->setName($genreData['name']);
            $genre->setDescription($genreData['description']);
            $manager->persist($genre);
            $genres[] = $genre;
        }

        // ==================== AUTHORS ====================
        $authors = [];
        $authorsData = [
            ['name' => 'Isaac', 'lastName' => 'Asimov', 'age' => 72, 'biography' => 'Écrivain américain d\'origine russe, célèbre pour ses œuvres de science-fiction'],
            ['name' => 'J.K.', 'lastName' => 'Rowling', 'age' => 58, 'biography' => 'Auteure britannique, créatrice de l\'univers Harry Potter'],
            ['name' => 'Stephen', 'lastName' => 'King', 'age' => 76, 'biography' => 'Maître de l\'horreur et du suspense américain'],
            ['name' => 'Agatha', 'lastName' => 'Christie', 'age' => 85, 'biography' => 'Reine du crime britannique'],
            ['name' => 'Victor', 'lastName' => 'Hugo', 'age' => 83, 'biography' => 'Écrivain, poète et dramaturge français du XIXe siècle'],
            ['name' => 'George', 'lastName' => 'Orwell', 'age' => 46, 'biography' => 'Écrivain et journaliste britannique'],
            ['name' => 'Jules', 'lastName' => 'Verne', 'age' => 77, 'biography' => 'Écrivain français, pionnier de la science-fiction'],
            ['name' => 'Tolkien', 'lastName' => 'J.R.R.', 'age' => 81, 'biography' => 'Écrivain britannique, créateur de la Terre du Milieu'],
        ];

        foreach ($authorsData as $authorData) {
            $author = new Author();
            $author->setName($authorData['name']);
            $author->setLastName($authorData['lastName']);
            $author->setAge($authorData['age']);
            $author->setBiography($authorData['biography']);
            $manager->persist($author);
            $authors[] = $author;
        }

        // ==================== BOOKS ====================
        $books = [];
        $booksData = [
            ['name' => 'Fondation', 'price' => 1599, 'author' => 0, 'genres' => [0]],
            ['name' => 'Les Robots', 'price' => 1399, 'author' => 0, 'genres' => [0]],
            ['name' => 'Harry Potter à l\'école des sorciers', 'price' => 1899, 'author' => 1, 'genres' => [1]],
            ['name' => 'Harry Potter et la Chambre des secrets', 'price' => 1999, 'author' => 1, 'genres' => [1]],
            ['name' => 'Ça', 'price' => 2299, 'author' => 2, 'genres' => [6]],
            ['name' => 'Shining', 'price' => 1899, 'author' => 2, 'genres' => [6, 3]],
            ['name' => 'Le Meurtre de Roger Ackroyd', 'price' => 1499, 'author' => 3, 'genres' => [4]],
            ['name' => 'Dix Petits Nègres', 'price' => 1599, 'author' => 3, 'genres' => [4]],
            ['name' => 'Les Misérables', 'price' => 2499, 'author' => 4, 'genres' => [2]],
            ['name' => 'Notre-Dame de Paris', 'price' => 1999, 'author' => 4, 'genres' => [2]],
            ['name' => '1984', 'price' => 1699, 'author' => 5, 'genres' => [0, 3]],
            ['name' => 'La Ferme des animaux', 'price' => 1299, 'author' => 5, 'genres' => [2]],
            ['name' => 'Vingt mille lieues sous les mers', 'price' => 1799, 'author' => 6, 'genres' => [0, 2]],
            ['name' => 'Le Tour du monde en 80 jours', 'price' => 1499, 'author' => 6, 'genres' => [2]],
            ['name' => 'Le Seigneur des Anneaux', 'price' => 2999, 'author' => 7, 'genres' => [1]],
            ['name' => 'Le Hobbit', 'price' => 1899, 'author' => 7, 'genres' => [1]],
        ];

        foreach ($booksData as $bookData) {
            $book = new Book();
            $book->setName($bookData['name']);
            $book->setPrice($bookData['price']);
            $book->setAuthor($authors[$bookData['author']]);

            foreach ($bookData['genres'] as $genreIndex) {
                $book->addGenre($genres[$genreIndex]);
            }

            $manager->persist($book);
            $books[] = $book;
        }

        // ==================== DISCUSSIONS ====================
        $discussionTitles = [
            'Analyse du personnage principal',
            'Théories sur la fin',
            'Meilleurs passages',
            'Comparaison avec le film',
            'Questions sans réponses',
            'Mon avis personnel',
            'Recommandations similaires',
        ];

        $discussionContents = [
            'Je trouve que ce livre est absolument fascinant...',
            'Quelle scène préférez-vous dans ce livre ?',
            'J\'ai adoré la façon dont l\'auteur développe l\'intrigue.',
            'Quelqu\'un peut m\'expliquer ce passage ?',
            'Je recommande vivement cette lecture !',
            'L\'univers créé par l\'auteur est incroyable.',
            'Ce livre m\'a marqué à vie.',
        ];

        // Créer 20 discussions
        for ($i = 0; $i < 20; $i++) {
            $discussion = new Discussion();
            $discussion->setTitle($discussionTitles[array_rand($discussionTitles)]);
            $discussion->setContent($discussionContents[array_rand($discussionContents)]);
            $discussion->setBook($books[array_rand($books)]);
            $discussion->setOwner($users[array_rand($users)]);

            $manager->persist($discussion);
        }

        $manager->flush();
    }
}
