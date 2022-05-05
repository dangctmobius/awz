<?php

namespace App\Jobs;

use App\Mail\Gmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $order)
    {
        //
        $this->email = $email;
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $message = [
            'image' => $this->order['image'],
            'total' => number_format($this->order['qty'] * $this->order['price']),
            'name' => $this->order['name'],
            'date' => $this->order['date']
        ];
        Mail::to( $this->email)->send(new Gmail($message));
    }
}
