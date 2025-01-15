<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MergeDuplicateEmailUsersCommand extends Command
{
    protected $signature = 'merge:duplicate-email-users';

    protected $description = 'Command description';

    public function handle()
    {
        DB::beginTransaction();
        try {
            $file = fopen(storage_path('/merge-users.csv'), "w");

            // Step 1: Find duplicate emails (case insensitive)
            $duplicates = DB::table('users')
                ->selectRaw('LOWER(email) as normalized_email, COUNT(*) as count')
                ->whereNull('deleted_at') // Add this condition
                ->groupByRaw('LOWER(email)')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            if ($duplicates->isEmpty()) {
                $this->info('No duplicate emails found.');
                return;
            }

            $progressBar = $this->output->createProgressBar($duplicates->count());

            foreach ($duplicates as $index => $dup) {
                $normalizedEmail = $dup->normalized_email;

                // Step 2: Find users with this email in different cases
                $users = User::whereRaw('LOWER(email) = ?', [$normalizedEmail])
                    ->orderByDesc('id') // Keeping the latest user
                    ->get();

                if ($users->count() < 2) {
                    continue;
                }

                $userToKeep = $users->first(); // Latest user
                $userIdsToDelete = $users->pluck('id')->slice(1)->toArray(); // Older users

                // Step 3: Update foreign key references in other tables
                $this->updateForeignKeys($userToKeep->id, $userIdsToDelete);

                // Step 4: Delete old users
                User::whereIn('id', $userIdsToDelete)->delete();

                $this->info("Merged users for email: $normalizedEmail");

                if ($index === 0) {
                    fputcsv($file, ['email', 'user_id_to_keep', 'user_ids_to_delete']);
                }

                $fileData = [$normalizedEmail, $userToKeep->id, implode('|', $userIdsToDelete)];
                fputcsv($file, $fileData);
                $progressBar->advance();
            }

            DB::commit();
            $this->info('User merging completed successfully.');
            $progressBar->finish();
            fclose($file);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error merging users: ' . $e->getMessage());
        }
    }

    private function updateForeignKeys($keepUserId, $oldUserIds): void
    {
        $tables = [
            [
                'name' => 'reports',
                'columns' => ['user_id']
            ],
            [
                'name' => 'users',
                'columns' => ['user_id']
            ],
            [
                'name' => 'subscriptions',
                'columns' => ['user_id']
            ],
            [
                'name' => 'channels',
                'columns' => ['user_id']
            ],
            [
                'name' => 'series',
                'columns' => ['user_id']
            ],
            [
                'name' => 'videos',
                'columns' => ['user_id']
            ],
            [
                'name' => 'video_likes',
                'columns' => ['user_id']
            ],
            [
                'name' => 'comments',
                'columns' => ['user_id']
            ],
            [
                'name' => 'messages',
                'columns' => ['user_id']
            ],
            [
                'name' => 'comment_likes',
                'columns' => ['user_id']
            ],
            [
                'name' => 'blocked_users',
                'columns' => ['user_id', 'target_id']
            ],
            [
                'name' => 'blocks',
                'columns' => ['user_id']
            ],
            [
                'name' => 'posts',
                'columns' => ['user_id']
            ],
            [
                'name' => 'post_comments',
                'columns' => ['user_id']
            ],
            [
                'name' => 'post_comments_likes',
                'columns' => ['user_id']
            ],
            [
                'name' => 'post_likes',
                'columns' => ['user_id']
            ],
            [
                'name' => 'temp_media',
                'columns' => ['user_id']
            ],
            [
                'name' => 'channel_user',
                'columns' => ['user_id']
            ],
            [
                'name' => 'device_tokens',
                'columns' => ['user_id']
            ],

        ];

        foreach ($tables as $table) {
            foreach ($table['columns'] as $column) {
                if (DB::getSchemaBuilder()->hasColumn($table['name'], $column)) {
                    DB::table($table['name'])->whereIn($column, $oldUserIds)->update([$column => $keepUserId]);
                }
            }
        }
    }
}
