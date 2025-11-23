   <?php
// Simple PHP router
$request = $_SERVER['REQUEST_URI'];


// Remove query string and get clean path
$path = parse_url($request, PHP_URL_PATH);


// Ensure path starts with /
if ($path === '') {
    $path = '/';
}

// Route the request
switch($path){
    case "/":
    case "/home":
        require "home.html";
        break;

    case "/about":
        require "about.html";
        break;

    case "/services":
        require "services.html";
        break;
        
    case "/services":
        require "services.html";
        break;

    case "/contact":
        require "contact.html";
        break;

    default:
        http_response_code(404);
        require "404.html";
        exit;
}
?>