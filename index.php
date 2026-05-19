<?php
// Ce fichier est le point d'entrée UNIQUE de l'application
// Toutes les URLs passent par ici : index.php?page=salles&action=create

session_start();

// Charger tous les contrôleurs
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/SalleController.php';
require_once '../app/controllers/ReservationController.php';

// Lire les paramètres de l'URL
$page   = $_GET['page']   ?? 'login';    // Par défaut : page de connexion
$action = $_GET['action'] ?? 'index';    // Par défaut : lister

// Vérifier si l'utilisateur est connecté (sauf pour login/register)
$public_pages = ['login', 'register'];
if (!in_array($page, $public_pages) && !isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

// Routage : selon $page et $action, on appelle le bon contrôleur
switch ($page) {

    case 'login':
        $ctrl = new AuthController();
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $ctrl->login() : $ctrl->loginForm();
        break;

    case 'register':
        $ctrl = new AuthController();
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $ctrl->register() : $ctrl->registerForm();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    case 'salles':
        $ctrl = new SalleController();
        if ($action === 'create') {
            $_SERVER['REQUEST_METHOD'] === 'POST' ? $ctrl->create() : $ctrl->createForm();
        } else {
            $ctrl->index();
        }
        break;

    case 'reservations':
        $ctrl = new ReservationController();
        if ($action === 'create') {
            $_SERVER['REQUEST_METHOD'] === 'POST' ? $ctrl->create() : $ctrl->createForm();
        } elseif ($action === 'delete') {
            $ctrl->delete();
        } else {
            $ctrl->index();
        }
        break;

    default:
        echo "Page introuvable.";
}
?>