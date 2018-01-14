<?php

return [
    'arChatId'        => [
        -1001283156137,   // @shownewcoin  (test chat)
//        -1001336939248,   // Test group https://t.me/test_loto_bot
//        -1001302351956,   // Crypto Lottery Chat group https://t.me/test_loto_bot
//        -1001085505193,   // @metalplacechat
    ],
    'arMembersStatus' => ["creator", "administrator", "member", "restricted"],
    'channel'         => [
        "name"     => "@shownewcoin",  // канал, в котором д.б. зарегистрирован участник
        "id"       => [-1001325237083],
        "interval" => 30, // sec
    ],
//    'urlRules'        => "https://t.me/cryptolotterychat/8",    // a link to the message in chat
    'urlRules'        => "https://t.me/metalplacechat/1457",  // a link to the message in chat

//'urlXlsx' => "https://metal.place/lottery/data/results.xlsx",

    'lastMessages'      => 30, // последние 30 сообщений
    'intervalSec'       => 10, // sec
    'intervalSecondSec' => 15, //60 * 60 * 2, // 10 min

    'xmlReports' => false, // create xml-files
    'memberCnt'  => 10 // сколько первых участников показывать с шансом выигрыша
];