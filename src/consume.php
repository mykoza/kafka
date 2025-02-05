<?php

namespace Root\Html;

$conf = new \RdKafka\Conf();
// $conf->set('log_level', (string) LOG_DEBUG);
// $conf->set('debug', 'all');
$conf->set('metadata.broker.list', 'kafka:9092');
// Configure the group.id. All consumer with the same group.id will consume
// different partitions.
$conf->set('group.id', 'consumer-of-topic-test-2');

$consumer = new \RdKafka\KafkaConsumer($conf);
$consumer->subscribe(['test-2']);

while (true) {
    $message = $consumer->consume(15 * 1000);

    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo PHP_EOL;
            echo '------------' . PHP_EOL;
            echo 'New message:' . PHP_EOL;
            echo '-Key: ' . $message->key . PHP_EOL;
            echo '-Payload: ' . $message->payload . PHP_EOL;
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages; will wait for more\n";
            break 2;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break 2;
        default:
            throw new \Exception($message->errstr(), $message->err);
            break;
    }

    switch ($message->key) {
        case 'key1':
            $payload = json_decode($message->payload);
            echo '-Decoded payload: ' . PHP_EOL;
            echo '--' . $payload->val1 . PHP_EOL;
            echo '------------' . PHP_EOL;
            break;
        case 'key2':
            $payload = json_decode($message->payload);
            echo '-Decoded payload: ' . PHP_EOL;
            echo '--' . $payload->val2 . PHP_EOL;
            echo '------------' . PHP_EOL;
            break;
        default:
            echo '-Unknown payload type' . PHP_EOL;
            echo '------------' . PHP_EOL;
            break;
    }

    sleep(2);
}

echo 'koniec';
