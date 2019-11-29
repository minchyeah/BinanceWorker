
DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) unsigned DEFAULT 0 COMMENT '父级订单编号',
  `client_id` bigint(20) unsigned DEFAULT 0 COMMENT '客户订单编号',
  `order_id` bigint(20) unsigned DEFAULT 0 COMMENT '交易所订单编号',
  `symbol` varchar(20) DEFAULT '' COMMENT '交易对',
  `source` varchar(20) DEFAULT '' COMMENT '订单来源',
  `price` decimal(20,10) unsigned DEFAULT 0 COMMENT '交易价格',
  `base` varchar(10) DEFAULT '' COMMENT '交易对中的基础币种',
  `quote` varchar(10) DEFAULT '' COMMENT '交易对中的报价币种',
  `fee` varchar(10) DEFAULT '' COMMENT '手续费币种',
  `base_amount` decimal(20,10) unsigned DEFAULT 0 COMMENT '交易量',
  `quote_amount` decimal(20,10) unsigned DEFAULT 0 COMMENT '交易金额',
  `fee_amount` decimal(20,10) unsigned DEFAULT 0 COMMENT '手续费金额',
  `type` varchar(20) DEFAULT '' COMMENT 'LIMIT 限价单,MARKET 市价单,STOP_LOSS 止损单,STOP_LOSS_LIMIT 限价止损单,TAKE_PROFIT 止盈单,TAKE_PROFIT_LIMIT 限价止盈单,LIMIT_MAKER 限价做市单',
  `direction` varchar(5) DEFAULT ''  COMMENT 'BUY 买, SELL 卖',
  `state` varchar(10) DEFAULT '' COMMENT 'NEW 新建订单,PARTIALLY_FILLED 部分成交,FILLED 全部成交,CANCELED 已撤销,PENDING_CANCEL 正在撤销中,REJECTED 订单被拒绝,EXPIRED 订单过期',
  `dateline` int(10) unsigned DEFAULT 0 COMMENT '时间',
  `filled_amount` decimal(20,10) unsigned DEFAULT 0 COMMENT '最终交易量',
  `balanced_amount` decimal(20,10) unsigned DEFAULT 0 COMMENT '结算交易量',
  `balanced` tinyint(2) unsigned DEFAULT 0 COMMENT '是否已结算',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `symbol` (`symbol`),
  KEY `direction` (`direction`),
  KEY `quote` (`quote`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

