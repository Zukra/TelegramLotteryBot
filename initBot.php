<?php
/**
 * Telegram Lottery Bot
 */

use zkr\classes\Lotto;
use zkr\classes\TelegramBot;

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Moscow');

$fileCfg = __DIR__ . '/config.php';
$lastUpdateCfg = filemtime($fileCfg);

require 'vendor/autoload.php';
$config = require $fileCfg;

$token = require_once __DIR__ . '/token.php';

$starTime = time();  // время запуска
$timeForMoreSend = $starTime + $config["intervalSecondSec"]; // время отправки шансов
$timeForChannelSend = $starTime + $config["channel"]["interval"]; // время отправки in channel

$bot = new TelegramBot($token);
$lotto = new Lotto();

while (true) {
    $currentTime = time();
    $isChange = false;  // if something changes
    $isCommandTop = false; // TOP command was launched

    // update config.cfg with out restart script
    if (file_exists($fileCfg)) {
        clearstatcache();
        $lastUpdate = filemtime($fileCfg);
        if ($lastUpdate != $lastUpdateCfg) {
            $config = require $fileCfg;
            $lastUpdateCfg = $lastUpdate;
        }
    } else {
        exit();
    }

    $urlRules = "<a href=\"{$config['urlRules']}\">Правила конкурса</a>";

    $response = $bot->getUpdatesOffset($config['lastMessages']); // получаем последние сообщений
    /*
        $data = include 'data/test_data.php';  // тестовые данные
        $response = json_decode($data, true);
    */

    if ($response && $response["ok"]) {
        // get admins from all chats
        $arAdministrators = [];
        foreach ($config['arChatId'] as $chatId) {
            $arTmp = $bot->getChatAdministrators($chatId) ?: []; // get chat admins
            $arAdministrators = array_merge($arAdministrators, $arTmp);
            unset($arTmp);
        }
        $arAdminsIds = array_unique($bot->getAdminsIds($arAdministrators));

        // run bot command
        foreach ($response["result"] as $result) {
            $result["message"] = isset($result["message"]) ? $result["message"] : $result["edited_message"];
            // команда для бота и от админа
            if (isset($result["message"]['entities'])
                && $result["message"]['entities'][0]['type'] == 'bot_command'
                && in_array($result["message"]["from"]["id"], $arAdminsIds)) {
                $updateId = $bot->getUpdateId() ?: 0;
                // если эта комманда ещё не была выполнена - выполняем и записываем в файл её update_id
                if ($updateId < $result['update_id']) {
                    switch ($result["message"]["text"]) {
                        case "/stop":
                        case "/stop@{$bot->info['username']}":
                            $bot->sendMessageToChats($result["message"]['chat']['id'], ["text" => "The bot is stopped.", "parse_mode" => "HTML", "disable_web_page_preview" => true, "disable_notification" => true]);
                            $bot->saveUpdateId($result['update_id']);
                            exit();
                            break;
                        case "/show" :
                        case "/show@{$bot->info['username']}":
                            $bot->sendMessageToChats($result["message"]['chat']['id'], ["text" => "testing command " . $result["message"]["text"], "parse_mode" => "HTML", "disable_web_page_preview" => true, "disable_notification" => true]);
                            $bot->saveUpdateId($result['update_id']);
                            break;
                        case "/top" :
                        case "/top@{$bot->info['username']}":
                            $bot->saveUpdateId($result['update_id']);
                            $isCommandTop = true;
                            break;
                    }
                }
            }
        }

        // general
        $lotto->arTickets = $lotto->getTickets() ?: []; // get saved выданные билеты
        $ticketCount = count($lotto->arTickets);
        ksort($lotto->arTickets);
        $last = end($lotto->arTickets);  // последний билет
        $ticketNumber = $last ? (key($lotto->arTickets) + 1) : 1; // +1 т.к. нумерация с 0, а 0-го номера билета нету

        $lotto->arStoredMembers = $lotto->getMembers() ?: []; // get all stored members
        $storeMemberCount = count($lotto->arStoredMembers);

        $lotto->arProcessData = $lotto->getProcessData() ?: []; // get saved основные данные
        $processDataCount = count($lotto->arProcessData);

        $lotto->arNewMembers = $lotto->getAddedMembers($response["result"], $config['arChatId']); // новые пользователи в чате

        foreach ($lotto->arNewMembers as $user) {
            // если обновился статус
//            if (empty($user["FROM"]["STATUS_IN_CHANNEL"])) {
//                $isChange = true;
//                $user["FROM"]["STATUS_IN_CHANNEL"] = $bot->inChannel($user["FROM"]["ID"], $config['channel']['name']);  // check user status in channel
//            }
            // если приглашающий отсутствует - добавляем в массив пользователей
            if (!isset($lotto->arStoredMembers[$user["FROM"]["ID"]])) {
                $lotto->arStoredMembers[$user["FROM"]["ID"]] = $user["FROM"];  // add new member
            }
            // если это новый приглашённый пользовватель - добавляем, записываем данные и выдаём билет
            foreach ($user["NEW_MEMBER"] as $newMember) {
                if (!isset($lotto->arStoredMembers[$newMember["ID"]])) {
                    // если обновился статус
//                    if (empty($newMember["STATUS_IN_CHANNEL"])) {
//                        $isChange = true;
//                        $newMember["STATUS_IN_CHANNEL"] = $bot->inChannel($newMember["ID"], $config['channel']['name']);  // check user status in channel
//                    }
                    $lotto->arStoredMembers[$newMember["ID"]] = $newMember; // add new member

                    $key = $user["UPDATE_ID"] . '_' . $user["DATE"] . "_" . $user["FROM"]["ID"] . "_" . $newMember["ID"];
                    $lotto->arProcessData[$key] = [
                        "UPDATE_ID"  => $user["UPDATE_ID"],
                        "DATE"       => $user["DATE"],
                        "USER_ID"    => $user["FROM"]["ID"],
                        "TICKET_ID"  => 0,
                        "NEW_MEMBER" => $newMember["ID"]
                    ];

                    $numberInvitedMembers = $lotto->getNumberInvitedMembers($user["FROM"]["ID"]);
                    // если приглашённых больше определённого количества
                    if (($numberInvitedMembers % 2) == 0) { // чётное количество приглашённых - выдаём билет
                        // добавляем новый номер билетика и запоминаем, кому выдали
                        $lotto->arTickets[$ticketNumber] = $user["FROM"]["ID"];

                        $lotto->updateMemberTicketsCnt(); // обновить количество билетов у пользователя

                        $lotto->arProcessData[$key]["TICKET_ID"] = $ticketNumber;

                        // 1-й приглашённый по билету
                        $firstInvitedMember = $lotto->getFirstInvitedMember($user["FROM"]["ID"]);
                        // присваиваем ему номер билета
                        $firstInvitedMember["TICKET_ID"] = $ticketNumber;

                        // запоминаем данные
                        $firstInvitedMemberKey = $firstInvitedMember["UPDATE_ID"] . '_' . $firstInvitedMember["DATE"] . "_" . $firstInvitedMember["USER_ID"] . "_" . $firstInvitedMember["NEW_MEMBER"];
                        $lotto->arProcessData[$firstInvitedMemberKey] = $firstInvitedMember;

                        $msg = $user["FROM"]["FIRST_NAME"]
                            . (!empty($user["FROM"]["LAST_NAME"]) ? " " . $user["FROM"]["LAST_NAME"] : "")
//                            . " status in " . $config['channel']['name'] . " - " . ($user["FROM"]["STATUS_IN_CHANNEL"] ?: "unregistered") . PHP_EOL
//                            . " " . $user["FROM"]["USERNAME"] . " (" . $user["FROM"]["ID"] . ")"
                            . " — присвоен билет №" . $ticketNumber
                            . " за приглашение " . $lotto->arStoredMembers[$firstInvitedMember["NEW_MEMBER"]]["FIRST_NAME"]
                            . (!empty($lotto->arStoredMembers[$firstInvitedMember["NEW_MEMBER"]]["LAST_NAME"]) ? " " . $lotto->arStoredMembers[$firstInvitedMember["NEW_MEMBER"]]["LAST_NAME"] : "")
//                            . " status in " . $config['channel']['name'] . " - " . ($lotto->arStoredMembers[$firstInvitedMember["NEW_MEMBER"]]["STATUS_IN_CHANNEL"] ?: "unregistered") . PHP_EOL
//                            . " " . $lotto->arStoredMembers[$firstInvitedMember["NEW_MEMBER"]]["USERNAME"] . " (" . $lotto->arStoredMembers[$firstInvitedMember["NEW_MEMBER"]]["ID"] . ")"
                            . " и " . $newMember["FIRST_NAME"]
                            . (!empty($newMember["LAST_NAME"]) ? " " . $newMember["LAST_NAME"] : "")
//                            . " status in " . $config['channel']['name'] . " - " . ($newMember["STATUS_IN_CHANNEL"] ?: "unregistered")
//                            . " " . $newMember["USERNAME"] . " (" . $newMember["ID"] . ")"
                            . PHP_EOL
                            . "Вероятность победы — " . round($lotto->arMemberTicketsCnt[$user["FROM"]["ID"]] / count($lotto->arTickets) * 100, 2) . "%"
                            . PHP_EOL . $urlRules;
//                            . PHP_EOL . PHP_EOL . "Спонсор - <a href=\"https://t.me/sendnewcoin\">New coins Hunter</a>"
//                            . "[Текущие результаты]({$config['urlXlsx']})";

                        $bot->sendMessageToChats($config['arChatId'], ["text" => $msg, "parse_mode" => "HTML", "disable_web_page_preview" => true, "disable_notification" => true]);

                        $ticketNumber++;
                    }
                }
            }
        }

        if ($isChange || $storeMemberCount != count($lotto->arStoredMembers)) { // если были новые пользователи
            $lotto->saveData($lotto->arStoredMembers, "members"); // сохранить новых пользователей
            $isChange = true;
        }
        if ($ticketCount != count($lotto->arTickets)) {
            $lotto->saveData($lotto->arTickets, "tickets"); // сохранить билеты
            $isChange = true;
        }
        if ($processDataCount != count($lotto->arProcessData)) {
            $lotto->saveData($lotto->arProcessData, "process"); // сохранить данные
            $isChange = true;
        }

        // вывод ТОП 10
        if ($config['showTop']) {
            $isTimeShowTop = false;  // настало ли время вывода
            // задано конкретное время вывода
            if (count($config['arSpecificTime']) > 0) {
                foreach ($config['arSpecificTime'] as $item) {
                    if ($currentTime >= strtotime($item) && $currentTime <= (strtotime($item) + $config['intervalSec'] + 3)) {
                        $isTimeShowTop = true;
                        break;
                    }
                }
                // иначе, выводим по заданному интервалу
            } elseif ($currentTime >= $timeForMoreSend) {
                $isTimeShowTop = true;
                $timeForMoreSend = $currentTime + $config['intervalSecondSec'];
            }
            // введена команда top или время выводить
            if ($isCommandTop || $isTimeShowTop) {
                $chanceText = $lotto->getChanceOfWinning($config['memberCnt'])
                    . $urlRules;
                $bot->sendMessageToChats($config['arChatId'], ["text" => $chanceText, "parse_mode" => "HTML", "disable_web_page_preview" => true, "disable_notification" => true]);
            }
        }

        /*
        // output to channel
        if (time() >= $timeForChannelSend) {
            $chanceText = $lotto->getChanceOfWinning($config['memberCnt']);
            $bot->sendMessageToChats($config['channel']['id'], ["text" => $chanceText, "parse_mode" => "HTML", "disable_web_page_preview" => true, "disable_notification" => true]);
            $timeForChannelSend = time() + $config['channel']['interval'];
        }
        */

        if ($config['xmlReports'] && $isChange) {
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();

            $lotto->reportToExcel($objPHPExcel);

            // Save Excel 2007 file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(__DIR__ . '/data/results.xlsx');

            $objPHPExcel->disconnectWorksheets();
            unset($objPHPExcel);

            // Create new PHPExcelProcess object
            $objPHPExcelProcess = new PHPExcel();

            $lotto->processToExcel($objPHPExcelProcess);

            $lotto->membersToExcel($objPHPExcelProcess);

            $lotto->ticketsToExcel($objPHPExcelProcess);

            $objPHPExcelProcess->setActiveSheetIndex(0);

            // Save Excel 2007 file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelProcess, 'Excel2007');
            $objWriter->save(__DIR__ . '/data/process.xlsx');

            $objPHPExcelProcess->disconnectWorksheets();
            unset($objPHPExcelProcess);
        }
    }

//    exit();
    sleep($config['intervalSec']);
}