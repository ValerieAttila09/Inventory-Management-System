<?php
require __DIR__ . '/../vendor/autoload.php';

// Load env
if (file_exists(__DIR__ . '/../vendor/vlucas/phpdotenv/src/Dotenv.php')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

// Basic Twig rendering for home page
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

echo $twig->render('home.twig', [
    'app_name' => $_ENV['APP_NAME'] ?? 'Storage Dashboard'
]);
