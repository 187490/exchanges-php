<?php

/**
 * @author lin <465382251@qq.com>
 * 
 * Most of them are unfinished and need your help
 * https://github.com/zhouaini528/huobi-php.git
 * */

use Lin\Exchange\Exchanges;

require __DIR__ .'../../vendor/autoload.php';

include 'key_secret.php';
$key=$keysecret['huobi']['key'];
$secret=$keysecret['huobi']['secret'];
$host=$keysecret['huobi']['host'];
$account_id=$keysecret['huobi']['account_id'];

$exchanges=new Exchanges('huobi',$key,$secret,$account_id,$host);

$action=intval($_GET['action'] ?? 0);//http pattern
if(empty($action)) $action=intval($argv[1]);//cli pattern

switch ($action){
    //******************************Spot
    //***********Spot Market
    case 100:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_price'=>'10',
        ]);
        break;
    }
    case 110:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'account-id'=>$account_id,
            'symbol'=>'btcusdt',
            'type'=>'buy-market',
            'amount'=>10
        ]);
        break;
    }
    
    case 101:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
        ]);
        break;
    }
    case 111:{
        //The original parameters
        $result=$exchanges->trader()->sell([
            'account-id'=>$account_id,
            'symbol'=>'btcusdt',
            'type'=>'sell-market',
            'amount'=>'0.001',
        ]);
        break;
    }
    //***********Spot Limit
    case 150:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'2000',
        ]);
        break;
    }
    case 160:{
        //The original parameters
        $result=$exchanges->trader()->buy([
            'account-id'=>$account_id,
            'symbol'=>'btcusdt',
            'type'=>'buy-limit',
            'amount'=>'0.001',
            'price'=>'2001',
        ]);
        break;
    }
    case 151:{
        $result=$exchanges->trader()->sell([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'9999',
        ]);
        break;
    }
    case 161:{
        //The original parameters
        $result=$exchanges->trader()->sell([
            'account-id'=>$account_id,
            'symbol'=>'btcusdt',
            'type'=>'sell-limit',
            'amount'=>'0.001',
            'price'=>'9998',
        ]);
        break;
    }
    
    case 300:{
        $result=$exchanges->trader()->show([
            '_order_id'=>'29897313869',
        ]);
        break;
    }
    
    case 301:{
        //The original parameters
        $result=$exchanges->trader()->show([
            'order-id'=>'30002957180',
        ]);
        break;
    }
    case 302:{
        //The original parameters
        $result=$exchanges->trader()->cancel([
            'order-id'=>'30003632696',
        ]);
        break;
    }
    
    
    
    //***Complete spot flow
    case 400:{
        $result=$exchanges->trader()->buy([
            '_symbol'=>'btcusdt',
            '_number'=>'0.001',
            '_price'=>'2000',
        ]);
        print_r($result);
        
        $result=$exchanges->trader()->cancel([
            '_order_id'=>$result['data']['id'],
        ]);
        
        break;
    }
    
    
    
    //******************************Future
    //***********Future Market
    //TODO
    
    
    //***Complete future flow
    //TODO
    case 450:{
        break;
    }
    
    default:{
        echo 'nothing';
        exit;
    }
}

print_r($result);