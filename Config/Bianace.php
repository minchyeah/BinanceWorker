<?php

namespace Config;

/**
 * GlobalData变量共享组件配置
 * API 密钥：
 * PFnZzFG3BuEjixBkYUHKvFPBBhbl9CJ28lqpET8oX93qgfbulEpP43j77pLmYhJD
 * 密钥:
 * OJMGRALLVial4JqJRk9vcBALXJFL8oO6dEzxHCBmNdtFwwMMtbyRv2b7vUEJyaIO
 * @author minch
 */
class Binance
{
    /**
     * 监听的ip地址，分布式部署时使用内网ip
     * @var string
     */
    public static $address = '127.0.0.1';

    /**
     * 网关监听端口
     * @var number
     */
    public static $port = 12207;

    /**
     * 数据是否持久化
     * @var boolean
     */
    public static $persistence = true;

    /**
     * 数据持久化文件路径
     * @var string
     */
    public static $datapath = '/tmp/GlobalData_cache.php';
}
