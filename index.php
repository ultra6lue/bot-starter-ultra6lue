<?php

require_once __DIR__ . '/vendor/autoload.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
try {
  $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
} catch(\LINE\LINEBot\Exception\InvalidSignatureException $e) {
  error_log("parseEventRequest failed. InvalidSignatureException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
  error_log("parseEventRequest failed. UnknownEventTypeException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\UnknownMessageTypeException $e) {
  error_log("parseEventRequest failed. UnknownMessageTypeException => ".var_export($e, true));
} catch(\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
  error_log("parseEventRequest failed. InvalidEventRequestException => ".var_export($e, true));
}

foreach ($events as $event) {
  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
    error_log('Non message event has come');
    continue;
  }
  if (!($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
    error_log('Non text message has come');
    continue;
  }
  # $bot->replyText($event->getReplyToken(), $event->getText());

#$profile = $bot->getProfile($event->getUserId())->getJSONDecodedBody();
#$message = $profile["displayName"] . "さん、おはようございます！今日も頑張りましょう！";
#$bot->replyMessage($event->getReplyToken(),
#  (new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder())
#    ->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message))
#    ->add(new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder(1, 114))
#);

# replyTextMessage($bot, $event->getReplyToken(), "TextMessage");
# replyImageMessage($bot, $event->getReplyToken(), "https://" . $_SERVER["HTTP_HOST"] . "/imgs/original.jpg", "https://" . $_SERVER["HTTP_HOST"] . "/imgs/preview.jpg");
replyLocationMessage($bot, $event->getReplyToken(), "LINE", "東京都渋谷区渋谷2-21-1 ヒカリエ27階", 35.659025, 139.703473);

}

# テキストメッセージの送信には、Bot->replyText関数が手軽ですが、他のメッセージと合わせて送ることも考慮しTextMessageBuilderを使って送信しましょう
function replyTextMessage($bot, $replyToken, $text) {
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

# 画像の送信には、ImageMessageBuilderを使います
function replyImageMessage($bot, $replyToken, $originalImageUrl, $previewImageUrl) {
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl, $previewImageUrl));
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

# 位置情報の送信には、LocationMessageBuilderを使います
function replyLocationMessage($bot, $replyToken, $title, $address, $lat, $lon) {
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\LocationMessageBuilder($title, $address, $lat, $lon));
  if (!$response->isSucceeded()) {
    error_log('Failed!'. $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

# test

 ?>
