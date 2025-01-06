<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetMissingTransactionsDataCommand extends Command
{
    protected $signature = 'get:missing-transactions-data';

    protected $description = 'Command description';

    public function handle(): void
    {
        $missingTransactionIds = DB::table('not_found_apple_subscriptions')->get()->pluck('subscription_id')->toArray();
        $subscriptions = DB::table('subscriptions')->whereIn('id', $missingTransactionIds)->get();
        $users = DB::table('users')->whereIn('id', $subscriptions->pluck('user_id'))->get();

        $columns = ['id', 'first_name', 'last_name', 'email'];

        $users = $users->map(function ($item) use ($columns) {
            return collect($item)->only($columns);
        });

        $this->table($columns, $users->toArray());
    }
}
