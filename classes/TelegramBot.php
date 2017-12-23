<?php

namespace zkr\classes;


class TelegramBot {
    protected $token = "";
    protected $urlBasic = "https://api.telegram.org/bot";
    protected $updateId = 0;

    /**
     * TelegramBot constructor.
     * @param string $token
     */
    public function __construct($token) {
        $this->token = $token;
    }


    function query($method, $params = []) {
        $url = $this->urlBasic . $this->token . "/" . $method;
        $url .= (!empty($params) ? "?" . http_build_query($params) : "");

        return json_decode(file_get_contents($url), true);
    }

    function getUpdatesOffset($offset = 50) {
        $response = $this->query("getUpdates", ["offset" => $this->updateId + 1]);
        // дабы отвечал только на последнее сообщение
        if ($response && $response["ok"]) {
            $this->updateId = $response['result'][count($response['result']) - 1]["update_id"] - $offset ;
        }

        return !empty($response["result"]) ? $response : false;
    }

    function getUpdates() {
        $response = $this->query("getUpdates");

        return !empty($response["result"]) ? $response : false;
    }

    function sendMessage($chatId, $msg, $parse_mode = "", $disableWebPagePreview = false, $disableNotification = false) {

        return $this->query("sendMessage", [
            "chat_id"                  => $chatId,
            "text"                     => $msg,
            "parse_mode"               => $parse_mode,
            "disable_web_page_preview" => $disableWebPagePreview,
            "disable_notification"     => $disableNotification
        ]);
    }

    function sendMessageToChats($arChatId = [], $msg = "", $mode = "", $disableWebPagePreview = false, $disableNotification = false) {
        foreach ($arChatId as $chatId) {
            $this->sendMessage($chatId, $msg, $mode, $disableWebPagePreview, $disableNotification);
        }
    }

    function pinChatMessage($chatId, $messageId) {
        return $this->query("pinChatMessage", ["chat_id" => $chatId, "message_id" => $messageId]);
    }
}