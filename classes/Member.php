<?php

namespace zkr\classes;


class Member {

    private $id;
    private $firstName;
    private $lastName;
    private $isBot;

    public function addMember($id, $firstName, $lastName, $isBot) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->isBot = $isBot;

        $this->saveMember();

        return $id;
    }

    public function getMembers() {
        return $this->getData("members");
    }

    public function getData($fileName) {
        $file = "data/" . $fileName . ".php";

        if (file_exists($file)) {
            return include($file);
        } else {
            return false;
        }
    }

    private function saveMember() {
        $data = [
            "ID"         => $this->id,
            "FIRST_NAME" => $this->firstName,
            "LAST_NAME"  => $this->lastName,
            "IS_BOT"     => $this->isBot
        ];
        $this->saveData($data, "members");
    }

    public function saveData($data, $fileName) {
        $storageData = '<?php return ' . var_export($data, true) . ';';
        file_put_contents("data/" . strtolower($fileName) . '.php', $storageData);
    }

    public function getAddedMembers($result) {
        $arMembers = [];
        foreach ($result as $update) {
            $message = $update["message"];
            // is bot and not new member
            if ($message["from"]["is_bot"] && empty($message["new_chat_member"])) {
                continue;
            } elseif (!empty($message["new_chat_member"]) && !$message['new_chat_member']["is_bot"]) {
                // массив с новыми пользователями
                $arMembers[$update['update_id']] = [
                    "UPDATE_ID"  => $update['update_id'],
                    "DATE"       => $message['date'],
                    "FROM"       => [
                        "ID"         => $message['from']["id"],
                        "IS_BOT"     => $message['from']["is_bot"],
                        "FIRST_NAME" => $message['from']["first_name"],
                        "LAST_NAME"  => $message['from']["last_name"],
                        "USERNAME"   => $message['from']["username"]
                    ],
                    "NEW_MEMBER" => [
                        "ID"         => $message['new_chat_member']["id"],
                        "IS_BOT"     => $message['new_chat_member']["is_bot"],
                        "FIRST_NAME" => $message['new_chat_member']["first_name"],
                        "LAST_NAME"  => $message['new_chat_member']["last_name"],
                        "USERNAME"   => $message['new_chat_member']["username"]
                    ]
                ];
            }
        }

        return $arMembers;
    }

    public function getTickets() {
        return $this->getData("tickets");
    }

    public function getProcessData() {
        return $this->getData("process");
    }

    public function getNumberInvitedMembers($userId, $arProcessData) {
        $countInvite = 0;
        foreach ($arProcessData as $element) {
            if ($element["USER_ID"] == $userId) {
                $countInvite++;
            }
        }

        return $countInvite;
    }

    public function getInviteReport($arProcessData, $arStoredMembers) {
        $msg = "";
        foreach ($arProcessData as $data) {
            $msg .= date("d-m-Y H:i:s", $data["DATE"])
                . " " . $arStoredMembers[$data["USER_ID"]]["FIRST_NAME"]
                . " " . $arStoredMembers[$data["USER_ID"]]["LAST_NAME"]
                . " " . $arStoredMembers[$data["USER_ID"]]["USERNAME"]
                . " invited"
                . " " . $arStoredMembers[$data["NEW_MEMBER"]]["FIRST_NAME"]
                . " " . $arStoredMembers[$data["NEW_MEMBER"]]["LAST_NAME"]
                . " " . $arStoredMembers[$data["NEW_MEMBER"]]["USERNAME"]
                . PHP_EOL;
        }

        return $msg;
    }

    public function getTicketsReport($arStoredMembers, $arTickets) {
        $msg = "";
        foreach ($arTickets as $number => $userId) {
            $msg .= $arStoredMembers[$userId]["FIRST_NAME"]
                . " " . $arStoredMembers[$userId]["LAST_NAME"]
                . " " . $arStoredMembers[$userId]["USERNAME"]
                . " ticket number " . $number
                . PHP_EOL;
        }

        return $msg;
    }
}