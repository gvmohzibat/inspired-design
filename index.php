<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// requirements
require 'vendor/autoload.php';
require_once 'config.php';
// file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id=92454&text=debug');
// require 'main-controller.php';
use PHPHtmlParser\Dom;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
// connecting
$telegram = new Api($token);
// get user message
// $updates = $telegram->getWebhookUpdates();
// $message = $updates->getMessage();
// $callback_query = $updates->getCallbackQuery();

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

// if ($message != null) {
// $chat = $message->getChat();
// $chat_id = (int) $chat->getId();
// $text       = $message->getText();
// $message_id = $message->getMessageId();
// $username   = $message->getFrom()->getUsername();
// $user       = $message->getFrom();
// $username   = $user->getUsername();
// $fullname   = $user->getFirstName() . ' ' . $user->getLastName();
try {
    $dom = new Dom;
    $boturl = 'https://api.telegram.org/bot' . $token . '/';
    $sendmessage = $boturl . 'sendMessage?chat_id=' . $admin_id . '&text=';

    $json = json_decode(file_get_contents('php://input'), true);
    // var_export($json);

    $dom->loadFromUrl($json['url']);
    $likes = getLikes();
    $views = getViews();

    $chatID = $admin_id;
    if ($likes > 200 || $views > 2000) {
        $chatID = $channel_id;
    }
    $titleStr = '🔅 <a href="' . $json['url'] . '">' . $json['title'] . '</a>';
    $likeStr = trim('👍 Likes: ' . $likes);
    $viewStr = trim('👀 Views: ' . $views);
    $idStr = '💠 @inspired_design';
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
        'parse_mode' => 'html',
    ];
    var_export($sMessage);
    $telegram->sendPhoto($sMessage);

} catch (Exception $e) {
    log_debug(make_exception_array($e));
}
// }
