<?php
// app/Services/ServerStatusService.php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class ServerStatusService
{
    public function checkStatus(): array
    {
        $status = [
            'database' => '正常',
            'overall' => '正常'
        ];

        try {
            DB::connection()->getPdo();
        } catch (Exception $e) {
            $status['database'] = '異常';
            $status['overall'] = '異常';
            Log::error('Database connection failed: ' . $e->getMessage()); // Use Log::error instead of \Log::error
        }

        return $status;
    }

    public function getOnlineUsers(): int
    {
        return Cache::remember('online_users', now()->addMinutes(5), function () {
            try {
                return DB::table('sessions')
                    ->where('last_activity', '>=', now()->subMinutes(15)->getTimestamp())
                    ->count();
            } catch (Exception $e) {
                Log::error('Failed to count online users: ' . $e->getMessage()); // Use Log::error
                return 0;
            }
        });
    }
}
