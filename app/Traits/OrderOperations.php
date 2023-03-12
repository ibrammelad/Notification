<?php

namespace App\Traits;

use App\Models\Captain;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
use PhpParser\Node\Expr\AssignOp\Mod;

trait OrderOperations
{


    function distance($lat1, $lon1, $lat2, $lon2)
    {
        if(($lat1 == $lat2) && ($lon1 == $lon2))
            return 0;
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1))
                * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;

            return ($miles * 1.609344 * 1000);

        }
    }
    public function push_notification_android($device_id,$message){

        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';

        /*api_key available in:
        Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/
        $api_key = 'AAAAbGUTi2E:APA91bGcQ6Ikeni02tRP9--VW9O2B3iNowiAfe0TK9bGgXNkubj9MLztBzVbMxGvgo8F4kAN9MtZ_J6rPCpg_YoaGSnOEEBGIDubtNQEM3bh9im3bOQ3_4nHNbkgN-nxXEKrNiAGsW__';

        $fields = array (
            'registration_ids' => array (
                $device_id
            ),
            'notification' => array (
                "title"=> "hasb",
                "body" => $message
            ),
            'data' => array (
                "type"=> "notification",
            )
        );

        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function PushNotificationForAllCaptainAvailable()
    {
        try {
            DB::beginTransaction();
            ////// all order need to captain /////////////
            $orderNeedCaptains = Order::where('status', 0)->get();
            ///////// all Captains is available/////////////
            $allCaptainsAvailable = Captain::where('status', 1)->get();
            foreach ($orderNeedCaptains as $order) {
                foreach ($allCaptainsAvailable as $captain) {
                    $message = "hello {$captain->name} here you are order
                     location in {$order->location} with cost {$order->cost}";
                    $distance = $this->distance($captain->lat, $captain->long, $order->latFrom, $order->longFrom);
                    if ($distance >= 5000) continue;
                    else
                        $this->push_notification_android($captain->FCMToken, $message);
                    Notification::create(['captain_id' => $captain->id,'order_id' =>$order->id ,'message' => $message]);
                }
            }
            DB::commit();
        }
    catch(\Exception $exception)
        {
            DB::rollback();
        }
    }
    public function pushForNotifyOrderCancel(Order $order)
    {
        $allCaptainReceivedNotification = $order->notifications()->with("captain")->get()->pluck('captain')->unique('id')->values();
        foreach ($allCaptainReceivedNotification as $captain)
        {
            $message = "hello {$captain->name} Order is location in {$order->location},it has been Canceled ";
            $this->push_notification_android($captain->FCMToken, $message);
            Notification::create(['captain_id' => $captain->id,'order_id' =>$order->id ,'message' => $message]);

        }
        return true;
    }

}
