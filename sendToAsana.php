<?php

class sendToAsana{
    public function send_to_Asana($data_array, $users_list){
        $usersID_array = [
            'tsuchida'=> '1172517787925676',
            'saiki'=> '435731065997903',
            's.matsuda'=> '1172529761670609',
            'h.higuma'=> '1172705367611835',
            'h.kodaka'=> '1173183367460908',
            'a.imase'=> '435743567718875',
            'h.shouji'=> '1172298671119996',
            'k.inomoto'=> '1187374967720180',
            'k.yamamoto'=> '1172518192195723',
            'k.nabehira'=> '1173156619137740',
            'f.eguchi'=> '1173211325962492',
            'n.matsuda'=> '1173156777693204',
            'g.nakamura'=> '1172352337237867',
            'e.shiba'=> '1172485813099934',
            't.hotta'=> '1179925246623803',
            'm.murase'=> '1189427995846986',
            'r.nakano'=> '1189446298541706',
            'k.yamauchi'=> '1190714856456372',
            't.yokoyama'=> '1195976123749457',
            'r.nakamura'=> '1188456578233589',
            'e.yajima'=> '1198974315740266',
            'm.arai'=> '1198840495129047'
        ];

        $types_array = [
            '営業・運営改善'=> '1198506091075438',
            '新規サイト作成'=> '1199228721722650',
            'サイト改善'=> '1198506091075439',
            'リスティング改善'=> '1198506091075440',
            'SNS関連施策'=> '1198506091075441',
            '効率化'=> '1198506091075442',
            '見える化'=> '1198506091075437',
            '速度改善'=> '1198897874038421',
            'SEO改善'=> '1198897874038422'
        ];

        $fields_array = [
            '無し'=> '',
            '借金問題'=> '1199123305296679',
            '相続問題'=> '1199123305296686',
            '交通事故'=> '1199123305296754',
            '任意売却'=> '1199123305296757',
            '不動産名義変更'=> '1199123305296760',
            '刑事事件'=> '1199123305296780',
            '離婚問題'=> '1199123305296784',
            'B型肝炎'=> '1199123305297812',
            '誹謗中傷'=> '1199123305297818',
            '時効援用'=> '1199123305298845',
            '労働問題'=> '1199123305298848',
            '養育費回収'=> '1199123305299884',
            '不動産問題'=> '1199123305300912',
            '成年後見'=> '1199123305300915',
            '行政書士'=> '1199123305300917',
            '債権回収'=> '1199123305300921'
        ];

        $send_data = array();
        $asanaID = array();

        $send_data['name'] = $data_array["施策"];
        $date = new DateTime($data_array["実施日"]);
        $send_data['date'] = $date -> format('Y-m-d');
        $send_data['purpose'] = $data_array["目的"];
        $send_data['detail'] = $data_array["説明"];

        $username_a = $users_list[$data_array["担当者"]];
        $asanaID['assignee'] = $usersID_array[$username_a];
        $asanaID['types'] = $types_array[$data_array["種別"]];
        $asanaID['fields'] = $fields_array[$data_array["分野"]];
        $detail_str = "目的：".$send_data['purpose']."\n\n".$send_data['detail'];
        // error_log(print_r($asanaID, true));
        // error_log(print_r($send_data, true));
        // error_log($detail_str);
        $section_gid = '1199123305392529';

        if($data_array['follower_num']==2){
            $username_f1 = $users_list[$data_array["携わった人"][0]];
            $username_f2 = $users_list[$data_array["携わった人"][1]];
            $asanaID['follower'][0] = $usersID_array[$username_f1];
            $asanaID['follower'][1] = $usersID_array[$username_f2];

            $client = Asana\Client::accessToken('1/1179925234352137:eab90fafa64b6216ef580cef18b6bdd5');
            $client->tasks->createTask(
                array(
                    'projects' => ['1199123043593342'],
                    'name' => $send_data['name'],
                    'notes' => $detail_str,
                    'assignee' => $asanaID['assignee'], 
                    'due_on' => $send_data['date'],
                    'followers' => [$asanaID['follower'][0], $asanaID['follower'][1]],
                    'tags' => [$asanaID['types']],
                    'custom_fields' => [
                        '1199123305295652' => $asanaID['fields']
                    ]
                ), 
                array('opt_pretty' => 'true')
            );
            //error_log("送信完了1");            
        }

        if($data_array['follower_num']==1){
            $username_f = $users_list[$data_array["携わった人"]];
            $asanaID['follower'] = $usersID_array[$username_f];

            $client = Asana\Client::accessToken('1/1179925234352137:eab90fafa64b6216ef580cef18b6bdd5');
            $result = $client->tasks->createTask(
                array(
                'projects' => ['1199123043593342'],
                'name' => $send_data['name'],
                'notes' => $detail_str,
                'assignee' => $asanaID['assignee'],
                'due_on' => $send_data['date'],
                'followers' => [$asanaID['follower']],
                'tags' => [$asanaID['types']],
                'custom_fields' => [
                    '1199123305295652' => $asanaID['fields']
                ]
            ),
                array('opt_pretty' => 'true')
            );
            //error_log("送信完了2");
        }

        if($data_array['follower_num']==0){
            $client = Asana\Client::accessToken('1/1179925234352137:eab90fafa64b6216ef580cef18b6bdd5');
            $result = $client->tasks->createTask(
                array(
                    'projects' => ['1199123043593342'],
                    'section' => '1199123305392529',
                    'name' => $send_data['name'],
                    'notes' => $detail_str,
                    'assignee' => $asanaID['assignee'],
                    'due_on' => $send_data['date'],
                    'tags' => [$asanaID['types']],
                    'custom_fields' => [
                        '1199123305295652' => $asanaID['fields']
                    ]
                ),
                array('opt_pretty' => 'true')
            );
            //error_log("送信完了3");
        }
    }
}