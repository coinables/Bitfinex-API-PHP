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
	     "request" => $request,
	     "order_id" => $order_id
	  );
	  return $this->hash_request($data);
   }
   
   public function kill_switch()
   {
   $request = "/v1/order/cancel/all";
   $data = array(
      "request" => $request
   );
   return $this->hash_request($data);
   }

   private function headers($data)
   {
      $data["nonce"] = strval(round(microtime(true) * 10,0));
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
         CURLOPT_SSL_VERIFYPEER => false,
         CURLOPT_POSTFIELDS => ""
      ));
      $ccc = curl_exec($ch);
      return json_decode($ccc, true);
   }

}

require_once('config.php');

$getask = json_decode(file_get_contents("https://api.bitfinex.com/v1/pubticker/BTCUSD"), true);
$ask = $getask['ask'];

$getbid = json_decode(file_get_contents("https://api.bitfinex.com/v1/pubticker/BTCUSD"), true);
$bid = $getbid['bid'];

$getavg = json_decode(file_get_contents("https://api.bitcoinaverage.com/ticker/global/USD/"), true);
$avg = $getavg['24h_avg'];

$diff = $avg - $ask;
$diff2 = $bid -  $avg;

if($diff > 2){
//good time to buy
echo "24h Avg: ".$avg."<br>";
echo "Current Ask: ".$ask."<br>";
$trade = new bitfinex($api_key, $api_secret);
$buy = $trade->new_order("BTCUSD", "0.01", "1", "buy", "exchange market");
print_r($buy);
}

if($diff2 > 2){
//good time to sell
echo "24h Avg: ".$avg."<br>";
echo "Current Bid: ".$bid."<br>";
$trade = new bitfinex($api_key, $api_secret);
$sell = $trade->new_order("BTCUSD", "0.01", "1", "sell", "exchange market");
print_r($sell);
}


?>

