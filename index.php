<?php
require "vendor/autoload.php";
use PHPHtmlParser\Dom;

$dom = new Dom;
$dom->loadFromUrl('https://dribbble.com/shots/4374190-Hello-Dribbble');
$boturl = 'https://api.telegram.org/bot571854722:AAGG7U3jYeW0B6xM5aH9UIw5Xbt0L9EgZ3s/';
$sendmessage = $boturl . 'sendMessage?chat_id=92454&text=';
// $html = $dom->outerHtml;

$res = $dom->find('span.views-count');
$views = intval(str_replace(' views', '', str_replace(',', '', $res[0]->innerHtml()))) . PHP_EOL;
$res = $dom->find('a.likes-count');
$likes = intval(str_replace(' likes', '', str_replace(',', '', $res[0]->innerHtml())));

file_get_contents($sendmessage . $views . '-' . $likes)

?>