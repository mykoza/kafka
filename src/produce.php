<?php

namespace Root\Html;

$conf = new \RdKafka\Conf();
// $conf->set('log_level', (string) LOG_DEBUG);
// $conf->set('debug', 'all');
$conf->set('metadata.broker.list', 'kafka:9092');

$producer = new \RdKafka\Producer($conf);

$topic = $producer->newTopic("test-3");

$i = 0;

while ($i < 3) {
    $message = "Pass $i";

    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "Message of pass $i");
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, '{"pass": ' . $i . ', "val1": "dupa 1"}', 'key1');
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, '{"pass": ' . $i . ', "val2": "dupa 2"}', 'key2');
    $producer->poll(0);

    echo PHP_EOL;
    echo '------------' . PHP_EOL;
    echo 'Message: "' . $message . '" sent.' . PHP_EOL;
    echo '------------' . PHP_EOL;

    $i++;
    sleep(2);
}

for ($flushRetries = 0; $flushRetries < 10; $flushRetries++) {
    $result = $producer->flush(10000);
    if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
        break;
    }
}

if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
    throw new \RuntimeException('Was unable to flush, messages might be lost!');
}
