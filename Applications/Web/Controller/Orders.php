<?php

namespace Web\Controller;

use \Library\Http;

class Orders extends Base
{
	public function index()
	{
		$this->render('orders.html');
	}

	public function data($return = false)
	{
		$data = $this->db()
					->select('*')
					->from('orders')
					->order('id desc')
					->query();
		if(is_array($data)){
			foreach ($data as $key => &$value) {
				$value['amount'] = $this->format(bcadd($value['amount'], $value['frozen'], 8));
				$value['unfrozen'] = $this->format($value['amount']);
				$value['usdt_amount'] = $this->format($value['usdt_amount']);
				$value['usdt_price'] = $this->format($value['usdt_price']);
				$value['now_amount'] = $this->format($value['now_amount']);
				$value['now_price'] = $this->format($value['now_price']);
				$value['gainloss'] = $this->format(bcsub($value['now_amount'], $value['usdt_amount'],8));
				$value['gainloss_rate'] = $value['usdt_amount']==0 ? 
					'0.00%' : 
					bcmul(bcsub(bcdiv($value['now_price'], $value['usdt_price'],6), 1, 6), 100,2).'%';
				$value['symbol'] = $value['currency'].'usdt';
			}
		}
		$this->success($data);
	}

	private function format($val)
	{
		$tmp = rtrim($val, '0');
		if(substr($tmp, -1) == '.'){
			$tmp .= '00';
		}
		return $tmp;
	}

	public function sync($currency)
	{
		$datas = $this->binance()->orders();
		if(is_array($datas) && !empty($datas)){
			foreach($datas as $data){
                $cols = [];
                $cols['client_id'] = $data['clientOrderId'];
                $cols['order_id'] = $data['orderId'];
                $cols['symbol'] = $data['symbol'];
                $cols['source'] = $data[''];
                $cols['price'] = $data['price'];
                $cols['base'] = $data[''];
                $cols['quote'] = $data[''];
                $cols['fee'] = $data['commissionAsset'];
                $cols['base_amount'] = $data['qty'];
                $cols['quote_amount'] = $data['quoteQty'];
                $cols['fee_amount'] = $data['commission'];
                $cols['type'] = $data['type'];
                $cols['direction'] = $data['side'];
                $cols['state'] = $data['status'];
                $cols['dateline'] = bcdiv($data['time'], 1000, 0);
                $cols['filled_amount'] = $data['qty'];
                $cols['balanced_amount'] = 0;
                $cols['balanced'] = 0;
                $row = $this->db()->select('id,order_id')->from('orders')->where('order_id', $data['orderId'])->row();
                if(!isset($row['id'])){
                    $this->db()->insert('orders')->cols($cols)->query();
                }else{
                    $this->db()->update('orders')->setCols($cols)->where('order_id', $data['id'])->query();
                }
			}
			$this->db()->query('UPDATE orders o,symbols s SET o.base=s.base,o.quote=s.quote WHERE o.symbol=s.symbol;');
		}
		$this->success(['currency'=>$currency, 'price'=>$price]);
	}

    private function save($datas)
    {
        foreach($datas as $data){
            $cols = [];
            $cols['oid'] = $data['id'];
            $cols['symbol'] = $data['symbol'];
            $cols['source'] = $data['source'];
            $cols['base_currency'] = '';
            $cols['quote_currency'] = '';
            $cols['price'] = $data['price'];
            $cols['amount'] = $data['amount'];
            $cols['type'] = $data['type'];
            $cols['direction'] = $this->direction($data['type']);
            $cols['account_id'] = $data['account-id'];
            $cols['created_at'] = $data['created-at'];
            $cols['finished_at'] = $data['finished-at'];
            $cols['canceled_at'] = $data['canceled-at'];
            $cols['state'] = $data['state'];
            $cols['field_amount'] = $data['field-amount'];
            $cols['field_cash_amount'] = $data['field-cash-amount'];
            $cols['field_fees'] = $data['field-fees'];
            $row = $this->db()->select('id,oid')->from('orders')->where('oid', $data['id'])->row();
            if(!isset($row['id'])){
                $this->db()->insert('orders')->cols($cols)->query();
            }else{
                $this->db()->update('orders')->setCols($cols)->where('oid', $data['id'])->query();
            }
        }
        $this->db()->query('UPDATE orders o,symbols s SET o.base_currency=s.base_currency,o.quote_currency=s.quote_currency WHERE o.symbol=s.symbol;');
        unset($datas, $data, $cols);
    }
}