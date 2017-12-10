<?php

namespace zkr\classes;


class TelegramBot {
    protected $token = "480191787:AAGIhoCjqjD6Rm3zK9SIAbUzQLfCJeRt5M8";
    protected $urlBasic = "https://api.telegram.org/bot";
    protected $updateId = 0;

    public function query($method, $params = []) {
        $url = $this->urlBasic . $this->token . "/" . $method;
        $url .= (!empty($params) ? "?" . http_build_query($params) : "");

        return json_decode(file_get_contents($url), true);
    }

    public function getUpdatesOffset() {
        $response = $this->query("getUpdates", ["offset" => $this->updateId + 1]);
        // дабы отвечал только на последнее сообщение
        if (!empty($response->result)) {
            $this->updateId = $response->result[count($response->result) - 1]->update_id;
        }

        return $response->result;
    }

    public function getUpdates() {
        $response = $this->query("getUpdates");
        return !empty($response["result"]) ? $response : false;
    }

    public function sendMessage($chatId, $msg, $parse_mode = "") {

        return $this->query("sendMessage", [
            "chat_id"    => $chatId,
            "text"       => $msg,
            "parse_mode" => $parse_mode
        ]);
    }

    public function sendMessageToChats($arChatId = [], $msg = "", $mode = "") {
        foreach ($arChatId as $chatId) {
            $this->sendMessage($chatId, $msg, $mode);
        }
    }
}