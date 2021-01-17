<?php
//header('Content-Type: text/html; charset=UTF-8');
require_once '/var/www/kaizenToAsana/vendor/autoload.php';
require_once '/var/www/kaizenToAsana/php-asana/vendor/autoload.php';
require_once '/var/www/kaizenToAsana/getData.php';
require_once '/var/www/kaizenToAsana/editData.php';
require_once '/var/www/kaizenToAsana/sendToAsana.php';
require_once '/var/www/kaizenToAsana/sendToSheets.php';

/*Slack側のエンドポイントチェック用
echo file_get_contents('php://input'); */

$getKaizen = new getData();
$data = $getKaizen -> get_Slack_history();
$users_list = $getKaizen -> get_Slack_users();

$kaizen = $data[0];
$ts_path = "/var/www/kaizenToAsana/timeStamp.txt";
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