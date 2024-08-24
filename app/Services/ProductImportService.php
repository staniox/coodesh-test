<?php

namespace App\Services;

use App\Console\Commands\CreateProducts;
use App\Models\ImportHistory;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductImportService
{
    public function __construct(private ProductService $productService)
    {}

    /**
     * @throws Exception
     */
    public function importProducts(int $chunkSize): void
    {
        $indexUrl = 'https://challenges.coode.sh/food/data/json/index.txt';
        $fileUrls = explode("\n", Http::get($indexUrl)->body());

        $tempDir = storage_path('temp');

        if (!File::isDirectory($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        foreach ($fileUrls as $fileName) {
            $fileName = trim($fileName);
            if (empty($fileName)) {
                continue;
            }

            $fileUrl = "https://challenges.coode.sh/food/data/json/{$fileName}";

            try {
                $response = Http::get($fileUrl);

                if ($response->failed()) {
                    Log::error("Falha ao obter o arquivo: $fileUrl");
                    throw new Exception("Erro ao obter o arquivo: $fileUrl");
                }

                $tempFilePath = $tempDir . '/' . basename($fileUrl);
                Storage::put($tempFilePath, $response->body());

                if (File::extension($tempFilePath) === 'gz') {
                    $gzHandle = gzopen(Storage::path($tempFilePath), 'r');

                    if (!$gzHandle) {
                        Log::error("Erro ao abrir o arquivo GZIP: $fileUrl");
                        throw new Exception("Erro ao abrir o arquivo GZIP: $fileUrl");
                    }

                    $productCount = 0;

                    while (!gzeof($gzHandle) && $productCount < $chunkSize) {
                        $line = gzgets($gzHandle, 20096);

                        if ($line === false) {
                            Log::warning("Falha ao ler a linha do arquivo GZIP: $fileUrl");
                            continue;
                        }

                        $line = trim($line);
                        $line = preg_replace('/[\x00-\x1F\x7F]/u', '', $line); // Remove caracteres de controle

                        $json = json_decode($line, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            Log::error("JSON inválido na linha: \"$line\". Erro: " . json_last_error_msg());
                            throw new Exception("JSON inválido na linha: \"$line\". Erro: " . json_last_error_msg());
                        }

                        if (!$this->productService->verifyProduct($json['code'])) {
                            $this->productService->createProduct($json);

                            ++$productCount;
                        } else {
                            continue;
                        }

                        if ($productCount == $chunkSize) {
                            //para importar apenas 100 registros por arquivo pode ser q nao tenha entendido direito o requisito
                           break;
                        }
                    }

                    gzclose($gzHandle);

                    File::delete($tempFilePath);

                    ImportHistory::create([
                        'filename' => $fileName,
                        'imported_at' => now(),
                        'status' => 'success',
                        'imported_products' => $productCount,
                    ]);
                } else {
                    Log::error("Arquivo não é um arquivo GZIP: $fileUrl");
                    throw new Exception("Arquivo não é um arquivo GZIP: $fileUrl");
                }

            } catch (Exception $e) {
                Log::error("Erro ao processar o arquivo: $fileUrl. " . $e->getMessage());
                ImportHistory::create([
                    'filename' => $fileNam ?? 'nao definido',
                    'imported_at' => now(),
                    'status' => 'error',
                    'imported_products' => $productCount ?? null,
                ]);
                throw $e;
            }
        }
    }
}
