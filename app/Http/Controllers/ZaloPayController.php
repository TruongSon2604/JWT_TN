<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZaloPayController extends Controller
{
    //
    public function payment()
    {
        $config = [
            "app_id" => 2554,
            "key1" => "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn",
            "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf",
            "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
        ];

        $embeddata = '{}'; // Merchant's data
        $items = '[]'; // Merchant's data
        $transID = rand(0,1000000); //Random trans id
        $order = [
            "app_id" => $config["app_id"],
            "app_time" => round(microtime(true) * 1000), // miliseconds
            "app_trans_id" => date("ymd") . "_" . $transID, // translation missing: vi.docs.shared.sample_code.comments.app_trans_id
            "app_user" => "user123",
            "item" => $items,
            "embed_data" => $embeddata,
            "amount" => 50000,
            "description" => "Lazada - Payment for the order #$transID",
            "bank_code" => "zalopayapp",
            "callback_url"=>"https://e307-123-19-198-244.ngrok-free.app/api/callback"
        ];

        // appid|app_trans_id|appuser|amount|apptime|embeddata|item
        $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"]
            . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $config["key1"]);

        $context = stream_context_create([
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($order)
            ]
        ]);

        $resp = file_get_contents($config["endpoint"], false, $context);
        $result = json_decode($resp, true);

        // foreach ($result as $key => $value) {
        //     echo "$key: $value<br>";
        // }
         return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
            'data' => $result
        ]);
    }
    // public function callback()
    // {

    //     $result = [];

    //     try {
    //     $key2 = "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf";
    //     $postdata = file_get_contents('php://input');
    //     $postdatajson = json_decode($postdata, true);
    //     $mac = hash_hmac("sha256", $postdatajson["data"], $key2);

    //     $requestmac = $postdatajson["mac"];

    //     // kiểm tra callback hợp lệ (đến từ ZaloPay server)
    //     if (strcmp($mac, $requestmac) != 0) {
    //         // callback không hợp lệ
    //         $result["return_code"] = -1;
    //         $result["return_message"] = "mac not equal";
    //     } else {
    //         // thanh toán thành công
    //         // merchant cập nhật trạng thái cho đơn hàng
    //         $datajson = json_decode($postdatajson["data"], true);
    //         echo "update order's status = success where app_trans_id = ". $dataJson["app_trans_id"];

    //         $result["return_code"] = 1;
    //         $result["return_message"] = "success";
    //     }
    //     } catch (Exception $e) {
    //     $result["return_code"] = 0; // ZaloPay server sẽ callback lại (tối đa 3 lần)
    //     $result["return_message"] = $e->getMessage();
    //     }

    //     // thông báo kết quả cho ZaloPay server
    //     echo json_encode($result);
    // }
    public function get_status($iddh)
    {

        $config = [
            "app_id" => 2554,
            "key1" => "sdngKKJmqEMzvh5QQcdD2A9XBSKUNaYn",
            "key2" => "trMrHtvjo6myautxDUiAcYsVtaeQ8nhf",
            "endpoint" => "https://sb-openapi.zalopay.vn/v2/query",
        ];

          $app_trans_id = $iddh;  // Input your app_trans_id
          $data = $config["app_id"]."|".$app_trans_id."|".$config["key1"]; // app_id|app_trans_id|key1
          $params = [
            "app_id" => $config["app_id"],
            "app_trans_id" => $app_trans_id,
            "mac" => hash_hmac("sha256", $data, $config["key1"])
          ];

          $context = stream_context_create([
              "http" => [
                  "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                  "method" => "POST",
                  "content" => http_build_query($params)
              ]
          ]);

          $resp = file_get_contents($config["endpoint"], false, $context);
          $result = json_decode($resp, true);

          return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
            'data' => $result
        ]);
    }
}
