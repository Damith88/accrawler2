<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Accrawler News Articles</title>
        <link href="<?php echo asset_url('css/bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo asset_url('css/news.css'); ?>" rel="stylesheet">
    </head>    
    <body>
        <h1 id="mainHeading"><a href="<?php echo site_url("news/index");?>">Accrawler - News Crawler</a></h1>
        <div id="search_news" class="box">
            <div class="head">
                <h1>Search News</h1>
            </div>
            <div class="inner">
                <form action="<?php echo site_url('news/search');?>" method="POST">
                    Key Word:
                    <input class="inputText" type="text" id="keyWords" name="keyWords" value="">
                    <br/>

                    Date Range-:
                    <span id="fromLable"></span>
                    From:
                    <input class="calendar" type="text" id="from" name="fromDate" value="">
                    <br/>
                    <span id="toLable"></span>
                    To:
                    <input class="calendar" type="text" id="to" name="toDate" value="">
                    <br/>
                    Location:
                    <input class="inputText" type="text" id="location" name="location" value="">
                    <br/>
                    <button class="btn" id="searchBtn">Search</button>
                </form>
            </div>
        </div>
        <div id="newsDiv" class="box">
            <div class="head">
                <h1>News</h1>
            </div>
            <div class="inner">
                <div id="sidebar">
                    <div id="profile-pic">
                        <h1 style="font-size: 18px;">News Articles</h1>
                    </div>
                    <ul id="sidenav">
                        <?php foreach ($news as $news_item): ?>
                            <li class="selected"><a href="<?php echo $news_item['sourceUrl'] ?>"><?php echo htmlspecialchars($news_item['heading']) ?></a></li>

                        <?php endforeach; ?>
                    </ul>
                </div> <!-- sidebar -->

                <ul class="newsUl" style="list-style-type: none;">
                    <?php foreach ($news as $news_item): ?>
                        <li>
                            <article>
                                <h2><?php echo htmlspecialchars($news_item['heading']) ?></h2>
                                <div class="content">
                                    <?php echo htmlspecialchars($news_item['content']) ?>
                                </div>                            
                            </article>
                            <p>
                                <a class="btn" href="<?php echo site_url("news/" . $news_item['id']); ?>">View article</a>
                                <a class="btn" href="<?php echo $news_item['sourceUrl'] ?>">View source article</a>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <link type="text/css" rel="stylesheet" media="all" 
              href="<?php echo asset_url('css/jquery-ui.min.css'); ?>" /> 
        <script src="<?php echo asset_url('js/jquery.min.js'); ?>"></script>
        <script src="<?php echo asset_url('js/jquery-ui.min.js'); ?>"></script>
        <script src="<?php echo asset_url('js/bootstrap.min.js'); ?>"></script>
        <script src="<?php echo asset_url('js/readmore.min.js'); ?>"></script>
    </body>
</html>
<script type="text/javascript">
    var locations = <?php echo json_encode($locations); ?>;
    $(document).ready(function () {
        $('article').readmore();
        
        $('input.calendar').datepicker();

        $("#location").autocomplete({source: locations});
    });
</script>
