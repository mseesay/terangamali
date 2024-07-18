<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

const APPLICATION_ROOT = __DIR__;
require APPLICATION_ROOT . '/vendor/autoload.php';

$view = $_GET['v'] ?? "index";
// remove trailing '.html.twig' if present
$view = preg_replace('/\.html\.twig$/', '', $view);
$view_path = APPLICATION_ROOT . "/application/view/$view.html.twig";

if(file_exists($view_path)) {

    // Initialize Twig
    $loader = new FilesystemLoader(APPLICATION_ROOT . "/application/view/");
    $twig = new Environment($loader, [
        'cache' => false
    ]);

    // Define custom functions
    $twig->addFunction(new TwigFunction('path', function ($path) {
        return "application/" . $path;
    }));

    $twig->addFunction(new TwigFunction('asset', function ($path) {
        return "asset/" . $path;
    }));


    // Set variables
    $user = new User();
    $twig->addGlobal('user', $user);


    // Render the view
    echo $twig->render($view . ".html.twig", [
        "title" => "Preview",
        "content" => $view_path
    ]);


    die();

}else{

    echo "View not found";

}

class User {
    // Method has_access($role)
    function has_access($role) {
        return true;
    }
}