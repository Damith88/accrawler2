<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Accrawler News Articles</title>
        <link href="<?php echo asset_url('css/bootstrap.min.css'); ?>" rel="stylesheet">
    </head>
    <body>
        <ul style="list-style-type: none;">
            <?php foreach ($news as $news_item): ?>
                <li>
                    <h2><?php echo htmlspecialchars($news_item['heading']) ?></h2>
                    <div class="main">
                        <?php echo htmlspecialchars($news_item['content']) ?>
                    </div>
                    <p>
                        <a class="btn" href="news/<?php echo $news_item['id'] ?>">View article</a>
                        <a class="btn" href="<?php echo $news_item['sourceUrl'] ?>">View source article</a>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>

        <script src="<?php echo asset_url('js/jquery.min.js'); ?>"></script>
        <script src="<?php echo asset_url('js/bootstrap.min.js'); ?>"></script>
    </body>
</html>
