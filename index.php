<?php
require "vendor/autoload.php";
require "config.php";
use PHPHtmlParser\Dom;

$dom = new Dom;
$dom->loadFromUrl('https://dribbble.com/shots/4374190-Hello-Dribbble');
$boturl = 'https://api.telegram.org/bot' . $token . '/';
$sendmessage = $boturl . 'sendMessage?chat_id=' . $admin_id . '&text=';
// $html = $dom->outerHtml;

$res = $dom->find('span.views-count');
$views = intval(str_replace(' views', '', str_replace(',', '', $res[0]->innerHtml()))) . PHP_EOL;
$res = $dom->find('a.likes-count');
$likes = intval(str_replace(' likes', '', str_replace(',', '', $res[0]->innerHtml())));

file_get_contents($sendmessage . $views . '-' . $likes);
file_get_contents($sendmessage . ((string) var_export($_POST, true)));
