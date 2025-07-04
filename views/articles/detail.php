<!-- F16 -->
<div class="container">
    <h1><?php echo htmlspecialchars($article['name']) ?></h1>
    <p><?php echo htmlspecialchars($article['content']) ?></p>
    <div class="footer">
        <!-- !!! -->
        <?php if ($edit): ?>
        <button class="primary"
            onclick="window.location.href='../article-edit/<?php echo $article['id']?>'">Edit</button> <!-- /F17--> <!-- /F19-->
        <button onclick="createSnapshot(<?php echo $article['id'] ?>)">Create Snapshot</button>
        <?php else: ?>
            <button onclick="window.location.href='../article/<?php echo $article['article_id']?>'">Show current version</button>
        <?php endif; ?>
        <!-- !!! -->
        <button onclick="window.location.href='../articles'" type="button">Back to Articles</button> <!-- /F18-->
    </div>
</div>
<!-- /F16 -->