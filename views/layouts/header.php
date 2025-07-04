<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Articles') ?></title>
    <link rel="stylesheet" href="<?php echo $stylesheetPath ?>">
    <script src="<?php echo $scriptPath ?>" defer></script>
</head>

<body>
    <button id="reset-button" onclick="resetArticlesTable()">Reset</button>