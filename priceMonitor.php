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
        'http://torob.com/p/7fc8f00b-0180-4cf8-acb0-87b7c894c36d/%DA%AF%D9%88%D8%B4%DB%8C-%D9%85%D9%88%D8%A8%D8%A7%DB%8C%D9%84-%D9%87%D9%88%D8%A7%D9%88%DB%8C-%D9%85%D8%AF%D9%84-p20-lite-%D8%AF%D9%88-%D8%B3%DB%8C%D9%85-%DA%A9%D8%A7%D8%B1%D8%AA-%D8%B8%D8%B1%D9%81%DB%8C%D8%AA-64-%DA%AF%DB%8C%DA%AF%D8%A7%D8%A8%D8%A7%DB%8C%D8%AA/',
        'https://www.digistyle.com/product/%D8%AA%DB%8C-%D8%B4%D8%B1%D8%AA-%D8%A2%D8%B3%D8%AA%DB%8C%D9%86-%D8%A8%D9%84%D9%86%D8%AF-%D9%85%D8%B1%D8%AF%D8%A7%D9%86%D9%87-69120',
        'https://www.digistyle.com/product/%D8%AA%DB%8C-%D8%B4%D8%B1%D8%AA-%D8%A2%D8%B3%D8%AA%DB%8C%D9%86-%D8%A8%D9%84%D9%86%D8%AF-%D9%85%D8%B1%D8%AF%D8%A7%D9%86%D9%87-69103',
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
                $name = $json['alternateName'];
                $text = $name . ' : ' . $price;
                // var_export($json);
                if ($price && $name) {
                    $telegram->sendMessage(['chat_id' => 92454, 'text' => $text]);
                }
            }
        }
    }
} catch (Exception $e) {
    dbg(make_exception_array($e));
}
?>
</pre>
