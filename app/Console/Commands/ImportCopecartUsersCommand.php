<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatusEnum;
use App\Mail\NewSubscribedMail;
use App\Models\Country;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ImportCopecartUsersCommand extends Command
{
    public int $trials = 0;
    public int $sales = 0;
    public int $renew = 0;

    protected $signature = 'import:copecart-users';

    protected $description = 'Command description';

    public function handle(): void
    {
        $this->importUsers(database_path('/copecart/trial-copecart-users.csv'));
        $this->importUsers(database_path('/copecart/sales-copecart-users.csv'));

        dd([
            "trials" => $this->trials,
            "sales" => $this->sales,
            "renew" => $this->renew,
        ]);
    }

    public function importUsers(string $csvPath): void
    {
        $usersCsv = $csvPath;

        if (($handle = fopen($usersCsv, 'r')) !== false) {

            // Skip the first line (header)
            $firstRow = fgetcsv($handle);
            $columns = array_combine(array_values($firstRow), array_keys($firstRow));

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $user = User::where('email', $data[$columns['Customer email']])->first();
                $orderId = $data[$columns['Order ID']];
                $plan = Plan::whereCopecartId($data[$columns['Product ID']])->first();
                $countryId = Country::where('name', 'iLIKE', $data[$columns['Customer country']])->first()?->id;

                if ($plan->name == 'monthly') {
                    $endAt = now()->addMonth();
                } elseif ($plan->name == 'yearly') {
                    $endAt = now()->addYear();
                } else {
                    $endAt = null;
                }

                if ($data[$columns['Status']] == 'Paid') {
                    $this->sales++;
                    $status = SubscriptionStatusEnum::active;
                } elseif ($data[$columns['Status']] == 'Trial') {
                    $this->trials++;
                    $endAt = now()->addDays($plan->trial_days);
                    $status = SubscriptionStatusEnum::trial;
                } else {
                    continue;
                }

                if ($user) {
                    $this->renew++;

                    Subscription::create([
                        'user_id' => $user->id,
                        'plan_id' => $plan->id,
                        'start_at' => Carbon::createFromFormat('d.m.Y', $data[$columns['Date']]),
                        'end_at' => $endAt,
                        'next_bill_at' => $endAt,
                        'status' => $status,
                        'provider' => 'copecart',
                        'payload' => ['order_id' => $orderId],
                    ]);
                } else {
                    $user = User::create([
                        'email' => $data[$columns['Customer email']],
                        'first_name' => $data[$columns['Customer name']] ?? null,
                        'last_name' => $data[$columns['Customer name']] ?? null,
                        'phone_number' => $data[$columns['Customer phone']] ?? null,
                        'country_id' => $countryId,
                        'password' => Hash::make(Str::random()),
                        'created_by_csv' => true,
                    ]);


                    $user->subscriptions()->create([
                        'plan_id' => $plan->id,
                        'start_at' => now(),
                        'end_at' => $endAt,
                        'next_bill_at' => $endAt,
                        'status' => $status,
                        'payload' => ['order_id' => $orderId],
                        'provider' => 'copecart',
                    ]);

                    $reset_url = route('password.reset', ['token' => \Illuminate\Support\Facades\Password::createToken($user), 'email' => $user->email]);
                    Mail::to($user->email)->queue((new NewSubscribedMail($reset_url,$plan))->onQueue('emails'));
                }
                echo "\n id $user->id,email $user->email created";
            }

            fclose($handle);

        } else {
            throw new \Exception('Cannot open world.csv');
        }
    }
}
