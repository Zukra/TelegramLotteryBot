<?php

namespace zkr\classes;


class Lotto {
    public $arStoredMembers = [];    // all stored members
    public $arTickets = [];          // all stored tickets (выданные билеты)
    public $arNewMembers = [];       // new members in chat
    public $arProcessData = [];      // general data (кто кого пригласил, присвоенные билеты)
    public $arMemberTicketsCnt = []; // количество билетов участника

    public function getMembers() {
        return $this->getData("members");
    }

    public function getData($fileName) {
        $file = "data/{$fileName}php";

        return file_exists($file) ? include($file) : false;
    }

    public function saveData($data, $fileName) {
        $storageData = '<?php return ' . var_export($data, true) . ';';

        return file_put_contents("data/" . strtolower($fileName) . '.php', $storageData);
    }

    public function getAddedMembers($result, $arChatId) {
        $arMembers = [];
        foreach ($result as $update) {
            if (!isset($update["message"])) {
                continue;
            }
            $message = $update["message"];
            // this is bot and not new member and correct chat
            if ($message["from"]["is_bot"] || !isset($message["new_chat_members"])
                || !in_array($message["chat"]["id"], $arChatId)
            ) {
                continue;
            } elseif (!empty($message["new_chat_members"])) {
                // массив с новыми пользователями
                $arNewChatMembers = [];
                foreach ($message["new_chat_members"] as $newMember) {
                    if ($newMember['is_bot']) continue;
                    $arNewChatMembers[] = [
                        "ID"         => $newMember["id"],
                        "IS_BOT"     => $newMember["is_bot"],
                        "FIRST_NAME" => $newMember["first_name"],
                        "LAST_NAME"  => !empty($newMember["last_name"]) ? $newMember["last_name"] : "",
                        "USERNAME"   => !empty($newMember["username"]) ? $newMember["username"] : ""
                    ];
                }
                $arMembers[$update['update_id']] = [
                    "UPDATE_ID"  => $update['update_id'],
                    "DATE"       => $message['date'],
                    "FROM"       => [
                        "ID"         => $message['from']["id"],
                        "IS_BOT"     => $message['from']["is_bot"],
                        "FIRST_NAME" => $message['from']["first_name"],
                        "LAST_NAME"  => isset($message['from']["last_name"]) ? $message['from']["last_name"] : "",
                        "USERNAME"   => !empty($message['from']["username"]) ? $message['from']["username"] : ""
                    ],
                    "NEW_MEMBER" => $arNewChatMembers
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

    public function getNumberInvitedMembers($userId) {
        $countInvite = 0;
        foreach ($this->arProcessData as $element) {
            if ($element["USER_ID"] == $userId) {
                $countInvite++;
            }
        }

        return $countInvite;
    }

    public function getInviteReport() {
        $msg = "";
        foreach ($this->arProcessData as $data) {
            $msg .= date("d-m-Y H:i:s", $data["DATE"])
                . " " . $this->arStoredMembers[$data["USER_ID"]]["FIRST_NAME"]
                . " " . $this->arStoredMembers[$data["USER_ID"]]["LAST_NAME"]
                . " " . $this->arStoredMembers[$data["USER_ID"]]["USERNAME"]
                . " invited"
                . " " . $this->arStoredMembers[$data["NEW_MEMBER"]]["FIRST_NAME"]
                . " " . $this->arStoredMembers[$data["NEW_MEMBER"]]["LAST_NAME"]
                . " " . $this->arStoredMembers[$data["NEW_MEMBER"]]["USERNAME"]
                . PHP_EOL;
        }

        return $msg;
    }

    public function getTicketsReport() {
        $msg = "";
        foreach ($this->arTickets as $number => $userId) {
            $msg .= $this->arStoredMembers[$userId]["FIRST_NAME"]
                . " " . $this->arStoredMembers[$userId]["LAST_NAME"]
                . " " . $this->arStoredMembers[$userId]["USERNAME"]
                . " ticket number " . $number
                . PHP_EOL;
        }

        return $msg;
    }

    public function membersToExcel($objPHPExcel) {
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', 'USER_ID')
            ->setCellValue('B1', 'IS_BOT')
            ->setCellValue('C1', 'FIRST_NAME')
            ->setCellValue('D1', 'LAST_NAME')
            ->setCellValue('E1', 'USERNAME');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $i = 3;
        foreach ($this->arStoredMembers as $member) {
            $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", $member["ID"]);
            $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", $member["IS_BOT"]);
            $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", $member["FIRST_NAME"]);
            $objPHPExcel->getActiveSheet()->setCellValue("D{$i}", $member["LAST_NAME"]);
            $objPHPExcel->getActiveSheet()->setCellValue("E{$i}", $member["USERNAME"]);
            $i++;
        }

    }

    public function ticketsToExcel($objPHPExcel) {
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(2);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'NUMBER_TICKET')
            ->setCellValue('B1', 'USER_ID');

        $i = 3;
        foreach ($this->arTickets as $ticketNumber => $userId) {
            $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", $ticketNumber);
            $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", $userId);
            $i++;
        }
    }

    public function processToExcel($objPHPExcel) {
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'UPDATE_ID')
            ->setCellValue('B1', 'DATE')
            ->setCellValue('C1', 'USER_ID')
            ->setCellValue('D1', 'TICKET_ID')
            ->setCellValue('E1', 'NEW_MEMBER');

        $i = 3;
        foreach ($this->arProcessData as $data) {
            $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", $data["UPDATE_ID"]);
            $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", date("d-m-Y H:i:s", $data["DATE"]));
            $objPHPExcel->getActiveSheet()->setCellValue("C{$i}", $data["USER_ID"]);
            $objPHPExcel->getActiveSheet()->setCellValue("D{$i}", $data["TICKET_ID"]);
            $objPHPExcel->getActiveSheet()->setCellValue("E{$i}", $data["NEW_MEMBER"]);
            $i++;
        }
    }

    /**
     * Вернуть первого приглашённого при выдаче билета за 2-го
     */
    public function getFirstInvitedMember($userId) {
        $arProcessData = $this->arProcessData;
        ksort($arProcessData); // по времени добавления
        foreach ($arProcessData as $element) {
            // приглашение этого пользователя и номер билета отсутствует
            if ($element["USER_ID"] == $userId && $element["TICKET_ID"] == 0) {
                return $element;
            }
        }

        return false;
    }

    public function reportToExcel($objPHPExcel) {
        $arProcessData = $this->arProcessData;
        $arTickets = [];  // выданные билеты
        foreach ($arProcessData as $data) {
            if ($data["TICKET_ID"] == 0) continue;
            $arTickets[] = $data["TICKET_ID"]; // все выданные номера билетов

        }
        $arTickets = array_unique($arTickets);
        sort($arTickets);

        $arReport = [];
        foreach ($arTickets as $ticket) {
            $rows = [];
            foreach ($arProcessData as $data) {
                if ($data["TICKET_ID"] == $ticket) {
                    $rows[] = [
                        "TICKET_ID" => $data["TICKET_ID"],
                        "FROM"      => $this->arStoredMembers[$data["USER_ID"]]["FIRST_NAME"] . " " . $this->arStoredMembers[$data["USER_ID"]]["LAST_NAME"] . " " . $this->arStoredMembers[$data["USER_ID"]]["USERNAME"] . " (" . $data["USER_ID"] . ")",
                        "DATE"      => date("d-m-Y H:i:s", $data["DATE"]),
                        "NEW"       => $this->arStoredMembers[$data["NEW_MEMBER"]]["FIRST_NAME"] . " " . $this->arStoredMembers[$data["NEW_MEMBER"]]["LAST_NAME"] . " " . $this->arStoredMembers[$data["NEW_MEMBER"]]["USERNAME"] . " (" . $data["NEW_MEMBER"] . ")"
                    ];
                    $key = $data["UPDATE_ID"] . '_' . $data["DATE"] . "_" . $data["USER_ID"] . "_" . $data["NEW_MEMBER"];
                    unset($arProcessData[$key]); // убираем уже обработанное
                } else {
                    continue;
                }
            }
            $arReport[$ticket] = $rows;
        }

        // итоговый отчёт
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()
            ->setCellValue('A1', 'Билет')
            ->setCellValue('B1', 'Пригласивший')
            ->setCellValue('C1', 'Дата')
            ->setCellValue('D1', 'Приглашённый');

        $i = 3;
        foreach ($arReport as $ticket => $data) {
            $objPHPExcel->getActiveSheet()->setCellValue("A{$i}", $ticket);
            $objPHPExcel->getActiveSheet()->setCellValue("B{$i}", $data[0]["FROM"]);
            $j = 0;
            foreach ($data as $info) {
                $z = $i + $j;
                $objPHPExcel->getActiveSheet()->setCellValue("C{$z}", $info["DATE"]);
                $objPHPExcel->getActiveSheet()->setCellValue("D{$z}", $info["NEW"]);
                $j++;
            }
            $i = $i + $j + 1;
        }
    }

    public function getChanceOfWinning($memberCnt = 0) {
        $numberTickets = count($this->arTickets); // всего присвоенных билетов
        $arMemberTicketCnt = array_count_values($this->arTickets); // количество билетов каждого участника

        arsort($arMemberTicketCnt); // по количеству билетов
        if ($memberCnt > 0) { // если задано количество участников для показа
            $arMemberTicketCnt = array_slice($arMemberTicketCnt, 0, $memberCnt, true);
        }

//        $result = [];
        $msg = "<strong>ТОП $memberCnt участников с максимальной вероятностью выигрыша</strong>" . PHP_EOL;
        foreach ($arMemberTicketCnt as $memberId => $cnt) {
            /*
            $result[$memberId] = [
                "FIRST_NAME" => $this->arStoredMembers[$memberId]["FIRST_NAME"],
                "LAST_NAME"  => $this->arStoredMembers[$memberId]["LAST_NAME"],
                "USERNAME"   => $this->arStoredMembers[$memberId]["USERNAME"],
                "TICKETS"    => $cnt,
                "CHANCE"     => round($cnt / $numberTickets * 100, 2)
            ];
            */
            $msg .= $this->arStoredMembers[$memberId]["FIRST_NAME"]
                . (!empty($this->arStoredMembers[$memberId]["LAST_NAME"]) ? " " . $this->arStoredMembers[$memberId]["LAST_NAME"] : "")
//                . " (" . $memberId . ")"
//                . " — билетов " . $cnt . ", "
                . " вероятность победы — " . round($cnt / $numberTickets * 100, 2) . "%" . PHP_EOL;
        }

        return $msg;
    }

    public function updateMemberTicketsCnt() {
        $this->arMemberTicketsCnt = array_count_values($this->arTickets); // количество билетов каждого участника

        arsort($this->arMemberTicketsCnt); // по количеству билетов
    }
}