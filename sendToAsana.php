<?php

class sendToAsana{
    public function send_to_Asana($data_array, $users_list){
        $usersID_array = [
            'person'=> 'ID',
        ];

        $types_array = [
            '〇〇改善'=> 'ID',
        ];

        $fields_array = [
            '無し'=> '',
            '借金問題'=> 'ID',
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
        $section_gid = 'section_id';

        if($data_array['follower_num']==2){
            $username_f1 = $users_list[$data_array["携わった人"][0]];
            $username_f2 = $users_list[$data_array["携わった人"][1]];
            $asanaID['follower'][0] = $usersID_array[$username_f1];
            $asanaID['follower'][1] = $usersID_array[$username_f2];

            $client = Asana\Client::accessToken('access_token');
            $client->tasks->createTask(
                array(
                    'projects' => ['project_id'],
                    'name' => $send_data['name'],
                    'notes' => $detail_str,
                    'assignee' => $asanaID['assignee'], 
                    'due_on' => $send_data['date'],
                    'followers' => [$asanaID['follower'][0], $asanaID['follower'][1]],
                    'tags' => [$asanaID['types']],
                    'custom_fields' => [
                        'custom_fields_id' => $asanaID['fields']
                    ]
                ), 
                array('opt_pretty' => 'true')
            );          
        }

        if($data_array['follower_num']==1){
            $username_f = $users_list[$data_array["携わった人"]];
            $asanaID['follower'] = $usersID_array[$username_f];

            $client = Asana\Client::accessToken('access_token');
            $result = $client->tasks->createTask(
                array(
                'projects' => ['project_id'],
                'name' => $send_data['name'],
                'notes' => $detail_str,
                'assignee' => $asanaID['assignee'],
                'due_on' => $send_data['date'],
                'followers' => [$asanaID['follower']],
                'tags' => [$asanaID['types']],
                'custom_fields' => [
                    'custom_fields_id' => $asanaID['fields']
                ]
            ),
                array('opt_pretty' => 'true')
            );
        }

        if($data_array['follower_num']==0){
            $client = Asana\Client::accessToken('access_token');
            $result = $client->tasks->createTask(
                array(
                    'projects' => ['project_id'],
                    'name' => $send_data['name'],
                    'notes' => $detail_str,
                    'assignee' => $asanaID['assignee'],
                    'due_on' => $send_data['date'],
                    'tags' => [$asanaID['types']],
                    'custom_fields' => [
                        'custom_fields_id' => $asanaID['fields']
                    ]
                ),
                array('opt_pretty' => 'true')
            );
        }
    }
}
