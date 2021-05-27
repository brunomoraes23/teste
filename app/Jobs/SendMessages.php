<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Http;
use App\Models\Receiver;

class SendMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $receivers = Receiver::where('status', 'Ativo')->get();

        $feed = Http::get('https://g1.globo.com/rss/g1/');
        $xml = simplexml_load_string($feed);
        
        $titles = '';
        foreach($xml->channel->item as $item){
            $titles .= strval($item->title) ."\n\n";
        }

        $messages = [];
        foreach($receivers as $receiver){
            $message = 
                $receiver->name
                ."\n\n"
                .$titles;
            $obj = [];
            $obj['message'] = $message;
            $obj['phone'] = $receiver->phone;
            $obj["test_mode"] = true;
            array_push($messages, $obj);
        }

        $response = Http::withHeaders([
            'Authorization' => env('APP_AUTHORIZATION', null)
        ])->post('https://zapito.com.br/api/messages', [
            'test_mode' => true,
            'data' => $messages
        ]);
    }
}
