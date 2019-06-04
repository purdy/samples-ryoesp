<?php

/**
 * Quick utility example to gather events from an SQS queue.
 */

require '../vendor/autoload.php';

$ses_sqs_url = $_ENV['SES_SQS_URL'];
try {
  $client = new Aws\Sqs\SqsClient([
    'version' => 'latest',
    'region' => 'us-east-1',
  ]);
  $result = $client->getQueueAttributes([
    'AttributeNames' => ['ApproximateNumberOfMessages'],
    'QueueUrl' => $ses_sqs_url,
  ]);
  $approx_msg_count = $result->get('Attributes')['ApproximateNumberOfMessages'];
  if ($approx_msg_count > 0) {
    echo "There are " . number_format($approx_msg_count) . " messages to process.\n";
    $chunks = ceil($approx_msg_count / 10);
    $msg_count = 0;
    $stats = [];
    for ($i = 0; $i < $chunks; $i++) {
      $result = $client->receiveMessage([
        'QueueUrl' => $ses_sqs_url,
        'MaxNumberOfMessages' => 10,
      ]);
      $messages = $result->get('Messages');
      if ($messages && is_array($messages) && !empty($messages)) {
        $delete_batch = [];
        foreach ($messages as $sqs_message) {
          $msg_count++;
          $body = json_decode($sqs_message['Body']);
          $message = json_decode($body->Message);
          // print_r($body); print_r($message);
          $type = strtolower($message->eventType);
          // echo "Message type: $type\n";
          $delete_batch[] = [
            'Id' => $sqs_message['MessageId'],
            'ReceiptHandle' => $sqs_message['ReceiptHandle'],
          ];
          switch ($type) {
            case 'send':
              $timestamp = strtotime($message->send->timestamp);
              break;

            case 'complaint':
              $timestamp = strtotime($message->complaint->timestamp);
              break;

            case 'bounce':
              $timestamp = strtotime($message->bounce->timestamp);
              $type = 'bounced';
              // echo "Bounce type: " . $message->bounce->bounceType . "\n";
              break;

            case 'delivery':
              $timestamp = strtotime($message->delivery->timestamp);
              $type = 'delivered';
              break;

            case 'click':
              $timestamp = strtotime($message->click->timestamp);
              $type = 'clicked';
              break;

            case 'open':
              $timestamp = strtotime($message->open->timestamp);
              $type = 'opened';
              break;

            default:
              $type = NULL;
              break;
          }
          // If it's not a permanent bounce (OOTO), skip this.
          if ($type == 'bounced' && $message->bounce->bounceType != 'Permanent') {
            $type = NULL;
          }
          if ($type) {
            if (isset($stats[$type])) {
              $stats[$type]++;
            }
            else {
              $stats[$type] = 1;
            }
          }
        }
        if (!empty($delete_batch)) {
          $result = $client->deleteMessageBatch([
            'Entries' => $delete_batch,
            'QueueUrl' => $ses_sqs_url,
          ]);
        }

      }
    }  
    printf("Processed %d messages: %d sends, %d deliveries, %d bounces, %d complaints, %d opens, %d clicks.\n",
      number_format($msg_count),
      number_format($stats['send']),
      number_format($stats['delivered']),
      number_format($stats['bounced']),
      number_format($stats['complaint']),
      number_format($stats['opened']),
      number_format($stats['clicked'])
    );
  }
  else {
    echo "No messages to process.\n";
  }

}
catch (Exception $e) {
  echo "Error trying to get SQS events: " . $e->__toString() . "\n";
}
