<?php
namespace App\Services;

use App\Models\ImportHistory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApiStatusService
{
    private $lastImport;
    public function __construct()
    {
        $this->lastImport = ImportHistory::orderBy('imported_at', 'desc')->first();
    }

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

        return $this->lastImport ? $this->lastImport->imported_at->toDateTimeString() : 'Ainda nao executado';
    }

    public function getServerUptime()
    {
        return trim(shell_exec('uptime -p'));
    }

    public function getMemoryUsage()
    {
        $memory = $this->lastImport ? $this->lastImport->memory : 0;
        return $this->convertB($memory);
    }
    public function getMemoryPeakUsage()
    {
        $memory = $this->lastImport ? $this->lastImport->peak_memory : 0;
        return $this->convertB($memory);
    }
    public function convertB(int $value)
    {
        if ($value == 0) {
            return 'Ainda nao executado';
        }
        $unit=['b','kb','mb','gb'];
        return @round($value/pow(1024,($i=floor(log($value,1024)))),2).' '.$unit[$i];
    }


    public function getApiStatus()
    {
        return [
            'api_status' => 'OK',
            'db_status' => $this->checkDatabaseConnection(),
            'last_cron_run' => $this->getLastCronRun(),
            'uptime' => $this->getServerUptime(),
            'memory_usage' => $this->getMemoryUsage(),
            'memory_peak_usage' => $this->getMemoryPeakUsage(),
        ];
    }
}
