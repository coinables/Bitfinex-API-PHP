<?php

class bitfinex{
  protected $apikey;
  protected $secret;
  protected $url = "https://api.bitfinex.com";

  public function __construct($apikey, $secret)
  {
     $this->apikey = $apikey;
     $this->secret = $secret;
  }

   public function new_order($symbol, $amount, $price, $side, $type)
   {
      $request = "/v1/order/new";
      $data = array(
         "request" => $request,
         "symbol" => $symbol,
         "amount" => $amount,
         "price" => $price,
         "exchange" => "bitfinex",
         "side" => $side,
         "type" => $type
      );
      return $this->hash_request($data);
   }
   
   public function cancel_order($order_id)
   {
      $request = "/v1/order/cancel";
	  $data = array(
	     "order_id" => $order_id
	  );
	  return $this->hash_request($data);
   }
   
   //kill switch, cancel all orders
   public function kill_switch()
   {
   $request = "/v1/order/cancel/all";
   return $this->hash_request($data);
   }

   private function headers($data)
   {
      $data["nonce"] = strval(round(microtime(true),0));
      $payload = base64_encode(json_encode($data));
      $signature = hash_hmac("sha384", $payload, $this->secret);
      return array(
         "X-BFX-APIKEY: " . $this->apikey,
         "X-BFX-PAYLOAD: " .$payload,
         "X-BFX-SIGNATURE: " . $signature
      );
   }

   private function hash_request($data)
   {
      $ch = curl_init();
      $bfurl = $this->url . $data["request"];
      $headers = $this->headers($data);
      curl_setopt_array($ch, array(
         CURLOPT_URL => $bfurl,
         CURLOPT_POST => true,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_HTTPHEADER => $headers,
         CURLOPT_SSL_VERIFYPEER => true,
         CURLOPT_POSTFIELDS => ""
      ));
      $ccc = curl_exec($ch);
      return json_decode($ccc, true);
   }

}

$trade = new bitfinex("JQmCzDlci9ehliKd1pZ8gXRKqxBKoyEq8PoLocXOu12", "H2IdizuS0Erilu0xfwuxicdybXTTMbAkrRpTcVOz6C9");
$margin_limit_buy = $trade->new_order("BTCUSD", "0.01", "375.85", "buy", "limit");
echo $margin_limit_buy['message'];

?>

