<?php

namespace App\Services;

class KafkaProducerService
{
    protected $producer;
    protected $topic;

    public function __construct()
    {
        $conf = new \RdKafka\Conf();

        // Set configuration properties (optional)
        $conf->set('log_level', LOG_DEBUG);
        $conf->set('debug', 'all');

        // Create producer instance
        $this->producer = new \RdKafka\Producer($conf);

        // Add brokers (comma separated for multiple brokers)
        $this->producer->addBrokers(env('KAFKA_BROKERS', 'localhost:9092'));
    }

    public function produce(string $message, string $topicName = null)
    {
        $topicName = $topicName ?: env('KAFKA_TOPIC', 'laravel_kafka_topic');

        // Get topic instance
        $topic = $this->producer->newTopic($topicName);

        // Produce message
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);

        // Poll for events (trigger delivery reports)
        $this->producer->poll(0);

        // Wait for messages to be delivered (optional)
        $result = $this->producer->flush(10000); // 10 second timeout

        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
            throw new \RuntimeException('Was unable to flush, messages might be lost');
        }

        return true;
    }
}
