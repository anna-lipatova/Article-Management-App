<div class="container">
    <h1>Article list</h1>
    <hr />
    <!-- F01 -->
    <?php foreach ($articles as $article): ?>
    <div id="article-<?php echo $article['id'] ?>" class="article">
        <h3><?php echo htmlspecialchars($article['name']) ?></h3> <!-- /F05-->
        <div class="article-actions">
            <a href="./article/<?php echo $article['id'] ?>">Show</a> <!-- /F06--> <!-- /F14-->
            <a href="./article-edit/<?php echo $article['id'] ?>">Edit</a> <!-- /F07--> <!-- /F19-->
            <!-- !!! -->
            <a href="#" class="show-snapshot-button" onclick="toggleSnapshots(event, <?php echo $article['id'] ?>)">Show snapshots</a>
            <a href="#" onclick="deleteArticle(<?php echo $article['id'] ?>)">Delete</a> <!-- /F08-->	
        </div>
    </div>
    <?php endforeach; ?>
    <!-- /F01 -->
    <hr />
    <div class="footer">	
        <div>
            <button class="hidden" id="prev-button">Previous</button> <!-- F02 -->
            <button id="next-button">Next</button> <!-- F02 -->
        </div>
        <div>Page: <span id="page-counter">1</span></div> <!-- F03 -->  
        <div class>
            <button id="create-button">Create Article</button> <!-- F09 -->
        </div>
    </div>
</div>

<!-- F10 -->
<dialog id="modal">
    <form class="form" action="./article-create" method="post">
        <div class="form-group">
            <label for="article-name">Name: </label>
            <input id="article-name" name="name" type="text" maxlength="32" required />
        </div>
        <div class="footer">
            <button id="modal-submit" type="submit" disabled>Create</button> <!-- F13 -->
            <button id="modal-cancel" type="button">Cancel</button> <!-- F11 -->
        </div>
    </form>
</dialog>
<!-- /F10 -->