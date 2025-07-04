<?php

require_once __DIR__ . '/Database.php';

class Article
{
    public static function getArticles()
    {
        $db = Database::getConnection();

        $result = $db->query("SELECT * FROM articles");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getArticle($id)
    {
        $db = Database::getConnection();

        $query = $db->prepare("SELECT * FROM articles WHERE id = ?");
        $query->bind_param('i', $id);
        $query->execute();
        $result = $query->get_result();
        $article = $result->fetch_assoc();

        $query->close();
        return $article;
    }

    public static function createArticle($name)
    {
        $db = Database::getConnection();

        $query = $db->prepare("INSERT INTO articles (name) VALUES (?)");
        $query->bind_param('s', $name);
        $query->execute();
        $query->close();

        return $db->insert_id; //controller=>createArticle
    }

    public static function editArticle($id, $name, $content)
    {
        $db = Database::getConnection();

        $query = $db->prepare("UPDATE articles SET name = ?, content = ? WHERE id = ?");
        $query->bind_param('ssi', $name, $content, $id);
        $result = $query->execute();

        $query->close();
        return $result;
    }

    public static function deleteArticle($id)
    {
        $db = Database::getConnection();

        $query = $db->prepare("DELETE FROM articles WHERE id = ?");
        $query->bind_param('i', $id);
        $result = $query->execute();

        $query->close();
        return $result;
    }

    /// !!!
    public static function createSnapshot($id) 
    {        
        $db = Database::getConnection();

        // $article = self::getArticle($id);
        /// or
        $query = $db->prepare("SELECT * FROM articles WHERE id = ?");
        $query->bind_param('i', $id);
        $query->execute();
        $result = $query->get_result();
        $article = $result->fetch_assoc();

        $query = $db->prepare("INSERT INTO snapshots (article_id, content) VALUES (?, ?)");
        $query->bind_param('is', $article['id'], $article['content']);
        $result = $query->execute();

        $query->close();
        return $db->insert_id;
    }

    /// !!!
    public static function showSnapshots($id)
    {
        $db = Database::getConnection();

        $query = $db->prepare("SELECT * FROM snapshots WHERE article_id = ? ORDER BY created_at DESC");
        $query->bind_param('i', $id);
        $query->execute();
        $result = $query->get_result();
        $snapshots = $result->fetch_all(MYSQLI_ASSOC);

        $query->close();
        echo json_encode($snapshots); // to be available for js
        return $result;
    }

    /// !!!
    public static function getSnapshot($id)
    {
        $db = Database::getConnection();

        $query = $db->prepare("SELECT * FROM snapshots WHERE id = ?");
        $query->bind_param('i', $id);
        $query->execute();
        $result = $query->get_result();
        $snapshot = $result->fetch_assoc();

        $query->close();
        return $snapshot;
    }

    // uz neni
    //controller => resetArticles
    public static function dropArticlesTable()
    {
        $db = Database::getConnection();
        return $db->query("DROP TABLE IF EXISTS articles");
    }

    public static function clearArticleTable()
    {
        $db = Database::getConnection();
        return $db->query("DELETE FROM articles");
    }

    // uz neni
    //controller => resetArticles
    public static function createArticlesTable()
    {
        $db = Database::getConnection();
        return $db->query("CREATE TABLE articles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(32) NOT NULL,
            content TEXT
        )");
    }

    //controller => resetArticles
    public static function fillArticlesTable()
    {
        $db = Database::getConnection();

        $content = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean tempor pretium est ut varius. Mauris id pretium sapien. Mauris suscipit dui felis, ac malesuada risus consequat sit amet. Proin molestie lobortis lacus, nec pharetra tellus volutpat convallis. In imperdiet ipsum nisi, id viverra velit tincidunt eget. Aliquam hendrerit, dolor non scelerisque mattis, tortor nibh commodo purus, in rhoncus mi lectus vel lacus. Nunc bibendum pulvinar leo et aliquet. Suspendisse aliquet lacus interdum, sagittis tellus ut, finibus mauris.";
        $query = $db->prepare("INSERT INTO articles (name, content) VALUES (?, ?)");
        $success = true;
        for ($i = 0; $i < 44 && $success; $i++) {
            $name = 'Article ' . ($i + 1);
            $query->bind_param('ss', $name, $content);
            $success = $query->execute();
        }

        $query->close();
        return $success;
    }
}