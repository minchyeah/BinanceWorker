<?php

namespace Library;

class Binance
{
    /**
     * static instance
     * @var array
     */
    protected static $instance = null;

    /**
     * apiBaseUrl description
     * @var string
     */
    protected $apiBaseUrl = 'https://api.binance.com/api/';

    /**
     * apiKey description
     * @var string
     */
    protected $apiKey = '';

    /**
     * apiSecret description
     * @var string
     */
    protected $apiSecret = '';

    /**
     * Constructor
     * @return null
     */
    protected function __construct($key, $secret)
    {
        $this->apiKey = $key;
        $this->apiSecret = $secret;
    }

    /**
     * static instance of Binance
     * @param string $key  api key
     * @param string $secret api secret
     * @return Library\Binance
     */
    public static function instance($key, $secret)
    {
        if(!isset(self::$instance) OR !self::$instance OR !(self::$instance instanceof Binance)){
            self::$instance = new self($key, $secret);
        }
        return self::$instance;
    }

    /**
     * buy attempts to create a currency order
     * each currency supports a number of order types, such as
     * -LIMIT
     * -MARKET
     * -STOP_LOSS
     * -STOP_LOSS_LIMIT
     * -TAKE_PROFIT
     * -TAKE_PROFIT_LIMIT
     * -LIMIT_MAKER
     *
     * You should check the @see exchangeInfo for each currency to determine
     * what types of orders can be placed against specific pairs
     *
     * $quantity = 1;
     * $price = 0.0005;
     * $order = $api->buy("BNBBTC", $quantity, $price);
     *
     * @param $symbol string the currency symbol
     * @param $quantity string the quantity required
     * @param $price string price per unit you want to spend
     * @param $type string type of order
     * @param $flags array addtional options for order type
     * @return array with error message or the order details
     */
    public function buy(string $symbol, $quantity, $price, string $type = "LIMIT", array $flags = [])
    {
        return $this->order("BUY", $symbol, $quantity, $price, $type, $flags);
    }

    /**
     * buyTest attempts to create a TEST currency order
     *
     * @see buy()
     *
     * @param $symbol string the currency symbol
     * @param $quantity string the quantity required
     * @param $price string price per unit you want to spend
     * @param $type string config
     * @param $flags array config
     * @return array with error message or empty or the order details
     */
    public function buyTest(string $symbol, $quantity, $price, string $type = "LIMIT", array $flags = [])
    {
        return $this->order("BUY", $symbol, $quantity, $price, $type, $flags, true);
    }

    /**
     * sell attempts to create a currency order
     * each currency supports a number of order types, such as
     * -LIMIT
     * -MARKET
     * -STOP_LOSS
     * -STOP_LOSS_LIMIT
     * -TAKE_PROFIT
     * -TAKE_PROFIT_LIMIT
     * -LIMIT_MAKER
     *
     * You should check the @see exchangeInfo for each currency to determine
     * what types of orders can be placed against specific pairs
     *
     * $quantity = 1;
     * $price = 0.0005;
     * $order = $api->sell("BNBBTC", $quantity, $price);
     *
     * @param $symbol string the currency symbol
     * @param $quantity string the quantity required
     * @param $price string price per unit you want to spend
     * @param $type string type of order
     * @param $flags array addtional options for order type
     * @return array with error message or the order details
     */
    public function sell(string $symbol, $quantity, $price, string $type = "LIMIT", array $flags = [])
    {
        return $this->order("SELL", $symbol, $quantity, $price, $type, $flags);
    }

    /**
     * sellTest attempts to create a TEST currency order
     *
     * @see sell()
     *
     * @param $symbol string the currency symbol
     * @param $quantity string the quantity required
     * @param $price string price per unit you want to spend
     * @param $type array config
     * @param $flags array config
     * @return array with error message or empty or the order details
     */
    public function sellTest(string $symbol, $quantity, $price, string $type = "LIMIT", array $flags = [])
    {
        return $this->order("SELL", $symbol, $quantity, $price, $type, $flags, true);
    }

    /**
     * marketBuy attempts to create a currency order at given market price
     *
     * $quantity = 1;
     * $order = $api->marketBuy("BNBBTC", $quantity);
     *
     * @param $symbol string the currency symbol
     * @param $quantity string the quantity required
     * @param $flags array addtional options for order type
     * @return array with error message or the order details
     */
    public function marketBuy(string $symbol, $quantity, array $flags = [])
    {
        return $this->order("BUY", $symbol, $quantity, 0, "MARKET", $flags);
    }

    /**
     * marketBuyTest attempts to create a TEST currency order at given market price
     *
     * @see marketBuy()
     *
     * @param $symbol string the currency symbol
     * @param $quantity string the quantity required
     * @param $flags array addtional options for order type
     * @return array with error message or the order details
     */
    public function marketBuyTest(string $symbol, $quantity, array $flags = [])
    {
        return $this->order("BUY", $symbol, $quantity, 0, "MARKET", $flags, true);
    }

    /**
     * marketSell attempts to create a currency order at given market price
     *
     * $quantity = 1;
     * $order = $api->marketSell("BNBBTC", $quantity);
     *
     * @param $symbol string the currency symbol
     * @param $quantity string the quantity required
     * @param $flags array addtional options for order type
     * @return array with error message or the order details
     */
    public function marketSell(string $symbol, $quantity, array $flags = [])
    {
        return $this->order("SELL", $symbol, $quantity, 0, "MARKET", $flags);
    }

    /**
     * marketSellTest attempts to create a TEST currency order at given market price
     *
     * @see marketSellTest()
     *
     * @param $symbol string the currency symbol
     * @param $quantity string the quantity required
     * @param $flags array addtional options for order type
     * @return array with error message or the order details
     */
    public function marketSellTest(string $symbol, $quantity, array $flags = [])
    {
        return $this->order("SELL", $symbol, $quantity, 0, "MARKET", $flags, true);
    }

    /**
     * cancel attempts to cancel a currency order
     *
     * $orderid = "123456789";
     * $order = $api->cancel("BNBBTC", $orderid);
     *
     * @param $symbol string the currency symbol
     * @param $orderid string the orderid to cancel
     * @param $flags array of optional options like ["side"=>"sell"]
     * @return array with error message or the order details
     * @throws \Exception
     */
    public function cancel(string $symbol, $orderid, $flags = [])
    {
        $params = [
            "symbol" => $symbol,
            "orderId" => $orderid,
        ];
        return $this->httpRequest("v3/order", "DELETE", array_merge($params, $flags), true);
    }

    /**
     * orderStatus attempts to get orders status
     *
     * $orderid = "123456789";
     * $order = $api->orderStatus("BNBBTC", $orderid);
     *
     * @param $symbol string the currency symbol
     * @param $orderid string the orderid to cancel
     * @return array with error message or the order details
     * @throws \Exception
     */
    public function orderStatus(string $symbol, $orderid)
    {
        return $this->httpRequest("v3/order", "GET", [
            "symbol" => $symbol,
            "orderId" => $orderid,
        ], true);
    }

    /**
     * openOrders attempts to get open orders for all currencies or a specific currency
     *
     * $allOpenOrders = $api->openOrders();
     * $allBNBOrders = $api->openOrders( "BNBBTC" );
     *
     * @param $symbol string the currency symbol
     * @return array with error message or the order details
     * @throws \Exception
     */
    public function openOrders(string $symbol = null)
    {
        $params = [];
        if (is_null($symbol) != true) {
            $params = [
                "symbol" => $symbol,
            ];
        }
        return $this->httpRequest("v3/openOrders", "GET", $params, true);
    }

    /**
     * orders attempts to get the orders for all or a specific currency
     *
     * $allBNBOrders = $api->orders( "BNBBTC" );
     *
     * @param $symbol string the currency symbol
     * @param $limit int the amount of orders returned
     * @param $fromOrderId string return the orders from this order onwards
     * @return array with error message or array of orderDetails array
     * @throws \Exception
     */
    public function orders(string $symbol, int $limit = 500, int $fromOrderId = 1)
    {
        return $this->httpRequest("v3/allOrders", "GET", [
            "symbol" => $symbol,
            "limit" => $limit,
            "orderId" => $fromOrderId,
        ], true);
    }

    /**
     * history Get the complete account trade history for all or a specific currency
     *
     * $allHistory = $api->history();
     * $BNBHistory = $api->history("BNBBTC");
     * $limitBNBHistory = $api->history("BNBBTC",5);
     * $limitBNBHistoryFromId = $api->history("BNBBTC",5,3);
     *
     * @param $symbol string the currency symbol
     * @param $limit int the amount of orders returned
     * @param $fromTradeId int (optional) return the orders from this order onwards. negative for all
     * @return array with error message or array of orderDetails array
     * @throws \Exception
     */
    public function history(string $symbol, int $limit = 500, int $fromTradeId = -1)
    {
        $parameters = [
            "symbol" => $symbol,
            "limit" => $limit,
        ];
        if ($fromTradeId > 0) {
            $parameters["fromId"] = $fromTradeId;
        }

        return $this->httpRequest("v3/myTrades", "GET", $parameters, true);
    }

    /**
     * useServerTime adds the 'useServerTime'=>true to the API request to avoid time errors
     *
     * $api->useServerTime();
     *
     * @return null
     * @throws \Exception
     */
    public function useServerTime()
    {
        $request = $this->httpRequest("v1/time");
        if (isset($request['serverTime'])) {
            $this->info['timeOffset'] = $request['serverTime'] - (microtime(true) * 1000);
        }
    }

    /**
     * time Gets the server time
     *
     * $time = $api->time();
     *
     * @return array with error message or array with server time key
     * @throws \Exception
     */
    public function time()
    {
        return $this->httpRequest("v1/time");
    }

    /**
     * exchangeInfo Gets the complete exchange info, including limits, currency options etc.
     *
     * $info = $api->exchangeInfo();
     *
     * @return array with error message or exchange info array
     * @throws \Exception
     */
    public function exchangeInfo()
    {
        return $this->httpRequest("v1/exchangeInfo");
    }

    /**
     * [marginSymbols description]
     * @return [type] [description]
     */
    public function marginSymbols()
    {
        $symbols = [];
        $exinfo = $this->exchangeInfo();
        if(isset($exinfo['symbols']) && !empty($exinfo['symbols'])){
            foreach ($exinfo['symbols'] as $symbol) {
                if($symbol['isMarginTradingAllowed'] && $symbol['quoteAsset'] == 'USDT'){
                    $symbols = $symbol;
                }
            }
        }
        return $symbols;
    }

    /**
     * prices get all the current prices
     *
     * $ticker = $api->prices();
     *
     * @return array with error message or array of all the currencies prices
     * @throws \Exception
     */
    public function prices()
    {
        return $this->priceData($this->httpRequest("v3/ticker/price"));
    }

    /**
     * price get the latest price of a symbol
     *
     * $price = $api->price( "ETHBTC" );
     *
     * @return array with error message or array with symbol price
     * @throws \Exception
     */
    public function price(string $symbol)
    {
        $ticker = $this->httpRequest("v3/ticker/price", "GET", ["symbol" => $symbol]);

        return $ticker['price'];
    }

    /**
     * bookPrices get all bid/asks prices
     *
     * $ticker = $api->bookPrices();
     *
     * @return array with error message or array of all the book prices
     * @throws \Exception
     */
    public function bookPrices()
    {
        return $this->bookPriceData($this->httpRequest("v3/ticker/bookTicker"));
    }

    /**
     * account get all information about the api account
     *
     * $account = $api->account();
     *
     * @return array with error message or array of all the account information
     * @throws \Exception
     */
    public function account()
    {
        return $this->httpRequest("v3/account", "GET", [], true);
    }

    /**
     * depth get Market depth
     *
     * $depth = $api->depth("ETHBTC");
     *
     * @param $symbol string the symbol to get the depth information for
     * @return array with error message or array of market depth
     * @throws \Exception
     */
    public function depth(string $symbol)
    {
        if (isset($symbol) === false || is_string($symbol) === false) {
            // WPCS: XSS OK.
            echo "asset: expected bool false, " . gettype($symbol) . " given" . PHP_EOL;
        }
        $json = $this->httpRequest("v1/depth", "GET", [
            "symbol" => $symbol,
        ]);
        if (isset($this->info[$symbol]) === false) {
            $this->info[$symbol] = [];
        }
        $this->info[$symbol]['firstUpdate'] = $json['lastUpdateId'];
        return $this->depthData($symbol, $json);
    }

    /**
     * balances get balances for the account assets
     *
     * $balances = $api->balances($ticker);
     *
     * @param bool $priceData array of the symbols balances are required for
     * @return array with error message or array of balances
     * @throws \Exception
     */
    public function balances($priceData = false)
    {
        if (is_array($priceData) === false) {
            $priceData = false;
        }

        $account = $this->httpRequest("v3/account", "GET", [], true);

        if (is_array($account) === false) {
            echo "Error: unable to fetch your account details" . PHP_EOL;
        }

        if (isset($account['balances']) === false) {
            echo "Error: your balances were empty or unset" . PHP_EOL;
        }

        return $this->balanceData($account, $priceData);
    }

    /**
     * httpRequest curl wrapper for all http api requests.
     * You can't call this function directly, use the helper functions
     *
     * @see buy()
     * @see sell()
     * @see marketBuy()
     * @see marketSell() $this->httpRequest( "https://api.binance.com/api/v1/ticker/24hr");
     *
     * @param $url string the endpoint to query, typically includes query string
     * @param $method string this should be typically GET, POST or DELETE
     * @param $params array addtional options for the request
     * @param $signed bool true or false sign the request with api secret
     * @return array containing the response
     * @throws \Exception
     */
    private function httpRequest(string $url, string $method = "GET", array $params = [], bool $signed = false)
    {
        if (function_exists('curl_init') === false) {
            throw new \Exception("Sorry cURL is not installed!");
        }

        if ($this->caOverride === false) {
            if (file_exists(getcwd() . '/ca.pem') === false) {
                $this->downloadCurlCaBundle();
            }
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, $this->httpDebug);
        $query = http_build_query($params, '', '&');

        // signed with params
        if ($signed === true) {
            if (empty($this->api_key)) {
                throw new \Exception("signedRequest error: API Key not set!");
            }

            if (empty($this->api_secret)) {
                throw new \Exception("signedRequest error: API Secret not set!");
            }

            $base = $this->apiBaseUrl;
            $ts = (microtime(true) * 1000) + $this->info['timeOffset'];
            $params['timestamp'] = number_format($ts, 0, '.', '');
            if (isset($params['wapi'])) {
                unset($params['wapi']);
                $base = $this->wapi;
            }
            $query = http_build_query($params, '', '&');
            $signature = hash_hmac('sha256', $query, $this->api_secret);
			if ($method === "POST") {
				$endpoint = $base . $url;
				$params['signature'] = $signature; // signature needs to be inside BODY
				$query = http_build_query($params, '', '&'); // rebuilding query
			} else
				$endpoint = $base . $url . '?' . $query . '&signature=' . $signature;
            curl_setopt($curl, CURLOPT_URL, $endpoint);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'X-MBX-APIKEY: ' . $this->api_key,
            ));
        }
        // params so buildquery string and append to url
        else if (count($params) > 0) {
            curl_setopt($curl, CURLOPT_URL, $this->apiBaseUrl . $url . '?' . $query);
        }
        // no params so just the base url
        else {
            curl_setopt($curl, CURLOPT_URL, $this->apiBaseUrl . $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'X-MBX-APIKEY: ' . $this->api_key,
            ));
        }
        curl_setopt($curl, CURLOPT_USERAGENT, "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)");
        // Post and postfields
        if ($method === "POST") {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        }
        // Delete Method
        if ($method === "DELETE") {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        }

        // PUT Method
        if ($method === "PUT") {
            curl_setopt($curl, CURLOPT_PUT, true);
        }

        // proxy settings
        if (is_array($this->proxyConf)) {
            curl_setopt($curl, CURLOPT_PROXY, $this->getProxyUriString());
            if (isset($this->proxyConf['user']) && isset($this->proxyConf['pass'])) {
                curl_setopt($curl, CURLOPT_PROXYUSERPWD, $this->proxyConf['user'] . ':' . $this->proxyConf['pass']);
            }
        }
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        // headers will proceed the output, json_decode will fail below
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        // set user defined curl opts last for overriding
        foreach ($this->curlOpts as $key => $value) {
            curl_setopt($curl, constant($key), $value);
        }

        if ($this->caOverride === false) {
            if (file_exists(getcwd() . '/ca.pem') === false) {
                $this->downloadCurlCaBundle();
            }
        }

        $output = curl_exec($curl);
        // Check if any error occurred
        if (curl_errno($curl) > 0) {
            // should always output error, not only on httpdebug
            // not outputing errors, hides it from users and ends up with tickets on github
            echo 'Curl error: ' . curl_error($curl) . "\n";
            return [];
        }
        curl_close($curl);
        $json = json_decode($output, true);
        if (isset($json['msg'])) {
            // should always output error, not only on httpdebug
            // not outputing errors, hides it from users and ends up with tickets on github
            echo "signedRequest error: {$output}" . PHP_EOL;
        }
        $this->transfered += strlen($output);
        $this->requestCount++;
        return $json;
    }

    /**
     * order formats the orders before sending them to the curl wrapper function
     * You can call this function directly or use the helper functions
     *
     * @see buy()
     * @see sell()
     * @see marketBuy()
     * @see marketSell() $this->httpRequest( "https://api.binance.com/api/v1/ticker/24hr");
     *
     * @param $side string typically "BUY" or "SELL"
     * @param $symbol string to buy or sell
     * @param $quantity string in the order
     * @param $price string for the order
     * @param $type string is determined by the symbol bu typicall LIMIT, STOP_LOSS_LIMIT etc.
     * @param $flags array additional transaction options
     * @param $test bool whether to test or not, test only validates the query
     * @return array containing the response
     * @throws \Exception
     */
    public function order(string $side, string $symbol, $quantity, $price, string $type = "LIMIT", array $flags = [], bool $test = false)
    {
        $opt = [
            "symbol" => $symbol,
            "side" => $side,
            "type" => $type,
            "quantity" => $quantity,
            "recvWindow" => 60000,
        ];

        // someone has preformated there 8 decimal point double already
        // dont do anything, leave them do whatever they want
        if (gettype($price) !== "string") {
            // for every other type, lets format it appropriately
            $price = number_format($price, 8, '.', '');
        }

        if (is_numeric($quantity) === false) {
            // WPCS: XSS OK.
            echo "warning: quantity expected numeric got " . gettype($quantity) . PHP_EOL;
        }

        if (is_string($price) === false) {
            // WPCS: XSS OK.
            echo "warning: price expected string got " . gettype($price) . PHP_EOL;
        }

        if ($type === "LIMIT" || $type === "STOP_LOSS_LIMIT" || $type === "TAKE_PROFIT_LIMIT") {
            $opt["price"] = $price;
            $opt["timeInForce"] = "GTC";
        }

        if (isset($flags['stopPrice'])) {
            $opt['stopPrice'] = $flags['stopPrice'];
        }

        if (isset($flags['icebergQty'])) {
            $opt['icebergQty'] = $flags['icebergQty'];
        }

        if (isset($flags['newOrderRespType'])) {
            $opt['newOrderRespType'] = $flags['newOrderRespType'];
        }

        $qstring = ($test === false) ? "v3/order" : "v3/order/test";
        return $this->httpRequest($qstring, "POST", $opt, true);
    }

    /**
     * candlesticks get the candles for the given intervals
     * 1m,3m,5m,15m,30m,1h,2h,4h,6h,8h,12h,1d,3d,1w,1M
     *
     * $candles = $api->candlesticks("BNBBTC", "5m");
     *
     * @param $symbol string to query
     * @param $interval string to request
     * @param $limit int limit the amount of candles
     * @param $startTime string request candle information starting from here
     * @param $endTime string request candle information ending here
     * @return array containing the response
     * @throws \Exception
     */
    public function klines(string $symbol, string $interval = "5m", int $limit = null, $startTime = null, $endTime = null)
    {
        if (!isset($this->charts[$symbol])) {
            $this->charts[$symbol] = [];
        }

        $opt = [
            "symbol" => $symbol,
            "interval" => $interval,
        ];

        if ($limit) {
            $opt["limit"] = $limit;
        }

        if ($startTime) {
            $opt["startTime"] = $startTime;
        }

        if ($endTime) {
            $opt["endTime"] = $endTime;
        }

        $response = $this->httpRequest("v1/klines", "GET", $opt);

        if (is_array($response) === false) {
            return [];
        }

        if (count($response) === 0) {
            echo "warning: v1/klines returned empty array, usually a blip in the connection or server" . PHP_EOL;
            return [];
        }

        $ticks = $this->chartData($symbol, $interval, $response);
        $this->charts[$symbol][$interval] = $ticks;
        return $ticks;
    }

    /**
     * balanceData Converts all your balances into a nice array
     * If priceData is passed from $api->prices() it will add btcValue & btcTotal to each symbol
     * This function sets $btc_value which is your estimated BTC value of all assets combined and $btc_total which includes amount on order
     *
     * $candles = $api->candlesticks("BNBBTC", "5m");
     *
     * @param $array array of your balances
     * @param $priceData array of prices
     * @return array containing the response
     */
    private function balanceData(array $array, $priceData)
    {
        $balances = [];

        if (is_array($priceData)) {
            $btc_value = $btc_total = 0.00;
        }

        if (empty($array) || empty($array['balances'])) {
            // WPCS: XSS OK.
            echo "balanceData error: Please make sure your system time is synchronized: call \$api->useServerTime() before this function" . PHP_EOL;
            echo "ERROR: Invalid request. Please double check your API keys and permissions." . PHP_EOL;
            return [];
        }

        foreach ($array['balances'] as $obj) {
            $asset = $obj['asset'];
            $balances[$asset] = [
                "available" => $obj['free'],
                "onOrder" => $obj['locked'],
                "btcValue" => 0.00000000,
                "btcTotal" => 0.00000000,
            ];

            if (is_array($priceData) === false) {
                continue;
            }

            if ($obj['free'] + $obj['locked'] < 0.00000001) {
                continue;
            }

            if ($asset === 'BTC') {
                $balances[$asset]['btcValue'] = $obj['free'];
                $balances[$asset]['btcTotal'] = $obj['free'] + $obj['locked'];
                $btc_value += $obj['free'];
                $btc_total += $obj['free'] + $obj['locked'];
                continue;
            }

            $symbol = $asset . 'BTC';

            if ($symbol === 'BTCUSDT') {
                $btcValue = number_format($obj['free'] / $priceData['BTCUSDT'], 8, '.', '');
                $btcTotal = number_format(($obj['free'] + $obj['locked']) / $priceData['BTCUSDT'], 8, '.', '');
            } elseif (isset($priceData[$symbol]) === false) {
                $btcValue = $btcTotal = 0;
            } else {
                $btcValue = number_format($obj['free'] * $priceData[$symbol], 8, '.', '');
                $btcTotal = number_format(($obj['free'] + $obj['locked']) * $priceData[$symbol], 8, '.', '');
            }

            $balances[$asset]['btcValue'] = $btcValue;
            $balances[$asset]['btcTotal'] = $btcTotal;
            $btc_value += $btcValue;
            $btc_total += $btcTotal;
        }
        if (is_array($priceData)) {
            uasort($balances, function ($opA, $opB) {
                return $opA['btcValue'] < $opB['btcValue'];
            });
            $this->btc_value = $btc_value;
            $this->btc_total = $btc_total;
        }
        return $balances;
    }

    /**
     * balanceHandler Convert balance WebSocket data into array
     *
     * $data = $this->balanceHandler( $json );
     *
     * @param $json array data to convert
     * @return array
     */
    private function balanceHandler(array $json)
    {
        $balances = [];
        foreach ($json as $item) {
            $asset = $item->a;
            $available = $item->f;
            $onOrder = $item->l;
            $balances[$asset] = [
                "available" => $available,
                "onOrder" => $onOrder,
            ];
        }
        return $balances;
    }

    /**
     * tickerStreamHandler Convert WebSocket ticker data into array
     *
     * $data = $this->tickerStreamHandler( $json );
     *
     * @param $json object data to convert
     * @return array
     */
    private function tickerStreamHandler(\stdClass $json)
    {
        return [
            "eventType" => $json->e,
            "eventTime" => $json->E,
            "symbol" => $json->s,
            "priceChange" => $json->p,
            "percentChange" => $json->P,
            "averagePrice" => $json->w,
            "prevClose" => $json->x,
            "close" => $json->c,
            "closeQty" => $json->Q,
            "bestBid" => $json->b,
            "bestBidQty" => $json->B,
            "bestAsk" => $json->a,
            "bestAskQty" => $json->A,
            "open" => $json->o,
            "high" => $json->h,
            "low" => $json->l,
            "volume" => $json->v,
            "quoteVolume" => $json->q,
            "openTime" => $json->O,
            "closeTime" => $json->C,
            "firstTradeId" => $json->F,
            "lastTradeId" => $json->L,
            "numTrades" => $json->n,
        ];
    }

    /**
     * tickerStreamHandler Convert WebSocket trade execution into array
     *
     * $data = $this->executionHandler( $json );
     *
     * @param \stdClass $json object data to convert
     * @return array
     */
    private function executionHandler(\stdClass $json)
    {
        return [
            "symbol" => $json->s,
            "side" => $json->S,
            "orderType" => $json->o,
            "quantity" => $json->q,
            "price" => $json->p,
            "executionType" => $json->x,
            "orderStatus" => $json->X,
            "rejectReason" => $json->r,
            "orderId" => $json->i,
            "clientOrderId" => $json->c,
            "orderTime" => $json->T,
            "eventTime" => $json->E,
        ];
    }

    /**
     * chartData Convert kline data into object
     *
     * $object = $this->chartData($symbol, $interval, $ticks);
     *
     * @param $symbol string of your currency
     * @param $interval string the time interval
     * @param $ticks array of the canbles array
     * @return array object of the chartdata
     */
    private function chartData(string $symbol, string $interval, array $ticks)
    {
        if (!isset($this->info[$symbol])) {
            $this->info[$symbol] = [];
        }

        if (!isset($this->info[$symbol][$interval])) {
            $this->info[$symbol][$interval] = [];
        }

        $output = [];
        foreach ($ticks as $tick) {
            list($openTime, $open, $high, $low, $close, $assetVolume, $closeTime, $baseVolume, $trades, $assetBuyVolume, $takerBuyVolume, $ignored) = $tick;
            $output[$openTime] = [
                "open" => $open,
                "high" => $high,
                "low" => $low,
                "close" => $close,
                "volume" => $baseVolume,
                "openTime" => $openTime,
                "closeTime" => $closeTime,
                "assetVolume" => $assetVolume,
                "baseVolume" => $baseVolume,
                "trades" => $trades,
                "assetBuyVolume" => $assetBuyVolume,
                "takerBuyVolume" => $takerBuyVolume,
                "ignored" => $ignored,
            ];
        }

        if (isset($openTime)) {
            $this->info[$symbol][$interval]['firstOpen'] = $openTime;
        }

        return $output;
    }

    /**
     * tradesData Convert aggTrades data into easier format
     *
     * $tradesData = $this->tradesData($trades);
     *
     * @param $trades array of trade information
     * @return array easier format for trade information
     */
    private function tradesData(array $trades)
    {
        $output = [];
        foreach ($trades as $trade) {
            $price = $trade['p'];
            $quantity = $trade['q'];
            $timestamp = $trade['T'];
            $maker = $trade['m'] ? 'true' : 'false';
            $output[] = [
                "price" => $price,
                "quantity" => $quantity,
                "timestamp" => $timestamp,
                "maker" => $maker,
            ];
        }
        return $output;
    }

    /**
     * bookPriceData Consolidates Book Prices into an easy to use object
     *
     * $bookPriceData = $this->bookPriceData($array);
     *
     * @param $array array book prices
     * @return array easier format for book prices information
     */
    private function bookPriceData(array $array)
    {
        $bookprices = [];
        foreach ($array as $obj) {
            $bookprices[$obj['symbol']] = [
                "bid" => $obj['bidPrice'],
                "bids" => $obj['bidQty'],
                "ask" => $obj['askPrice'],
                "asks" => $obj['askQty'],
            ];
        }
        return $bookprices;
    }

    /**
     * priceData Converts Price Data into an easy key/value array
     *
     * $array = $this->priceData($array);
     *
     * @param $array array of prices
     * @return array of key/value pairs
     */
    private function priceData(array $array)
    {
        $prices = [];
        foreach ($array as $obj) {
            $prices[$obj['symbol']] = $obj['price'];
        }
        return $prices;
    }

    /**
     * cumulative Converts depth cache into a cumulative array
     *
     * $cumulative = $api->cumulative($depth);
     *
     * @param $depth array cache array
     * @return array cumulative depth cache
     */
    public function cumulative(array $depth)
    {
        $bids = [];
        $asks = [];
        $cumulative = 0;
        foreach ($depth['bids'] as $price => $quantity) {
            $cumulative += $quantity;
            $bids[] = [
                $price,
                $cumulative,
            ];
        }
        $cumulative = 0;
        foreach ($depth['asks'] as $price => $quantity) {
            $cumulative += $quantity;
            $asks[] = [
                $price,
                $cumulative,
            ];
        }
        return [
            "bids" => $bids,
            "asks" => array_reverse($asks),
        ];
    }

    /**
     * highstock Converts Chart Data into array for highstock & kline charts
     *
     * $highstock = $api->highstock($chart, $include_volume);
     *
     * @param $chart array
     * @param $include_volume bool for inclusion of volume
     * @return array highchart data
     */
    public function highstock(array $chart, bool $include_volume = false)
    {
        $array = [];
        foreach ($chart as $timestamp => $obj) {
            $line = [
                $timestamp,
                floatval($obj['open']),
                floatval($obj['high']),
                floatval($obj['low']),
                floatval($obj['close']),
            ];
            if ($include_volume) {
                $line[] = floatval($obj['volume']);
            }

            $array[] = $line;
        }
        return $array;
    }

    /**
     * first Gets first key of an array
     *
     * $first = $api->first($array);
     *
     * @param $array array
     * @return string key or null
     */
    public function first(array $array)
    {
        if (count($array) > 0) {
            return array_keys($array)[0];
        }
        return null;
    }

    /**
     * last Gets last key of an array
     *
     * $last = $api->last($array);
     *
     * @param $array array
     * @return string key or null
     */
    public function last(array $array)
    {
        if (count($array) > 0) {
            return array_keys(array_slice($array, -1))[0];
        }
        return null;
    }

    /**
     * displayDepth Formats nicely for console output
     *
     * $outputString = $api->displayDepth($array);
     *
     * @param $array array
     * @return string of the depth information
     */
    public function displayDepth(array $array)
    {
        $output = '';
        foreach ([
            'asks',
            'bids',
        ] as $type) {
            $entries = $array[$type];
            if ($type === 'asks') {
                $entries = array_reverse($entries);
            }

            $output .= "{$type}:" . PHP_EOL;
            foreach ($entries as $price => $quantity) {
                $total = number_format($price * $quantity, 8, '.', '');
                $quantity = str_pad(str_pad(number_format(rtrim($quantity, '.0')), 10, ' ', STR_PAD_LEFT), 15);
                $output .= "{$price} {$quantity} {$total}" . PHP_EOL;
            }
            // echo str_repeat('-', 32).PHP_EOL;
        }
        return $output;
    }

    /**
     * depthData Formats depth data for nice display
     *
     * $array = $this->depthData($symbol, $json);
     *
     * @param $symbol string to display
     * @param $json array of the depth infomration
     * @return array of the depth information
     */
    private function depthData(string $symbol, array $json)
    {
        $bids = $asks = [];
        foreach ($json['bids'] as $obj) {
            $bids[$obj[0]] = $obj[1];
        }
        foreach ($json['asks'] as $obj) {
            $asks[$obj[0]] = $obj[1];
        }
        return $this->depthCache[$symbol] = [
            "bids" => $bids,
            "asks" => $asks,
        ];
    }
    
    /**
     * roundStep rounds quantity with stepSize
     * @param $qty quantity
     * @param $stepSize parameter from exchangeInfo
     * @return rounded value. example: roundStep(1.2345, 0.1) = 1.2
     *
     */
    public function roundStep($qty, $stepSize = 0.1) {
        $precision = strlen(substr(strrchr(rtrim($stepSize,'0'), '.'), 1));
        return round((($qty / $stepSize) | 0) * $stepSize, $precision);
    }
    
    /**
     * roundTicks rounds price with tickSize
     * @param $value price
     * @param $tickSize parameter from exchangeInfo
     * @return rounded value. example: roundStep(1.2345, 0.1) = 1.2
     *
     */
    public function roundTicks($price, $tickSize) {
        $precision = strlen(rtrim(substr($tickSize,strpos($tickSize, '.', 1) + 1), '0'));
        return number_format($price, $precision, '.', '');
    }
    
    /**
     * getTransfered gets the total transfered in b,Kb,Mb,Gb
     *
     * $transfered = $api->getTransfered();
     *
     * @return string showing the total transfered
     */
    public function getTransfered()
    {
        $base = log($this->transfered, 1024);
        $suffixes = array(
            '',
            'K',
            'M',
            'G',
            'T',
        );
        return round(pow(1024, $base - floor($base)), 2) . ' ' . $suffixes[floor($base)];
    }

    /**
     * getRequestCount gets the total number of API calls
     *
     * $apiCount = $api->getRequestCount();
     *
     * @return int get the total number of api calls
     */
    public function getRequestCount()
    {
        return $this->requestCount;
    }

    /**
     * addToTransfered add interger bytes to the total transfered
     * also incrementes the api counter
     *
     * $apiCount = $api->addToTransfered( $int );
     *
     * @return null
     */
    public function addToTransfered(int $int)
    {
        $this->transfered += $int;
        $this->requestCount++;
    }

    /*
     * WebSockets
     */

    /**
     * depthHandler For WebSocket Depth Cache
     *
     * $this->depthHandler($json);
     *
     * @param $json array of depth bids and asks
     * @return null
     */
    private function depthHandler(array $json)
    {
        $symbol = $json['s'];
        if ($json['u'] <= $this->info[$symbol]['firstUpdate']) {
            return;
        }

        foreach ($json['b'] as $bid) {
            $this->depthCache[$symbol]['bids'][$bid[0]] = $bid[1];
            if ($bid[1] == "0.00000000") {
                unset($this->depthCache[$symbol]['bids'][$bid[0]]);
            }

        }
        foreach ($json['a'] as $ask) {
            $this->depthCache[$symbol]['asks'][$ask[0]] = $ask[1];
            if ($ask[1] == "0.00000000") {
                unset($this->depthCache[$symbol]['asks'][$ask[0]]);
            }

        }
    }

    /**
     * chartHandler For WebSocket Chart Cache
     *
     * $this->chartHandler($symbol, $interval, $json);
     *
     * @param $symbol string to sort
     * @param $interval string time
     * @param \stdClass $json object time
     * @return null
     */
    private function chartHandler(string $symbol, string $interval, \stdClass $json)
    {
        if (!$this->info[$symbol][$interval]['firstOpen']) { // Wait for /kline to finish loading
            $this->chartQueue[$symbol][$interval][] = $json;
            return;
        }
        $chart = $json->k;
        $symbol = $json->s;
        $interval = $chart->i;
        $tick = $chart->t;
        if ($tick < $this->info[$symbol][$interval]['firstOpen']) {
            return;
        }
        // Filter out of sync data
        $open = $chart->o;
        $high = $chart->h;
        $low = $chart->l;
        $close = $chart->c;
        $volume = $chart->q; // +trades buyVolume assetVolume makerVolume
        $this->charts[$symbol][$interval][$tick] = [
            "open" => $open,
            "high" => $high,
            "low" => $low,
            "close" => $close,
            "volume" => $volume,
        ];
    }

    /**
     * sortDepth Sorts depth data for display & getting highest bid and lowest ask
     *
     * $sorted = $api->sortDepth($symbol, $limit);
     *
     * @param $symbol string to sort
     * @param $limit int depth
     * @return null
     */
    public function sortDepth(string $symbol, int $limit = 11)
    {
        $bids = $this->depthCache[$symbol]['bids'];
        $asks = $this->depthCache[$symbol]['asks'];
        krsort($bids);
        ksort($asks);
        return [
            "asks" => array_slice($asks, 0, $limit, true),
            "bids" => array_slice($bids, 0, $limit, true),
        ];
    }

    /**
     * terminate Terminates websocket endpoints. View endpoints first: print_r($api->subscriptions)
     *
     * $api->terminate('ethbtc_kline@5m');
     *
     * @return null
     */
    public function terminate($endpoint)
    {
        // check if $this->subscriptions[$endpoint] is true otherwise error
        $this->subscriptions[$endpoint] = false;
    }

    /**
     * Due to ongoing issues with out of date wamp CA bundles
     * This function downloads ca bundle for curl website
     * and uses it as part of the curl options
     */
    private function downloadCurlCaBundle()
    {
        $output_filename = getcwd() . "/ca.pem";

        if (is_writable(getcwd()) === false) {
            die(getcwd() . " folder is not writeable, plese check your permissions");
        }

        $host = "https://curl.haxx.se/ca/cacert.pem";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $host);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);

        if ($result === false) {
            echo "Unable to to download the CA bundle $host" . PHP_EOL;
            return;
        }

        $fp = fopen($output_filename, 'w');

        if ($fp === false) {
            echo "Unable to write $output_filename, please check permissions on folder" . PHP_EOL;
            return;
        }

        fwrite($fp, $result);
        fclose($fp);
    }
}
