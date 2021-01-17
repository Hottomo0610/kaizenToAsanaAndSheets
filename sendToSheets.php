<?php

class sendToSheets{
    public static $credentials_path = "./credentials.json";

    public function send_to_sheets($data_array, $users_list){
        $client = new \Google_Client();
        $client->setScopes([
            \Google_Service_Sheets::SPREADSHEETS,
            \Google_Service_Sheets::DRIVE,
        ]);
        $client->setAuthConfig(sendToSheets::$credentials_path);

        $send_data = array();
        $send_data[0][0] = $data_array["実施日"];
        $username_a = $users_list[$data_array["担当者"]];
        $send_data[0][1] = $username_a;
        $send_data[0][2] = $data_array["種別"];
        $send_data[0][3] = $data_array['follower_num'] + 1;
        
        if($data_array['follower_num'] == 2){
            $username_f1 = $users_list[$data_array["携わった人"][0]];
            $username_f2 = $users_list[$data_array["携わった人"][1]];
            $send_data[0][4] = $username_f1;
            $send_data[0][5] = $username_f2;
        } elseif($data_array['follower_num'] == 1){
            $username_f = $users_list[$data_array["携わった人"]];
            $send_data[0][4] = $username_f;
            $send_data[0][5] = "";
        } else {
            $send_data[0][4] = "";
            $send_data[0][5] = "";
        }

        $send_data[0][6] = "事業";

        $service = new \Google_Service_Sheets($client);
        $spreadsheet_id = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
        $sheetName = "シート1";
        $rowsInfo = $service->spreadsheets_values->get($spreadsheet_id, 'シート1!A3:I');
        $lastRow = count($rowsInfo)+3;
        $range = $sheetName."!A"."$lastRow";
        $body = new \Google_Service_Sheets_ValueRange([
            "majorDimension" => "ROWS",
            "values" => $send_data
        ]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        $service->spreadsheets_values->update($spreadsheet_id, $range, $body, $params);
    }
}
