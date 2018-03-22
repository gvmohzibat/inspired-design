<?php
require "vendor/autoload.php";
use PHPHtmlParser\Dom;

$dom = new Dom;
$dom->loadFromUrl('https://dribbble.com/shots/4374190-Hello-Dribbble');
// $html = $dom->outerHtml;

$res = $dom->find('span.views-count');
echo intval(str_replace(' views', '', str_replace(',', '', $res[0]->innerHtml()))) . PHP_EOL;
$res = $dom->find('a.likes-count');
echo intval(str_replace(' likes', '', str_replace(',', '', $res[0]->innerHtml())));

?>