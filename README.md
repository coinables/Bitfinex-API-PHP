# Bitfinex PHP Library

This repo is the outcome of me ignoring the 2016 superbowl. Instead I streamed this for an hour and half on youtube (https://www.youtube.com/watch?v=rky1EnDqmhI). 
In the end we had a mini PHP library for the bitfinex API and a very simple trading bot. The bot uses bitcoinaverage.com API to determine if it 
is a "good" time to buy or sell. 

BTC:  1NPrfWgJfkANmd1jt88A141PjhiarT8d9U

# Usage
0. Requirements

 * Bitfinex account with funds in appropriate finex wallets (exchange, trading)
 * Bitfinex API Keys and secret key
 * A server that is able to run PHP files. 
 * (Optional) Access to Cronjobs (this way you can automate trading)

1. Download or clone the main project and extract files.

DELTE EVERYTHING AFTER `require_once(config.php);` if you don't want the bot to execute a trade. 
If you want the bot to run automatically, you will need to set up the finex.php file to run as a crong job every N minutes/hours/days etc. 

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
		$variable = new bitfinex($api_key, $api_secret);
		$variable2 = $variable->cancel_order("orderID");
		
		
## Cancel All Orders
		$variable = new bitfinex($api_key, $api_secret);
		$variable2 = $variable->kill_switch();
		
		
		
