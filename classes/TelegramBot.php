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
            $this->updateId = $response['result'][count($response['result']) - 1]["update_id"] - $offset;
        }

        return !empty($response["result"]) ? $response : false;
    }

    function getUpdates() {
        $response = $this->query("getUpdates");

        return !empty($response["result"]) ? $response : false;
    }

    function sendMessage($option = []) {

        return $this->query("sendMessage", $option);
    }

    function sendMessageToChats($arChatId = [], $opt = []) {
        foreach ($arChatId as $chatId) {
            $opt["chat_id"] = $chatId;
            $this->sendMessage($opt);
        }
    }

    function pinChatMessage($chatId, $messageId) {
        return $this->query("pinChatMessage", ["chat_id" => $chatId, "message_id" => $messageId]);
    }
}