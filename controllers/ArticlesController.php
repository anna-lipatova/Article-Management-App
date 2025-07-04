<?php

require_once __DIR__ . '/../models/Article.php';

class ArticlesController
{
    private $paths = [
        'index' => __DIR__ . '/../views/articles/index.php',
        'detail' => __DIR__ . '/../views/articles/detail.php',
        'edit' => __DIR__ . '/../views/articles/edit.php',
    ];

    private function render($path, $data = [])
    {
        $stylesheetPath = dirname($_SERVER['SCRIPT_NAME']) . '/css/styles.css';
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']) . '/js/scripts.js';

        extract($data);

        try {
            require __DIR__ . '/../views/layouts/header.php';
            require $path;
            require __DIR__ . '/../views/layouts/footer.php';
        } catch (Exception $e) {
            return false;
        }

        return true;
    }


    // F14) User can view the article detail page by 
    //navigating to URL ./article/{id}, where {id} is 
    //a valid article identifier.
    private function fetchArticleFromUrl($params)
    {
        $rawData = preg_split('/\//', $params['page']);
        if (count($rawData) !== 2) {
            http_response_code(400);
            return false;
        }

        $id = (int)$rawData[1];
        $article = Article::getArticle($id);


        // F15) If an article with given {id} does not 
        //exist, then the server returns an empty document 
        //with status code 404.
        // F20)
        if (!$article) {
            http_response_code(404);
            return null;
        }

        return $article;
    }

    /// !!!
    private function fetchSnapshotFromUrl($params)
    {
        $rawData = preg_split('/\//', $params['page']);
        if (count($rawData) !== 2) {
            http_response_code(400);
            return false;
        }

        $id = (int)$rawData[1];
        $snapshot = Article::getSnapshot($id); ///

        if (!$snapshot) {
            http_response_code(404);
            return null;
        }

        return $snapshot;
    }



    public function showArticles($params)
    {
        $articles = Article::getArticles($params);

        return $this->render($this->paths['index'], ['articles' => $articles]);
    }



    public function showArticle($params)
    {
        $article = $this->fetchArticleFromUrl($params);

        if ($article) {
            return $this->render($this->paths['detail'], ['article' => $article, 'title' => $article['name'], 'edit' => true]);
        }

        return false;
    }


    // F13) If a user clicks the Create button, a new article 
    //is created. The article is created with given name and 
    //empty body. Next, the user is redirected to the article 
    //edit for the new article.
    public function createArticle($params)
    {
        if (!isset($_POST['name'])) {
            http_response_code(400);
            return false;
        }

        $name = trim($_POST['name']);

        if (empty($name) || strlen($name) > 32) {
            http_response_code(400);
            return false;
        }

        $articleId = Article::createArticle($name);

        if ($articleId) {
            header('Location: ./article-edit/' . $articleId);
            exit;
        } else {
            http_response_code(500);
            return false;
        }
    }


    // F19) User can view the article edit page by 
    //navigating to URL ./article-edit/{id}, where 
    //{id} is a valid article identifier.
    private function renderEditForm($params)
    {
        $article = $this->fetchArticleFromUrl($params);

        if ($article) {
            return $this->render($this->paths['edit'], ['article' => $article, 'title' => 'Edit Article']);
        }

        return false;
    }



    // F25) If a user presses the Save button, all the 
    //changes are saved and the user is redirected back 
    //to the first page of the article list. 
    private function processEditForm($params)
    {
        if (!isset($params['id']) || !isset($params['name']) || !isset($params['content'])) {
            http_response_code(400);
            return false;
        }

        $id = (int)$params['id'];
        $name = trim($params['name']);
        $content = trim($params['content']);

        // F24) User can use the Save button if and only if the text input (Name) is not empty.
        if (empty($name) || strlen($name) > 32) {
            http_response_code(400);
            return false;
        }

        if (Article::editArticle($id, $name, $content)) {
            header('Location: ./articles');
            exit;
        } else {
            http_response_code(500);
            return false;
        }
    }


    /// !!!
    public function createSnapshot($params)
    {
        if (!isset($params['id'])) {
            http_response_code(400);
            return false;
        }

        $id = (int)$params['id'];

        return Article::createSnapshot($id);
    }

    /// !!!
    public function showSnapshots($params)
    {
        if (!isset($params['id'])) {
            http_response_code(400);
            return false;
        }

        $id = (int)$params['id'];

        return Article::showSnapshots($id);
    }

    /// !!!
    public function showSnapshot($params)
    {
        $snapshot = $this->fetchSnapshotFromUrl($params);
        $article = Article::getArticle($snapshot['article_id']);

        $snapshot['name'] = $article['name'];

        if ($snapshot) {
            return $this->render($this->paths['detail'], ['article' => $snapshot, 'title' => $snapshot['name'] . ' snapshot', 'edit' => false]);
        }
    }

    public function editArticle($params)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processEditForm($_POST);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $this->renderEditForm($params);
        }
    }

    public function deleteArticle($params)
    {
        if (!isset($params['id'])) {
            http_response_code(400);
            return false;
        }


        $id = (int)$params['id'];

        return Article::deleteArticle($id);
    }

    public function resetArticles($params)
    {
        return Article::clearArticleTable() && Article::fillArticlesTable();
    }

}