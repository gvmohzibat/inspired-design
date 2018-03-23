<?php
error_reporting(-1);
ini_set('display_errors', 'On');

// requirements
require 'vendor/autoload.php';
require_once 'config.php';
// file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id=92454&text=debug');
use PHPHtmlParser\Dom;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
$telegram = new Api($token);

if (isset($_GET['debug'])) {
    log_debug($_GET['debug']);
}

function getLikes()
{
    global $dom;
    $res = $dom->find('a.likes-count');
    return intval(str_replace(' likes', '', str_replace(',', '', $res[0]->innerHtml())));
}
function getViews()
{
    global $dom;
    $res = $dom->find('span.views-count');
    return intval(trim(str_replace(' views', '', str_replace(',', '', $res[0]->innerHtml())))) . PHP_EOL;
}

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
function log_debug($data, $chat_id = 92454)
{
    $text = var_export($data, true);
    global $telegram;
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $text,
    ]);
}

try {
    $dom = new Dom;
    $boturl = 'https://api.telegram.org/bot' . $token . '/';
    $sendmessage = $boturl . 'sendMessage?chat_id=' . $admin_id . '&text=';

    $json = json_decode(file_get_contents('php://input'), true);
    log_debug($json);

    $dom->loadFromUrl($json['url']);
    $likes = getLikes();
    $views = getViews();

    $chatID = $admin_id;
    if ($likes > 150 || $views > 1000) {
        $chatID = $channel_id;
    }
    $titleStr = '🔅 ' . $json['title'];
    $likeStr = trim('👍 Likes: ' . $likes);
    $viewStr = trim('👀 Views: ' . $views);
    $idStr = '💠 ' . $channel_id;
    $caption = $titleStr . PHP_EOL . $likeStr . PHP_EOL . $viewStr . PHP_EOL . $idStr;

    if (strlen($caption) > 200) {
        $caption = $titleStr . PHP_EOL . $likeStr . PHP_EOL . $idStr;
    }
    if (strlen($caption) > 200) {
        $caption = $titleStr . PHP_EOL . $idStr;
    }
    if (strlen($caption) > 200) {
        $caption = $likeStr . PHP_EOL . $viewStr . PHP_EOL . $idStr;
    }

    $sMessage = [
        'chat_id' => $chatID,
        'photo' => InputFile::create($json['image'], 't.me/inspired_design.jpg'),
        'caption' => $caption,
        // 'parse_mode' => 'html',
    ];
    var_export($sMessage);
    $telegram->sendPhoto($sMessage);

} catch (Exception $e) {
    log_debug(make_exception_array($e));
}
// }
