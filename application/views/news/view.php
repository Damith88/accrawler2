<style>
    span.person {
        background-color: yellow
    }
    span.date {
        background-color: pink
    }
    span.location {
        background-color: greenyellow
    }
</style>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo htmlspecialchars($news_item['heading']); ?></title>
        <link href="<?php echo asset_url('css/bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo asset_url('css/news.css'); ?>" rel="stylesheet">
    </head>
    <body>
        <h1 id="mainHeading"><a href="<?php echo site_url("news/index"); ?>">Accrawler - News Crawler</a></h1>

        <?php
        $rawContent = $news_item['content'];
        $escapedText = htmlspecialchars($rawContent);
        $sentences = $news_item['sentences'];
        if ($sentences && !$news_item['has_overlapping_entities']) {
            $hasSpecialChars = $rawContent !== $escapedText;
            preg_match_all('/\[(\d+\.\.\d+)\)/', $sentences, $matches);
            $sBoundaries = $matches[1];
            $sentences = array();
            foreach ($sBoundaries as $sBoundary) {
                list($sStart, $sEnd) = explode('..', $sBoundary);
                $sentences[] = mb_substr($rawContent, $sStart, $sEnd - $sStart);
            }

            preg_match_all('/\d+ (person|location|date) \d+ \d+ \d+\.\d+/', $news_item['named_entity_info'], $matches1);
            $namedEntityStrValues = $matches1[0];
            $namedEntities = array();
            $namedEntityInfoBySIndex = array();
            foreach ($namedEntityStrValues as $namedEntityStrValue) {
                $namedEntityInfo = explode(' ', $namedEntityStrValue);
                list($sIndex, $type, $startPos, $endPos, $prob) = $namedEntityInfo;
                $sentence = $sentences[$sIndex];
                $entityName = mb_substr($sentence, $startPos, $endPos - $startPos);
                $namedEntities = array($entityName, $type, $prob);
                if (isset($namedEntityInfoBySIndex[$sIndex])) {
                    $namedEntityInfoBySIndex[$sIndex][] = $namedEntityInfo;
                } else {
                    $namedEntityInfoBySIndex[$sIndex] = array($namedEntityInfo);
                }
            }

            $markupSentences = array();
            foreach ($sentences as $sIndex => $sentence) {
                $markupText = '';
                $currentPos = 0;
                if (isset($namedEntityInfoBySIndex[$sIndex])) {
                    $indexes = $namedEntityInfoBySIndex[$sIndex];
                } else {
                    $indexes = array();
                    $end = 0;
                }
                foreach ($indexes as $index) {
                    list($sIndex, $type, $start, $end, $prob) = $index;
                    if ($start > $currentPos) {
                        $val = mb_substr($sentence, $currentPos, $start - $currentPos);
                        $markupText .= $hasSpecialChars ? htmlspecialchars($val) : $val;
                    }
                    $entity = mb_substr($sentence, $start, $end - $start);
                    if ($hasSpecialChars) {
                        $entity = htmlspecialchars($entity);
                    }
                    $markupText .= '<span class="' . $type . '" title="' . $type . '">' . $entity . '</span>';
                    $currentPos = $end;
                }
                $len = strlen($sentence);
                if ($len > $end) {
                    $val = mb_substr($sentence, $end, $len - $end);
                    $markupText .= $hasSpecialChars ? htmlspecialchars($val) : $val;
                }
                $markupSentences[] = $markupText;
            }
        }
        ?>
        <div id="search_news" class="box">
            <div class="head">
                <h1>Article Heading: <?php echo htmlspecialchars($news_item['heading']); ?></h1>
            </div>
            <div class="inner">
                <?php echo $escapedText; ?>
            </div>
            <a class="btn" href="<?php echo site_url("news/index"); ?>">Back to List</a>
            <a class="btn" href="<?php echo $news_item['sourceUrl'] ?>">View source article</a>
        </div>
        <?php if ($sentences && !$news_item['has_overlapping_entities']) { ?>
        <div id="search_news" class="box">
            <div class="head">
                <h1>Article content with names highlighted</h1>
            </div>
            <div class="inner">                
                <?php echo implode("<br/>", $markupSentences);?>
            </div>
        </div>
        <?php } ?>
    </body>
</html>