# Bitfinex PHP Library

This repo is the outcome of me ignoring the 2016 superbowl. Instead I streamed this for an hour and half on youtube (https://www.youtube.com/watch?v=rky1EnDqmhI). 
In the end we had a mini PHP library for the bitfinex API and a very simple trading bot. The bot uses bitcoinaverage.com API to determine if it 
is a "good" time to buy or sell. 


=======
BTC:  1NPrfWgJfkANmd1jt88A141PjhiarT8d9U


# Usage
0. Requirements

 * Bitfinex account with funds in appropriate finex wallets (exchange, trading)
 * Bitfinex API Keys and secret key
 * A server that is able to run PHP files. 
 * (Optional) Access to Cronjobs (this way you can automate trading)

1. Download or clone the main project and extract files.

`example.php` will make trades if you run it. DELETE EVERYTHING AFTER `require_once(config.php);` if you don't want the bot to execute a trade. If you want the bot to run automatically, you will need to set up the `example.php` file to run as a cron job every N minutes/hours/days etc. 

2. Update the config.php file with your api keys

        $api_key = "your_api_key";
		$api_secret = "your_api_secret";


3. How to do a market buy order on the exchange market

        $trade = new bitfinex($api_key, $api_secret);
		$buy = $trade->new_order("BTCUSD", "0.01", "1", "buy", "exchange market");

4. How to do a limit sell order on the margin market

		$trade = new bitfinex($api_key, $api_secret);
		$buy = $trade->new_order("BTCUSD", "0.01", "380.29", "sell", "limit");


## Additional Info

The basic schematic is: 

		$variable = new bitfinex($api_key, $api_secret);
		$variable2 = $variable->new_order("SYMBOL", "AMOUNT", "PRICE", "SIDE", "TYPE");
		
When making a limit order the price is the price you are looking to get met. When making a market order the price can be any random number. 
The `SIDE` refers to buy or sell. The `TYPE` is the type of order. If you want to do margin trades, enter the type without `exchange` in the front. 

Example (exchange buy):
		("BTCUSD", "0.01", "1", "buy", "exchange market");
		
Example (margin buy):
		("BTCUSD", "0.01", "1", "buy", "market");
		
## Cancelling Orders
		
		$order_id = "943715";
		$execute = $trade->cancel_order($order_id);
		
## Cancel All Orders
		
		$execute = $trade->cancel_all();		
		
		
## Account Info
		
		$execute = $trade->account_info();
		print_r($execute);
		/*
	    example response
	    [{
	    "maker_fees":"0.1",
	    "taker_fees":"0.2",
		"fees":[{
		"pairs":"BTC",
		"maker_fees":"0.1",
		"taker_fees":"0.2"
	    },{
		"pairs":"LTC",
		"maker_fees":"0.1",
		"taker_fees":"0.2"
	    },
	    {
		"pairs":"DRK",
		"maker_fees":"0.1",
		"taker_fees":"0.2"
		}]
	    }]
	    */

## Deposit
		$method = "bitcoin";
		$wallet = "trading";
		$renew = 1;
		$execute = $trade->deposit($method, $wallet, $renew);
		
Deposit generates a BTC address to deposit funds into bitfinex
$renew will generate a new fresh deposit address if set to 1, default is 0.

		/*
		example response
		{
		  "result":"success",
		  "method":"bitcoin",
		  "currency":"BTC",
		  "address":"1A2wyHKJ4KWEoahDHVxwQy3kdd6g1qiSYV"
		}
		*/


## Positions
Shows current positions.
		
		$execute = $trade->positions();

		/*
		Example response:
		[{
		  "id":943715,
		  "symbol":"btcusd",
		  "status":"ACTIVE",
		  "base":"246.94",
		  "amount":"1.0",
		  "timestamp":"1444141857.0",
		  "swap":"0.0",
		  "pl":"-2.22042"
		}]
		*/
   
	
## Close Position
		
		$position_id = 841235;
		$execute = $trade->close_position($position_id);	


## Claim Position
		
		$execute = $trade->claim_position($position_id);	
				

## Get Balance of Account
		
		$execute = $trade->fetch_balance();				


## Get Margin Info of Account
		
		$execute = $trade->margin_infos();


## Transfer funds to different wallet on your account
		
		$amount = 2.014; 
		$currency = "btc";
		$from = "exchange";
		$to = "trading";
		$execute = $trade->transfer($amount, $currency, $from, $to);		
		
		
=======
		

