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
        <h1 id="mainHeading"><a href="<?php echo site_url("news/index"); ?>">Accrawler - News Crawler</a></h1>
        <div id="search_news" class="box">
            <div class="head">
                <h1>Search News</h1>
            </div>
            <div class="inner">
                <form id="newsSearchForm" action="<?php echo site_url('news/search'); ?>" method="POST">
                    Key Word:
                    <input class="inputText" type="text" id="keyWords" name="keyWords" value="">
                    <br/>

                    Date Range-:
                    <span id="fromLable"></span>
                    From:
                    <input class="calendar" type="text" id="from" name="fromDate" value="" placeholder="mm/dd/yyyy">
                    <br/>
                    <span id="toLable"></span>
                    To:
                    <input class="calendar" type="text" id="to" name="toDate" value="" placeholder="mm/dd/yyyy">
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
                        <h1 style="font-size: 18px; white-space: nowrap">Latest Accident News</h1>
                    </div>
                    <ul id="sidenav" style="list-style-type: none; margin-left: 0px">
                        <?php foreach ($latest_accidents as $news_item): ?>
                            <li class="selected"><a href="<?php echo $news_item['url'] ?>"><?php echo htmlspecialchars($news_item['heading']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div> <!-- sidebar -->                
                <ul class="newsUl" style="list-style-type: none;">
                    <?php if (count($news) > 0) { ?>
                        <div class="pagination">
                            <ul>
                                <?php echo $pagination_helper->create_links(); ?>
                            </ul>   
                        </div>
                        <?php foreach ($news as $news_item):
                            ?>
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
                        <?php
                        endforeach;
                    } else {
                        ?>
                        <span>No search result found</span>
<?php } ?>
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
    var filterValues = <?php echo json_encode($filters); ?>;
    $.each(filterValues, function(index, value) {
        $("form#newsSearchForm [name='" + index + "']").val(value);
    });
    $(document).ready(function () {
        $('article').readmore();
        $('input.calendar').datepicker();
        $("#location").autocomplete({source: locations});
    });
</script>
