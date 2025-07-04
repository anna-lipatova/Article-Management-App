<?php

require_once __DIR__ . '/controllers/ArticlesController.php';

// $requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$routes = [
    'GET' => [
        'articles' => 'showArticles',
        'article' => 'showArticle',
        'article-edit' => 'editArticle',
        'article-snapshot' => 'showSnapshot',
    ],
    'POST' => [
        'reset-articles' => 'resetArticles',
        'article-create' => 'createArticle',
        'article-edit' => 'editArticle',
        'article-snapshot' => 'createSnapshot',
        'article-snapshots-list' => 'showSnapshots',
    ],
    'DELETE' => [
        'article-delete' => 'deleteArticle',
    ]
];

$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING) ?? 'articles';

$route = preg_split('/\//', $page);
$success = false;

if (isset($routes[$requestMethod][$route[0]])) {
    $action = $routes[$requestMethod][$route[0]];
    $controller = new ArticlesController();

    if ($requestMethod === 'GET') {
        $success = $controller->$action($_GET);
    } elseif ($requestMethod === 'POST' || $requestMethod === 'DELETE') {
        $params = json_decode(file_get_contents('php://input'), true);
        $success = $controller->$action($params);
    }
}

if (!$success) {
    http_response_code(404);
}