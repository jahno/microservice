<?php
$conf = new RdKafka\Conf();
$producer = new RdKafka\Producer($conf);

$producer->setLogLevel(LOG_DEBUG);
$producer->addBrokers("localhost:9092");

$topic = $producer->newTopic("nouveaux-articles");

$message = json_encode(array(
    'nom' => 'nom du produit',
    'description' => 'description du produit',
    'quantite' => 10
));

$topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);

$producer->flush(1000);