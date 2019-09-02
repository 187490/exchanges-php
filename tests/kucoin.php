<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/bitmex-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

include 'key_secret.php';

//kucoin
$key=$keysecret['ku']['kucoin']['key'];
$secret=$keysecret['ku']['kucoin']['secret'];
$passphrase=$keysecret['ku']['kucoin']['passphrase'];
$host=$keysecret['ku']['kucoin']['host'];
$kucoin=new Exchanges('kucoin',$key,$secret,$passphrase,$host);

//kumex
$key=$keysecret['ku']['kumex']['key'];
$secret=$keysecret['ku']['kumex']['secret'];
$passphrase=$keysecret['ku']['kumex']['passphrase'];
$host=$keysecret['ku']['kumex']['host'];
$kumex=new Exchanges('kucoin',$key,$secret,$passphrase,$host);


//Support for more request Settings
$kucoin->setOptions([
    //Set the request timeout to 60 seconds by default
    'timeout'=>10,
    
    //If you are developing locally and need an agent, you can set this
    'proxy'=>true,
    //More flexible Settings
    /* 'proxy'=>[
     'http'  => 'http://127.0.0.1:12333',
     'https' => 'http://127.0.0.1:12333',
     'no'    =>  ['.cn']
     ], */
    //Close the certificate
    'verify'=>false,
]);

$kumex->setOptions([
    'proxy'=>true,
    'verify'=>false,
]);


$action=intval($_GET['action'] ?? 0);//http pattern
if(empty($action)) $action=intval($argv[1]);//cli pattern

switch ($action){
    //***********system error
    //exception testing
    case 1:{
        $result=$kucoin->trader()->buy([
            '_symbol'=>'exception testing',
            '_number'=>'1',
        ]);
        
        /*
         Array
        (
            [_error] => Array
                (
                    [error] => Array
                        (
                            [message] => symbol is invalid
                            [name] => HTTPError
                        )
                    [_method] => POST
                    [_url] => https://testnet.bitmex.com/api/v1/order
                    [_httpcode] => 400
                )
        )
         */
        break;
    }
    
    //***********Trader Market
    case 100:{
        $result=$kucoin->trader()->buy([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 101:{
        $result=$kucoin->trader()->sell([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 110:{
        //The original parameters
        $result=$kucoin->trader()->buy([
            'symbol'=>'XBTUSD',
            'orderQty'=>'1',
            'ordType'=>'Market',
        ]);
        break;
    }
    case 111:{
        //The original parameters
        $result=$kucoin->trader()->sell([
            'symbol'=>'XBTUSD',
            'orderQty'=>'1',
            'ordType'=>'Market',
        ]);
        break;
    }
    
    //***********Trader Limit
    case 200:{
        $result=$kucoin->trader()->buy([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            '_price'=>100
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    case 201:{
        $result=$kucoin->trader()->sell([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            '_price'=>999999
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 211:{
        //The original parameters
        $result=$kucoin->trader()->buy([
            'symbol'=>'XBTUSD',
            'price'=>'100',
            'orderQty'=>'1',
            'ordType'=>'Limit',
        ]);
        break;
    }
    
    case 212:{
        //The original parameters
        $result=$kucoin->trader()->sell([
            'symbol'=>'XBTUSD',
            'price'=>'9999',
            'orderQty'=>'1',
            'ordType'=>'Limit',
        ]);
        break;
    }
    
    case 300:{
        $result=$kucoin->trader()->show([
            '_symbol'=>'XBTUSD',
            '_order_id'=>'7d03ac2a-b24d-f48c-95f4-2628e6411927',
            //'_client_id'=>'custom ID',
        ]);
        break;
    }
    
    case 301:{
        //The original parameters
        $result=$kucoin->trader()->show([
            'symbol'=>'XBTUSD',
            'orderID'=>'807772e6-fc86-ddcc-9237-a3d8b36e6bfe',
        ]);
        break;
    }
    
    case 302:{
        //The original parameters
        $result=$kucoin->trader()->cancel([
            'symbol'=>'XBTUSD',
            'orderID'=>'1bffaa7f-c945-3b78-e10a-37c87bff6152',
        ]);
        break;
    }
    
    case 303:{
        //bargaining transaction
        $result=$kucoin->account()->get([
            '_symbol'=>'XBTUSD'
        ]);
        break;
    }
    
    //***********Complete flow
    case 400:{
        $result=$kucoin->trader()->buy([
            '_symbol'=>'XBTUSD',
            '_number'=>'1',
            '_price'=>100
            //'_client_id'=>'custom ID',
        ]);
        print_r($result);
        
        $result=$kucoin->trader()->cancel([
            '_symbol'=>'XBTUSD',
            '_order_id'=>$result['_order_id'],
            //'_client_id'=>'custom ID',
        ]);
        
        break;
    }
    
    //
    case 500:{
        $client_id=rand(10000,99999).rand(10000,99999);
        
        //The original object，
        $result=$kucoin->getPlatform()->order()->post([
            'clientOid'=>$client_id,
            'side'=>'buy',
            'symbol'=>'ETH-BTC',
            'price'=>'0.0001',
            'size'=>'10',
        ]);
        print_r($result);
        sleep(1);
        
        //Place an Order
        $result=$kucoin->getPlatform()->order()->get([
            'orderId'=>$result['data']['orderId']
        ]);
        print_r($result);
        sleep(1);
    
        $result=$kucoin->getPlatform()->order()->delete([
            'orderId'=>$result['data']['id']
        ]);
        
        break;
    }
    
    //***********Kumex
    case 600:{
        $clientOid=rand(10000,99999).rand(10000,99999);
        
        $result=$kumex->getPlatform()->order()->post([
            'clientOid'=>$clientOid,
            'side'=>'buy',
            'symbol'=>'XBTUSDM',
            'leverage'=>10,
            
            'price'=>9000,
            'size'=>10,
        ]);
        print_r($result);
        sleep(1);
        
        $result=$kumex->getPlatform()->order()->get([
            'order-id'=>$result['data']['orderId'],
        ]);
        print_r($result);
        sleep(1);
        
        $result=$kumex->getPlatform()->order()->delete([
            'order-id'=>$result['data']['id'],
        ]);
        break;
    }
    
    default:{
        echo 'nothing';
        //exit;
    }
}

print_r($result);