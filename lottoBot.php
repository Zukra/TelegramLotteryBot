<?php
/**
 * Metal.Place Lottery Bot
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Moscow');

require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

use zkr\classes\Lotto;
use zkr\classes\TelegramBot;

$token = require_once __DIR__ . '/token.php';


$data = include __DIR__ . '/data/test_data.php';  // тестовые данные
$response = json_decode($data, true);


$starTime = time();  // время запуска
$timeForMoreSend = $starTime + $intervalSecondSec; // время отправки шансов

$bot = new TelegramBot($token);
$lotto = new Lotto();

$fileCfg = __DIR__ . '/config.php';
$lastUpdateCfg = filemtime($fileCfg);

//$bot->pinChatMessage($arChatId[0], 17);

while (true) {
    // update config.cfg with out restart script
    if (file_exists($fileCfg)) {
        $lastUpdate = filemtime($fileCfg);
        clearstatcache();
        if ($lastUpdate != $lastUpdateCfg) {
            require __DIR__ . '/config.php';
        }
    } else {
        exit();
    }

//    $response = $bot->getUpdatesOffset(30); // получаем последние 30 сообщений
    if ($response && $response["ok"]) {
        $lotto->arTickets = $lotto->getTickets() ?: []; // получить выданные билеты
        $ticketCount = count($lotto->arTickets);
        ksort($lotto->arTickets);
        $last = end($lotto->arTickets);  // последний билет
        $ticketNumber = $last ? (key($lotto->arTickets) + 1) : 1; // +1 т.к. нумерация с 0, а 0-го номера билета нету

        $lotto->arStoredMembers = $lotto->getMembers() ?: []; // get all stored members
        $storeMemberCount = count($lotto->arStoredMembers);

        $lotto->arNewMembers = $lotto->getAddedMembers($response["result"], $arChatId); // новые пользователи в чате

        $lotto->arProcessData = $lotto->getProcessData() ?: []; // основные данные
        $processDataCount = count($lotto->arProcessData);

        foreach ($lotto->arNewMembers as $user) {
            // если приглашающий отсутствует - добавляем в массив пользователей
            if (!isset($lotto->arStoredMembers[$user["FROM"]["ID"]])) {
                $lotto->arStoredMembers[$user["FROM"]["ID"]] = $user["FROM"];  // add new member
            }
            // если это новый приглашённый пользовватель - добавляем, записываем данные и выдаём билет
            foreach ($user["NEW_MEMBER"] as $newMember) {
                if (!isset($lotto->arStoredMembers[$newMember["ID"]])) {
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

                        $msg = $user["FROM"]["FIRST_NAME"] . " " . $user["FROM"]["LAST_NAME"]
//                            . " " . $user["FROM"]["USERNAME"]
                            . " (" . $user["FROM"]["ID"] . ")" . " - присвоен билет № " . $ticketNumber
                            . PHP_EOL
                            . "за приглашение " . $lotto->arStoredMembers[$firstInvitedMember["NEW_MEMBER"]]["FIRST_NAME"]
                            . " " . $lotto->arStoredMembers[$firstInvitedMember["NEW_MEMBER"]]["LAST_NAME"]
//                            . " " . $lotto->arStoredMembers[$firstInvitedMember["NEW_MEMBER"]]["USERNAME"]
                            . " (" . $lotto->arStoredMembers[$firstInvitedMember["NEW_MEMBER"]]["ID"] . ")"
                            . " и " . $newMember["FIRST_NAME"] . " " . $newMember["LAST_NAME"]
                            . " " . $newMember["USERNAME"]
                            . " (" . $newMember["ID"] . ")"
                            . PHP_EOL
                            . "текуший % выигрыша " . round($lotto->arMemberTicketsCnt[$user["FROM"]["ID"]] / count($lotto->arTickets) * 100, 2)
                            . PHP_EOL
                            . "<a href=\"{$urlRules}\">Правила конкурса</a>";
//                        . "[Текущие результаты]({$urlXlsx})";

                        $bot->sendMessageToChats($arChatId, ["text" => $msg, "parse_mode" => "HTML", "disable_web_page_preview" => true, "disable_notification" => true]);

                        $ticketNumber++;
                    }
                }
            }
        }

        $isChange = false;
        if ($storeMemberCount != count($lotto->arStoredMembers)) { // если были новые пользователи
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


        if (time() >= $timeForMoreSend) {
            $chanceText = $lotto->getChanceOfWinning($memberCnt);
            $bot->sendMessageToChats($arChatId, ["text" => $chanceText, "parse_mode" => "HTML", "disable_web_page_preview" => true, "disable_notification" => true]);
            $timeForMoreSend = time() + $intervalSecondSec;
        }

        if ($isChange) {
            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();

            $lotto->reportToExcel($objPHPExcel);

            // Save Excel 2007 file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save(__DIR__ . '/data/results.xlsx');

            // Create new PHPExcelProcess object
            $objPHPExcelProcess = new PHPExcel();

            $lotto->processToExcel($objPHPExcelProcess);

            $lotto->membersToExcel($objPHPExcelProcess);

            $lotto->ticketsToExcel($objPHPExcelProcess);

            $objPHPExcelProcess->setActiveSheetIndex(0);

            // Save Excel 2007 file
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelProcess, 'Excel2007');
            $objWriter->save(__DIR__ . '/data/process.xlsx');
        }
    }

//    exit();

    sleep($intervalSec);
}