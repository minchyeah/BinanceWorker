<?php

namespace Timer\Trade;

use Timer\Base;

/**
 * 定时检查是否可交易
 * @author Minch<yeah@minch.me>
 * @since 2019-08-26
 */
class Check extends Base
{
    /**
     * 定时检查是否可交易
     */
    public function trigger()
    {
        if(!$this->getlock()){
            return false;
        }
        $now = time();
        $interval = intval(\Config\Timer::$modules['Trade\Check']);

        // check trade every 5min
        if( ($now % 300) < $interval){
            //foreach (\Config\Binance::$symbols as $symbol) {
                $this->call('Business\Trade\Check', array('symbol'=>'BTCUSDT'));
            //}
        }

        unset($now,$interval,$symbol);

        $this->unlock();
        $this->wait();
    }
}