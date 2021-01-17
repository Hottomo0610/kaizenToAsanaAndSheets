<?php

class editData{
    public function edit_text($text_array){
        $retrun_array = [];

        for($i=0; $i<8; $i++){
            if(array_key_exists("text", $text_array[$i]['elements'][2])){
                $key = $text_array[$i]['elements'][0]['text'];
                $retrun_array[$key] = $text_array[$i]['elements'][2]['text'];
                if($i==7){
                    $retrun_array['follower_num'] = 0;
                }
            } elseif((array_key_exists("text", $text_array[$i]['elements'][2]) == false)&&(array_key_exists("user_id", $text_array[$i]['elements'][2]))){
                $follower_array = $text_array[$i]['elements'];
                if(count($follower_array)==5){
                    $key = $text_array[$i]['elements'][0]['text'];
                    $retrun_array[$key][0] = "<@".$text_array[$i]['elements'][2]['user_id'].">";
                    $retrun_array[$key][1] = "<@".$text_array[$i]['elements'][4]['user_id'].">";
                    $retrun_array['follower_num'] = 2;
                } else {
                    $key = $text_array[$i]['elements'][0]['text'];
                    $retrun_array[$key] = "<@".$text_array[$i]['elements'][2]['user_id'].">";
                    if($i==7){
                        $retrun_array['follower_num'] = 1;
                    }
                }
            }
        }

        return $retrun_array;
    }
}