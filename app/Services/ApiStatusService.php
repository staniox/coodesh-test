<?php
namespace App\Services;

use App\Models\ImportHistory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApiStatusService
{
    public function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return 'OK';
        } catch (\Exception $e) {
            return 'Erro na conexão: ' . $e->getMessage();
        }
    }

    public function getLastCronRun()
    {
        $lastImport = ImportHistory::orderBy('imported_at', 'desc')->first();
        return $lastImport ? $lastImport->imported_at->toDateTimeString() : 'Ainda não executado';
    }

    public function getServerUptime()
    {
        return trim(shell_exec('uptime -p'));
    }

    public function getMemoryUsage()
    {
        $unit=['b','kb','mb','gb'];
        $size = memory_get_usage(true);
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

    public function getApiStatus()
    {
        return [
            'api_status' => 'OK',
            'db_status' => $this->checkDatabaseConnection(),
            'last_cron_run' => $this->getLastCronRun(),
            'uptime' => $this->getServerUptime(),
            'memory_usage' => $this->getMemoryUsage(),
        ];
    }
}
