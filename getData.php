<?php

class getData {
    public function get_Slack_history(){
        //Slackの改善チャンネルから改善施策の投稿を取得
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://slack.com/api/conversations.history?token=xoxp-20057120048-1182319415393-1528960139238-983ee68779ed05bb8bb69643c43cd5d7&channel=CAM91FNQ0&limit=2"
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);

        $decodedData = json_decode($resp, true);
        $data = $decodedData['messages'];
        return $data;
    }

    public function get_Slack_users(){
        //SlackのWPワークスペースに属する全てのユーザーの情報を取得

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "https://slack.com/api/users.list?token=xoxp-20057120048-1182319415393-1528960139238-983ee68779ed05bb8bb69643c43cd5d7"
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);

        $decodedData = json_decode($resp, true);
        $users_list = $decodedData['members'];
        //error_log(print_r($users_list, true));

        //不要なユーザーやbotを除外して、さらにデータを加工
        $valid_users_list = array();

        foreach($users_list as $value){
            if($value["is_bot"]!=1){
                if($value["deleted"]!=1){
                    if ($value["is_restricted"]!=1) {
                        if ($value["name"]!="slackbot") {
                            $valid_users_list["<@".$value["id"].">"] = $value["name"];
                        }
                    }
                }
            }
        }

        return $valid_users_list;
    }
}