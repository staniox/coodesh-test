<?php

namespace App\Console\Commands;

use App\Services\ProductImportService;
use Exception;
use Illuminate\Console\Command;

class ImportProducts extends Command
{
    protected $signature = 'products:import {--chunk=100 : Numero de produtos por lote}';
    protected $description = 'Importar produtos de arquivos JSON.GZ';

    public function __construct(private readonly ProductImportService $productImportService)
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $chunkSize = (int) $this->option('chunk');
        $this->info("Iniciando a importação. Por favor, aguarde enquanto o processo é concluído, o que pode levar alguns minutos.");
        $this->productImportService->importProducts($chunkSize);
        $this->info("Arquivos Importados com sucesso! Logs estao na tabela import_histories");
    }
}
