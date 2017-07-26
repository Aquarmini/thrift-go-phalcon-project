<?php

namespace App\Tasks\Test;

use Thrift\Protocol\TBinaryProtocol;
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
        $client = new \ThriftService\SystemClient($protocol);

        $transport->open();

        echo $client->test(" World! ");
        echo PHP_EOL;
        echo $client->version();
        echo PHP_EOL;
        echo $client->count(0);

        $transport->close();
    }

    public function goClientAction()
    {
        $thrift = di('thrift');

        $thrift->setHost('127.0.0.1');
        $thrift->setPort('10086');
        $socket = $thrift->socket();

        $transport = new TFramedTransport($socket, 1024, 1024);
        $protocol = new TBinaryProtocol($transport);
        $client = new \ThriftService\SystemClient($protocol);

        $transport->open();

        echo $client->test(" World! ");
        echo PHP_EOL;
        echo $client->version();
        echo PHP_EOL;
        echo $client->count(0);

        $transport->close();
    }

}

