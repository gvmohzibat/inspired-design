<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require 'vendor/autoload.php';
require_once 'config.php';

use Telegram\Bot\Api;
$telegram = new Api($token);

function make_exception_array($e)
{
    return [
        'exception' => 'exception',
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
        'trace' => $e->getTraceAsString(),
    ];
}

function dbg($data, $chat_id = 92454)
{
    $text = var_export($data, true);
    global $telegram;
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $text,
    ]);
}

try {
    $urls = [
        'http://torob.com/p/254b8b34-1e0c-40b2-830e-ad49705ee9fb/%DA%AF%D9%88%D8%B4%DB%8C-%D9%85%D9%88%D8%A8%D8%A7%DB%8C%D9%84-%D9%87%D9%88%D8%A7%D9%88%DB%8C-%D9%85%D8%AF%D9%84-mate-10-alp-l29-%D8%AF%D9%88-%D8%B3%DB%8C%D9%85-%DA%A9%D8%A7%D8%B1%D8%AA/',
        'https://www.digikala.com/Product/DKP-32334/',
    ];

    foreach ($urls as $url) {
        // echo '<br>';
        // var_export($url);
        // echo '<br>';

        $dom = new DOMDocument();
        libxml_use_internal_errors(1);
        @$dom->loadHTML(file_get_contents($url));

        $div = $dom->getElementsByTagName('script');

        foreach ($div as $d) {
            if ($d->getAttribute('type') == 'application/ld+json') {
                $json = json_decode($d->textContent, true);
                $price = null;
                if (array_key_exists('lowPrice', $json['offers'])) {$price = $json['offers']['lowPrice'];}
                if (array_key_exists('price', $json['offers'])) {$price = $json['offers']['price'];}
                $text = $json['name'] . ' : ' . $price;
                var_export($json);
                $telegram->sendMessage(['chat_id' => 92454, 'text' => $text]);
            }
        }
    }
} catch (Exception $e) {
    dbg(make_exception_array($e));
}
?>
</pre>
