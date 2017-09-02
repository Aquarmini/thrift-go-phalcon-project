<?php

namespace App\Tasks\Test;

use App\Logics\Thrift\Clients\AppClient;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TMultiplexedProtocol;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

class TestTask extends \Phalcon\Cli\Task
{

    public function phpClientAction()
    {
        $thrift = di('thrift');
        $socket = $thrift->client('/server');

        $transport = new TBufferedTransport($socket, 1024, 1024);
        $protocol = new TBinaryProtocol($transport);
        $client = new \MicroService\AppClient($protocol);

        $transport->open();

        echo $client->version();
        echo PHP_EOL;

        $transport->close();
    }

    public function goClientAction()
    {
        $thrift = di('thrift');

        $socket = $thrift->socket('127.0.0.1', '10086');

        // $transport = new TFramedTransport($socket, 1024, 1024);
        $transport = new TBufferedTransport($socket, 1024, 1024);
        $protocol = new TBinaryProtocol($transport);

        $app_protocol = new TMultiplexedProtocol($protocol, "app");
        // $user_protocal = new TMultiplexedProtocol($protocol, "user");

        $client = new \MicroService\AppClient($app_protocol);

        $transport->open();

        echo $client->version();
        echo PHP_EOL;

        $transport->close();
    }

    public function testAction()
    {
        $client = AppClient::getInstance();

        dd($client->version());
    }

}

