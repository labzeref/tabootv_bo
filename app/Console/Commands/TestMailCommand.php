<?php

namespace App\Console\Commands;

use App\Mail\NewSubscribedMail;
use App\Mail\ResetPasswordMail;
use App\Models\Plan;
use http\Client\Curl\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'test:mail';

    protected $description = 'Command description';

    public function handle(): void
    {
       $user = \App\Models\User::where('email','akash@zereflab.com')->first();
       $plan = Plan::first();

       if ($user){
           $this->info('User found');
       }
       if ($plan){
           $this->info('Plan found');
       }

       $reset_url = route('password.reset',['token'=>\Illuminate\Support\Facades\Password::createToken($user),'email'=>$user->email]);

       $this->info($reset_url);

       Mail::to($user)->send(new NewSubscribedMail($reset_url,$plan));

        $this->info('Mail has been sent successfully');
    }
}
