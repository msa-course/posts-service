<?php

namespace App\Http\Controllers;

use App\Services\KafkaProducerService;
use Illuminate\Http\Request;

class KafkaController extends Controller
{
    protected $producer;

    public function __construct(KafkaProducerService $producer)
    {
        $this->producer = $producer;
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message', 'Default Kafka message');

        try {
            $this->producer->produce($message);
            return response()->json([
                'status' => 'success',
                'message' => 'Message sent to Kafka'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
