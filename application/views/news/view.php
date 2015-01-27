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
echo '<h2>' . htmlspecialchars($news_item['heading']) . '</h2>';
echo $escapedText . "<br/><br/>";
if ($sentences && !$news_item['has_overlapping_entities']) {
	echo implode("<br/>", $markupSentences);
}
