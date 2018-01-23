<?php

namespace zkr\classes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;

class TelegramBot {
    protected $token = "";
    public $info = [];
    protected $urlBasic = "https://api.telegram.org/bot";
    protected $updateId = 0;

    /**
     * TelegramBot constructor.
     * @param string $token
     */
    function __construct($token) {
        $this->token = $token;
        $this->info = $this->getMe();
    }

    function query($method, $params = []) {
        $url = $this->urlBasic . $this->token . "/" . $method;
        $params['method'] = $method;

        return $this->sendRequest($url, $params);
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
        $arChatId = is_array($arChatId) ? $arChatId : (array)$arChatId;
        foreach ($arChatId as $chatId) {
            $opt["chat_id"] = $chatId;
            $this->sendMessage($opt);
        }
    }

    function pinChatMessage($chatId, $messageId) {
        return $this->query("pinChatMessage", ["chat_id" => $chatId, "message_id" => $messageId]);
    }

    function sendRequest($baseUrl = "", $options = []) {
        $result = false;
        try {
            $client = new Client([
                'base_uri' => $baseUrl, // Base URI is used with relative requests
                'timeout'  => 15, // You can set any number of default request options.
//                'timeout'  => 2.0, // You can set any number of default request options.
            ]);
            $parameters = !empty($options) ? ['query' => $options] : [];

            $response = $client->request('GET', '', $parameters);

            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();
                $result = (!empty($body)
                    ? json_decode($body, true)
                    : "empty body method -> {$options['method']}");
            } else {
                $result = "error method -> {$options["method"]}";
                $this->errorLog($result . "    " . http_build_query($options));
            }
        } catch (ClientException $e) {
            $errorMsg = Psr7\str($e->getRequest()) . Psr7\str($e->getResponse());
            $this->errorLog($errorMsg);
        }
        unset($client);

        return $result;
    }

    function errorLog($errorMsg) {
        $errorMsg = "-----" . date("d-m-Y H:i:s", time()) . "-----"
            . PHP_EOL
            . $errorMsg
            . PHP_EOL . " " . PHP_EOL;
        file_put_contents("error.log", $errorMsg, FILE_APPEND);
    }

    public function getChatAdministrators($chatId) {
        $response = $this->query("getChatAdministrators", ["chat_id" => $chatId]);

        return !empty($response["result"]) ? $response["result"] : false;
    }

    public function deleteMessage($chatId, $msgId) {
        $response = $this->query("deleteMessage", ["chat_id" => $chatId, "message_id" => $msgId]);

        return !empty($response["ok"]) ? $response["ok"] : false;
    }

    public function getAdminsIds($arAdministrators) {
        $arResult = [];
        foreach ($arAdministrators as $administrator) {
            $arResult[] = $administrator["user"]["id"];
        }

        return $arResult;
    }

    /**
     * check user status in channel
     * @param $userId
     * @param $channelName
     * @return bool
     */
    public function inChannel($userId, $channelName) {
        $response = $this->query("getChatMember", ["chat_id" => $channelName, "user_id" => $userId]);

        return !empty($response["result"]) ? $response["result"]["status"] : false;
    }

    public function saveUpdateId($updateId) {
        return file_put_contents("data/update_id.dat", $updateId);
    }

    public function getUpdateId() {
        $file = "data/update_id.dat";

        return file_exists($file) ? file_get_contents($file) : false;

    }

    public function getMe() {
        $response = $this->query("getMe");

        return !empty($response["result"]) ? $response["result"] : false;
    }
}