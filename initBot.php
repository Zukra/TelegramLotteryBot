<?php
/**
 * Telegram lottery bot
 * https://api.telegram.org/bot480191787:AAGIhoCjqjD6Rm3zK9SIAbUzQLfCJeRt5M8/getUpdates
 *
 * 480191787        @emk_lotto_bot "EMK lottery"
 * 412846761        (My)
 * -1001283156137   Test group
 */

require_once __DIR__ . '/vendor/autoload.php';

use zkr\classes\Member;
use zkr\classes\TelegramBot;

$data = '{"ok":true,"result":[{"update_id":196958665,
"message":{"message_id":451,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":412846761,"first_name":"Nikolay","last_name":"Litvinovich","type":"private"},"date":1512733347,"text":"test"}},{"update_id":196958666,
"message":{"message_id":452,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-218487457,"title":"TestSendCoin","type":"group","all_members_are_administrators":true},"date":1512733685,"text":"/test @ForTestCurrencyBot","entities":[{"offset":0,"length":5,"type":"bot_command"},{"offset":6,"length":19,"type":"mention"}]}},{"update_id":196958667,
"edited_message":{"message_id":452,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-218487457,"title":"TestSendCoin","type":"group","all_members_are_administrators":true},"date":1512733685,"edit_date":1512733712,"text":"11111test @ForTestCurrencyBot","entities":[{"offset":10,"length":19,"type":"mention"}]}},{"update_id":196958668,
"message":{"message_id":453,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-218487457,"title":"TestSendCoin","type":"group","all_members_are_administrators":true},"date":1512733965,"new_chat_participant":{"id":264667499,"is_bot":false,"first_name":"Anastasia","last_name":"Saldatava"},"new_chat_member":{"id":264667499,"is_bot":false,"first_name":"Anastasia","last_name":"Saldatava"},"new_chat_members":[{"id":264667499,"is_bot":false,"first_name":"Anastasia","last_name":"Saldatava"}]}},{"update_id":196958669,
"message":{"message_id":454,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-218487457,"title":"TestSendCoin","type":"group","all_members_are_administrators":true},"date":1512734175,"migrate_to_chat_id":-1001283156137}},{"update_id":196958670,
"message":{"message_id":1,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734175,"migrate_from_chat_id":-218487457}},{"update_id":196958671,
"message":{"message_id":455,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":412846761,"first_name":"Nikolay","last_name":"Litvinovich","type":"private"},"date":1512734623,"text":"1234"}},{"update_id":196958672,
"message":{"message_id":2,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734325,"text":"\u041e\u043f"}},{"update_id":196958673,
"message":{"message_id":3,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734329,"text":"\u0425\u043b\u043e\u043f"}},{"update_id":196958674,
"message":{"message_id":4,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734415,"text":"qwer"}},{"update_id":196958675,
"message":{"message_id":5,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734436,"text":"ddff @ForTestCurrencyBot","entities":[{"offset":5,"length":19,"type":"mention"}]}},{"update_id":196958676,
"message":{"message_id":6,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734481,"text":"wddf @ForTestCurrencyBot","entities":[{"offset":5,"length":19,"type":"mention"}]}},{"update_id":196958677,
"message":{"message_id":7,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734594,"text":"Hhh"}},{"update_id":196958678,
"message":{"message_id":8,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734667,"text":"Ggg"}},{"update_id":196958679,
"message":{"message_id":9,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734691,"text":"test"}},{"update_id":196958680,
"message":{"message_id":10,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734846,"new_chat_participant":{"id":256676752,"is_bot":false,"first_name":"Maksim"},"new_chat_member":{"id":256676752,"is_bot":false,"first_name":"Maksim"},"new_chat_members":[{"id":256676752,"is_bot":false,"first_name":"Maksim"}]}},{"update_id":196958681,
"message":{"message_id":11,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512734946,"new_chat_participant":{"id":363174975,"is_bot":false,"first_name":"\u0410\u043d\u0430\u0441\u0442\u0430\u0441\u0438\u044f","last_name":"\u0424\u0438\u043b\u0438\u043f\u043f\u043e\u0432\u0430"},"new_chat_member":{"id":363174975,"is_bot":false,"first_name":"\u0410\u043d\u0430\u0441\u0442\u0430\u0441\u0438\u044f","last_name":"\u0424\u0438\u043b\u0438\u043f\u043f\u043e\u0432\u0430"},"new_chat_members":[{"id":363174975,"is_bot":false,"first_name":"\u0410\u043d\u0430\u0441\u0442\u0430\u0441\u0438\u044f","last_name":"\u0424\u0438\u043b\u0438\u043f\u043f\u043e\u0432\u0430"}]}},{"update_id":196958682,
"message":{"message_id":12,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512735797,"new_chat_participant":{"id":426264513,"is_bot":false,"first_name":"Olesya","last_name":"Tyurina"},"new_chat_member":{"id":426264513,"is_bot":false,"first_name":"Olesya","last_name":"Tyurina"},"new_chat_members":[{"id":426264513,"is_bot":false,"first_name":"Olesya","last_name":"Tyurina"}]}},{"update_id":196958683,
"message":{"message_id":13,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512735828,"new_chat_participant":{"id":319120669,"is_bot":false,"first_name":"Hero","last_name":"Park"},"new_chat_member":{"id":319120669,"is_bot":false,"first_name":"Hero","last_name":"Park"},"new_chat_members":[{"id":319120669,"is_bot":false,"first_name":"Hero","last_name":"Park"}]}},{"update_id":196958684,
"message":{"message_id":14,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512735835,"new_chat_participant":{"id":459325647,"is_bot":false,"first_name":"Yury"},"new_chat_member":{"id":459325647,"is_bot":false,"first_name":"Yury"},"new_chat_members":[{"id":459325647,"is_bot":false,"first_name":"Yury"}]}},{"update_id":196958685,
"message":{"message_id":15,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512735840,"new_chat_participant":{"id":437776757,"is_bot":false,"first_name":"Makeda","last_name":"The Queen of Sheba"},"new_chat_member":{"id":437776757,"is_bot":false,"first_name":"Makeda","last_name":"The Queen of Sheba"},"new_chat_members":[{"id":437776757,"is_bot":false,"first_name":"Makeda","last_name":"The Queen of Sheba"}]}},{"update_id":196958686,
"message":{"message_id":16,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512735849,"new_chat_participant":{"id":202925143,"is_bot":false,"first_name":"Adolf"},"new_chat_member":{"id":202925143,"is_bot":false,"first_name":"Adolf"},"new_chat_members":[{"id":202925143,"is_bot":false,"first_name":"Adolf"}]}},{"update_id":196958687,
"message":{"message_id":17,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512735858,"new_chat_participant":{"id":284352172,"is_bot":false,"first_name":"Alexey","last_name":"Rudynsky","username":"alexeyrudynsky"},"new_chat_member":{"id":284352172,"is_bot":false,"first_name":"Alexey","last_name":"Rudynsky","username":"alexeyrudynsky"},"new_chat_members":[{"id":284352172,"is_bot":false,"first_name":"Alexey","last_name":"Rudynsky","username":"alexeyrudynsky"}]}},{"update_id":196958688,
"message":{"message_id":18,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512735882,"new_chat_participant":{"id":391533920,"is_bot":false,"first_name":"Metal.Place","last_name":"\u041f\u043e\u0434\u0434\u0435\u0440\u0436\u043a\u0430","username":"MetalPlaceHelper"},"new_chat_member":{"id":391533920,"is_bot":false,"first_name":"Metal.Place","last_name":"\u041f\u043e\u0434\u0434\u0435\u0440\u0436\u043a\u0430","username":"MetalPlaceHelper"},"new_chat_members":[{"id":391533920,"is_bot":false,"first_name":"Metal.Place","last_name":"\u041f\u043e\u0434\u0434\u0435\u0440\u0436\u043a\u0430","username":"MetalPlaceHelper"}]}},{"update_id":196958689,
"message":{"message_id":19,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736185,"left_chat_participant":{"id":256676752,"is_bot":false,"first_name":"Maksim"},"left_chat_member":{"id":256676752,"is_bot":false,"first_name":"Maksim"}}},{"update_id":196958690,
"message":{"message_id":20,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736189,"left_chat_participant":{"id":271421427,"is_bot":false,"first_name":"\u041d\u0430\u0434\u0435\u0436\u0434\u0430","last_name":"\u042e\u0440\u043a\u043e\u0432\u0435\u0446"},"left_chat_member":{"id":271421427,"is_bot":false,"first_name":"\u041d\u0430\u0434\u0435\u0436\u0434\u0430","last_name":"\u042e\u0440\u043a\u043e\u0432\u0435\u0446"}}},{"update_id":196958691,
"message":{"message_id":21,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736214,"left_chat_participant":{"id":201780247,"is_bot":false,"first_name":"Ludmila","last_name":"Batura"},"left_chat_member":{"id":201780247,"is_bot":false,"first_name":"Ludmila","last_name":"Batura"}}},{"update_id":196958692,
"message":{"message_id":22,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736217,"left_chat_participant":{"id":264029369,"is_bot":false,"first_name":"\u041e\u043b\u044c\u0433\u0430","last_name":"\u041b\u0438\u043f\u0430\u0442\u043e\u0432\u0430","username":"Devoja"},"left_chat_member":{"id":264029369,"is_bot":false,"first_name":"\u041e\u043b\u044c\u0433\u0430","last_name":"\u041b\u0438\u043f\u0430\u0442\u043e\u0432\u0430","username":"Devoja"}}},{"update_id":196958693,
"message":{"message_id":23,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736220,"left_chat_participant":{"id":363174975,"is_bot":false,"first_name":"\u0410\u043d\u0430\u0441\u0442\u0430\u0441\u0438\u044f","last_name":"\u0424\u0438\u043b\u0438\u043f\u043f\u043e\u0432\u0430"},"left_chat_member":{"id":363174975,"is_bot":false,"first_name":"\u0410\u043d\u0430\u0441\u0442\u0430\u0441\u0438\u044f","last_name":"\u0424\u0438\u043b\u0438\u043f\u043f\u043e\u0432\u0430"}}},{"update_id":196958694,
"message":{"message_id":24,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736224,"left_chat_participant":{"id":426264513,"is_bot":false,"first_name":"Olesya","last_name":"Tyurina"},"left_chat_member":{"id":426264513,"is_bot":false,"first_name":"Olesya","last_name":"Tyurina"}}},{"update_id":196958695,
"message":{"message_id":25,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736229,"left_chat_participant":{"id":391533920,"is_bot":false,"first_name":"Metal.Place","last_name":"\u041f\u043e\u0434\u0434\u0435\u0440\u0436\u043a\u0430","username":"MetalPlaceHelper"},"left_chat_member":{"id":391533920,"is_bot":false,"first_name":"Metal.Place","last_name":"\u041f\u043e\u0434\u0434\u0435\u0440\u0436\u043a\u0430","username":"MetalPlaceHelper"}}},{"update_id":196958696,
"message":{"message_id":26,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736232,"left_chat_participant":{"id":284352172,"is_bot":false,"first_name":"Alexey","last_name":"Rudynsky","username":"alexeyrudynsky"},"left_chat_member":{"id":284352172,"is_bot":false,"first_name":"Alexey","last_name":"Rudynsky","username":"alexeyrudynsky"}}},{"update_id":196958697,
"message":{"message_id":27,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736234,"left_chat_participant":{"id":202925143,"is_bot":false,"first_name":"Adolf"},"left_chat_member":{"id":202925143,"is_bot":false,"first_name":"Adolf"}}},{"update_id":196958698,
"message":{"message_id":28,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736239,"left_chat_participant":{"id":463578390,"is_bot":false,"first_name":"David","last_name":"Mora","username":"davmora"},"left_chat_member":{"id":463578390,"is_bot":false,"first_name":"David","last_name":"Mora","username":"davmora"}}},{"update_id":196958699,
"message":{"message_id":29,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736246,"left_chat_participant":{"id":459325647,"is_bot":false,"first_name":"Yury"},"left_chat_member":{"id":459325647,"is_bot":false,"first_name":"Yury"}}},{"update_id":196958700,
"message":{"message_id":30,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736249,"left_chat_participant":{"id":437776757,"is_bot":false,"first_name":"Makeda","last_name":"The Queen of Sheba"},"left_chat_member":{"id":437776757,"is_bot":false,"first_name":"Makeda","last_name":"The Queen of Sheba"}}},{"update_id":196958701,
"message":{"message_id":31,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512736253,"left_chat_participant":{"id":319120669,"is_bot":false,"first_name":"Hero","last_name":"Park"},"left_chat_member":{"id":319120669,"is_bot":false,"first_name":"Hero","last_name":"Park"}}},{"update_id":196958702,
"message":{"message_id":32,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512737816,"new_chat_participant":{"id":176430224,"is_bot":true,"first_name":"RandomBot","username":"rgen_bot"},"new_chat_member":{"id":176430224,"is_bot":true,"first_name":"RandomBot","username":"rgen_bot"},"new_chat_members":[{"id":176430224,"is_bot":true,"first_name":"RandomBot","username":"rgen_bot"}]}},{"update_id":196958703,
"message":{"message_id":33,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512737830,"text":"/start@rgen_bot","entities":[{"offset":0,"length":15,"type":"bot_command"}]}},{"update_id":196958704,
"message":{"message_id":35,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512737835,"text":"/help@rgen_bot","entities":[{"offset":0,"length":14,"type":"bot_command"}]}},{"update_id":196958705,
"message":{"message_id":37,"from":{"id":264667499,"is_bot":false,"first_name":"Anastasia","last_name":"Saldatava"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512737856,"left_chat_participant":{"id":264667499,"is_bot":false,"first_name":"Anastasia","last_name":"Saldatava"},"left_chat_member":{"id":264667499,"is_bot":false,"first_name":"Anastasia","last_name":"Saldatava"}}},{"update_id":196958706,
"message":{"message_id":38,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512737879,"text":"/random 1 5000","entities":[{"offset":0,"length":7,"type":"bot_command"}]}},{"update_id":196958707,
"message":{"message_id":39,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512737920,"text":"/random@rgen_bot","entities":[{"offset":0,"length":16,"type":"bot_command"}]}},{"update_id":196958708,
"message":{"message_id":41,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512737939,"text":"/random@rgen_bot 20 30","entities":[{"offset":0,"length":16,"type":"bot_command"}]}},{"update_id":196958709,
"message":{"message_id":42,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512737982,"text":"/random 1000 2000","entities":[{"offset":0,"length":7,"type":"bot_command"}]}},{"update_id":196958710,
"message":{"message_id":44,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512738016,"text":"/random 5 8","entities":[{"offset":0,"length":7,"type":"bot_command"}]}},{"update_id":196958711,
"message":{"message_id":46,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512738119,"text":"/random 8","entities":[{"offset":0,"length":7,"type":"bot_command"}]}},{"update_id":196958712,
"message":{"message_id":50,"from":{"id":187486854,"is_bot":false,"first_name":"\u0414\u043c\u0438\u0442\u0440\u0438\u0439","last_name":"\u041a\u0443\u0440\u0442o","username":"dmkurto"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512738617,"text":"/random 1 1000000","entities":[{"offset":0,"length":7,"type":"bot_command"}]}},{"update_id":196958713,
"message":{"message_id":52,"from":{"id":39661790,"is_bot":false,"first_name":"Andrey","last_name":"Karpovich","username":"Karpv","language_code":"en"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512739204,"text":"/random 100","entities":[{"offset":0,"length":7,"type":"bot_command"}]}},{"update_id":196958714,
"message":{"message_id":54,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-1001283156137,"title":"TestSendCoin","type":"supergroup"},"date":1512814907,"new_chat_participant":{"id":480191787,"is_bot":true,"first_name":"EMK lottery","username":"emk_lotto_bot"},"new_chat_member":{"id":480191787,"is_bot":true,"first_name":"EMK lottery","username":"emk_lotto_bot"},"new_chat_members":[{"id":480191787,"is_bot":true,"first_name":"EMK lottery","username":"emk_lotto_bot"}]}},{"update_id":196958715,
"message":{"message_id":55,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-1001283156137,"title":"Test group","type":"supergroup"},"date":1512814959,"new_chat_title":"Test group"}},{"update_id":196958716,
"message":{"message_id":56,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-1001283156137,"title":"Test group","type":"supergroup"},"date":1512815034,"text":"test"}},{"update_id":196958717,
"message":{"message_id":57,"from":{"id":412846761,"is_bot":false,"first_name":"Nikolay","last_name":"Litvinovich","language_code":"en"},"chat":{"id":-1001283156137,"title":"Test group","type":"supergroup"},"date":1512815104,"text":"test"}}]}';

$intervalSec = 10; // sec
$arChatId = [-1001283156137];

$countInvite = 2; // количество приглашённых

$bot = new TelegramBot();
$member = new Member();

while (true) {

    $response = json_decode($data, true);
//    var_dump($bot->query("getUpdates", ["offset" => 374768770]));
//    $response = $bot->getMessageFromChat();

    $arTickets = $member->getTickets() ?: []; // получить выданные билеты
    ksort($arTickets);
    $last = end($arTickets);  // последний элемент
    $ticketNumber = $last ? (key($arTickets) + 1) : 1; // +1 т.к. нумерация с 0, а 0-го номера билета нету
    $ticketCount = count($arTickets);

    $arStoredMembers = $member->getMembers() ?: []; // get all stored members
    $storeMemberCount = count($arStoredMembers);

    $arNewMembers = $member->getAddedMembers($response["result"]); // новые пользователи в чате

    $arProcessData = $member->getProcessData() ?: []; // основные данные
    $processDataCount = count($arProcessData);

    if ($response["ok"] && !empty($response["result"])) {
        $lastUpdateId = $response["result"][count($response["result"]) - 1]["update_id"]; // последний update id

        foreach ($arNewMembers as $user) {
            // если пользователь отсутствует - добавляем
            if (!$arStoredMembers[$user["FROM"]["ID"]]) {
                $arStoredMembers[$user["FROM"]["ID"]] = $user["FROM"];  // add new member
            }
            // если это новый приглашённый пользовватель - добавляем, записываем данные и выдаём билет
            if (!$arStoredMembers[$user["NEW_MEMBER"]["ID"]]) {
                $arStoredMembers[$user["NEW_MEMBER"]["ID"]] = $user["NEW_MEMBER"]; // add new member

                $arProcessData[$user["UPDATE_ID"]] = [
                    "UPDATE_ID"  => $user["UPDATE_ID"],
                    "DATE"       => $user["DATE"],
                    "USER_ID"    => $user["FROM"]["ID"],
                    "TICKET_ID"  => 0,
                    "NEW_MEMBER" => $user["NEW_MEMBER"]["ID"]
                ];

                $numberInvitedMembers = $member->getNumberInvitedMembers($user["FROM"]["ID"], $arProcessData);
                // если приглашённых больше опредёлённого количества
                if ($numberInvitedMembers >= $countInvite) {
                    $arProcessData[$user["UPDATE_ID"]]["TICKET_ID"] = $ticketNumber;
                    $arTickets[$ticketNumber++] = $user["FROM"]["ID"]; // новый номер билетика и запоминаем, кому выдали
                }
            }
        }

        if ($storeMemberCount != count($arStoredMembers)) { // если были новые пользователи
            $member->saveData($arStoredMembers, "members"); // сохранить новых пользователей
        }
        if ($ticketCount != count($arTickets)) {
            $member->saveData($arTickets, "tickets"); // сохранить билеты
        }
        if ($processDataCount != count($arProcessData)) {
            $member->saveData($arProcessData, "process"); // сохранить данные
        }
    }

    $inviteMsg = $member->getInviteReport($arProcessData, $arStoredMembers);
    $ticketsMsg = $member->getTicketsReport($arStoredMembers, $arTickets);

    var_dump($inviteMsg, $ticketsMsg);

//    $bot->sendMessageToChats($arChatId, "msg");

    exit();

    sleep($intervalSec);
}
