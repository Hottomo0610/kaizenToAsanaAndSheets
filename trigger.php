<?php
//header('Content-Type: text/html; charset=UTF-8');
require_once './vendor/autoload.php';
require_once './php-asana/vendor/autoload.php';
require_once './getData.php';
require_once './editData.php';
require_once './sendToAsana.php';
require_once './sendToSheets.php';

/*Slack側のエンドポイントチェック用
echo file_get_contents('php://input'); */

$getKaizen = new getData();
$data = $getKaizen -> get_Slack_history();
$users_list = $getKaizen -> get_Slack_users();

$kaizen = $data[0];
$ts_path = "./timeStamp.txt";
$ts = file($ts_path);

if($kaizen['username'] == "改善-事業パワーアップ"){
    $time = $kaizen['ts'];
    if ($time>$ts[0]) {
        unset($ts[0]);
        file_put_contents($ts_path, $time);
        $text_array = $kaizen['blocks'][0]['elements'][0]['elements'];
        $edit = new editData();
        $data_array = $edit -> edit_text($text_array);
        $forAsana = new sendToAsana();
        $forAsana -> send_to_Asana($data_array, $users_list);
        $forSheets = new sendToSheets();
        $forSheets -> send_to_sheets($data_array, $users_list);
    }
}
