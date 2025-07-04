<div class="container">
    <form class="form" action="../article-edit" method="post">
        <input type="hidden" name="id" value="<?php echo $article['id'] ?>" /> <!-- to be sent along with form data -->
        <!-- F23 -->
        <div class="form-group">
            <label for="article-name">Name</label>
            <input id="article-name" name="name" value="<?php echo htmlspecialchars($article['name']) ?>" type="text"
                maxlength="32" required />
        </div>
        <div class="form-group">
            <label for="article-content">Content</label>
            <textarea id="article-content" name="content"
                rows="5"><?php echo htmlspecialchars($article['content']) ?></textarea>
        </div>
        <!-- /F23 -->
        <div class="footer">
            <button class="success" id="modal-submit" type="submit">Edit</button>
            <button class="danger" onclick="window.location.href='../articles'" type="button">Back to Articles</button> <!-- F22 -->
        </div>
    </form>
</div>