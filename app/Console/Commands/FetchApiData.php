<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Income;

class FetchApiData extends Command
{
    protected $signature   = 'fetch:api-data {--dateFrom=} {--dateTo=}';
    protected $description = 'Fetch all data from WB test API in bulk';

    protected string $apiKey;
    protected string $apiHost;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey  = config('app.api_key');
        $this->apiHost = 'http://' . config('app.api_host');
    }

    public function handle(): void
    {
        $dateFrom = $this->option('dateFrom') ?? now()->subMonth()->format('Y-m-d');
        $dateTo   = $this->option('dateTo')   ?? now()->format('Y-m-d');

        $this->info("Fetching data from {$dateFrom} to {$dateTo}...");

        $this->fetchSales($dateFrom, $dateTo);
        $this->fetchOrders($dateFrom, $dateTo);
        $this->fetchStocks();
        $this->fetchIncomes($dateFrom, $dateTo);

        $this->info("Done!");
    }

    private function fetchSales(string $dateFrom, string $dateTo): void
    {
        $this->info("Fetching Sales...");
        $this->fetchPaginatedData('/api/sales', Sale::class, $dateFrom, $dateTo);
    }

    private function fetchOrders(string $dateFrom, string $dateTo): void
    {
        $this->info("Fetching Orders...");
        $this->fetchPaginatedData('/api/orders', Order::class, $dateFrom, $dateTo);
    }

    private function fetchStocks(): void
    {
        $this->info("Fetching Stocks...");
        $this->fetchPaginatedData('/api/stocks', Stock::class, now()->format('Y-m-d'));
    }

    private function fetchIncomes(string $dateFrom, string $dateTo): void
    {
        $this->info("Fetching Incomes...");
        $this->fetchPaginatedData('/api/incomes', Income::class, $dateFrom, $dateTo);
    }

    private function fetchPaginatedData(string $endpoint, string $modelClass, string $dateFrom, string $dateTo = null): void
    {
        $page     = 1;
        $total    = 0;
        $lastPage = 1;

        do {
            $params = [
                'key'      => $this->apiKey,
                'dateFrom' => $dateFrom,
                'page'     => $page,
                'limit'    => 500,
            ];

            if ($dateTo) {
                $params['dateTo'] = $dateTo;
            }

            $response = Http::timeout(60)->get($this->apiHost . $endpoint, $params);

            if (!$response->successful()) {
                $this->error("Request failed on page {$page}: " . $response->status());
                break;
            }

            $items = $response->json('data', []);

            if (empty($items)) {
                break;
            }

            $this->bulkSave($modelClass, $items);

            $lastPage  = $response->json('meta.last_page', 1);
            $total    += count($items);

            $this->info("Page {$page}/{$lastPage}: " . count($items) . " records (total: {$total})");

            $page++;

        } while ($page <= $lastPage);
    }

    private function bulkSave(string $modelClass, array $items): void
    {
        $records = array_map(fn($item) => $this->mapItem($modelClass, $item), $items);
        $records = array_filter($records);

        if (empty($records)) {
            return;
        }

        match ($modelClass) {
            Sale::class => DB::table('sales')->upsert(
                $records,
                ['sale_id'],
                ['g_number','date','last_change_date','supplier_article','tech_size','barcode',
                 'total_price','discount_percent','is_supply','is_realization','promo_code_discount',
                 'warehouse_name','country_name','oblast_okrug_name','region_name','income_id',
                 'odid','spp','for_pay','finished_price','price_with_disc','nm_id',
                 'subject','category','brand','is_storno','updated_at']
            ),
            Order::class => DB::table('orders')->insert($records),
            Stock::class => DB::table('stocks')->insert($records),
            Income::class => DB::table('incomes')->insert($records),
        };
    }

    private function mapItem(string $modelClass, array $item): array
    {
        $now = now();

        return match ($modelClass) {
            Sale::class => [
                'sale_id'             => $item['sale_id'] ?? null,
                'g_number'            => $item['g_number'] ?? null,
                'date'                => $item['date'] ?? null,
                'last_change_date'    => $item['last_change_date'] ?? null,
                'supplier_article'    => $item['supplier_article'] ?? null,
                'tech_size'           => $item['tech_size'] ?? null,
                'barcode'             => $item['barcode'] ?? null,
                'total_price'         => $item['total_price'] ?? null,
                'discount_percent'    => $item['discount_percent'] ?? null,
                'is_supply'           => $item['is_supply'] ?? false,
                'is_realization'      => $item['is_realization'] ?? false,
                'promo_code_discount' => $item['promo_code_discount'] ?? null,
                'warehouse_name'      => $item['warehouse_name'] ?? null,
                'country_name'        => $item['country_name'] ?? null,
                'oblast_okrug_name'   => $item['oblast_okrug_name'] ?? null,
                'region_name'         => $item['region_name'] ?? null,
                'income_id'           => $item['income_id'] ?? null,
                'odid'                => $item['odid'] ?? null,
                'spp'                 => $item['spp'] ?? null,
                'for_pay'             => $item['for_pay'] ?? null,
                'finished_price'      => $item['finished_price'] ?? null,
                'price_with_disc'     => $item['price_with_disc'] ?? null,
                'nm_id'               => $item['nm_id'] ?? null,
                'subject'             => $item['subject'] ?? null,
                'category'            => $item['category'] ?? null,
                'brand'               => $item['brand'] ?? null,
                'is_storno'           => $item['is_storno'] ?? null,
                'created_at'          => $now,
                'updated_at'          => $now,
            ],
            Order::class => [
                'g_number'           => $item['g_number'] ?? null,
                'date'               => $item['date'] ?? null,
                'last_change_date'   => $item['last_change_date'] ?? null,
                'supplier_article'   => $item['supplier_article'] ?? null,
                'tech_size'          => $item['tech_size'] ?? null,
                'barcode'            => $item['barcode'] ?? null,
                'total_price'        => $item['total_price'] ?? null,
                'discount_percent'   => $item['discount_percent'] ?? null,
                'warehouse_name'     => $item['warehouse_name'] ?? null,
                'oblast'             => $item['oblast'] ?? null,
                'income_id'          => $item['income_id'] ?? null,
                'odid'               => $item['odid'] ?? null,
                'nm_id'              => $item['nm_id'] ?? null,
                'subject'            => $item['subject'] ?? null,
                'category'           => $item['category'] ?? null,
                'brand'              => $item['brand'] ?? null,
                'is_cancel'          => $item['is_cancel'] ?? false,
                'cancel_dt'          => $item['cancel_dt'] ?? null,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            Stock::class => [
                'date'               => $item['date'] ?? null,
                'last_change_date'   => $item['last_change_date'] ?? null,
                'supplier_article'   => $item['supplier_article'] ?? null,
                'tech_size'          => $item['tech_size'] ?? null,
                'barcode'            => $item['barcode'] ?? null,
                'quantity'           => $item['quantity'] ?? 0,
                'is_supply'          => $item['is_supply'] ?? false,
                'is_realization'     => $item['is_realization'] ?? false,
                'quantity_full'      => $item['quantity_full'] ?? 0,
                'warehouse_name'     => $item['warehouse_name'] ?? null,
                'in_way_to_client'   => $item['in_way_to_client'] ?? 0,
                'in_way_from_client' => $item['in_way_from_client'] ?? 0,
                'nm_id'              => $item['nm_id'] ?? null,
                'subject'            => $item['subject'] ?? null,
                'category'           => $item['category'] ?? null,
                'brand'              => $item['brand'] ?? null,
                'sc_code'            => $item['sc_code'] ?? null,
                'price'              => $item['price'] ?? null,
                'discount'           => $item['discount'] ?? null,
                'created_at'         => $now,
                'updated_at'         => $now,
            ],
            Income::class => [
                'income_id'         => $item['income_id'] ?? null,
                'number'            => $item['number'] ?? null,
                'date'              => $item['date'] ?? null,
                'last_change_date'  => $item['last_change_date'] ?? null,
                'supplier_article'  => $item['supplier_article'] ?? null,
                'tech_size'         => $item['tech_size'] ?? null,
                'barcode'           => $item['barcode'] ?? null,
                'quantity'          => $item['quantity'] ?? 0,
                'total_price'       => $item['total_price'] ?? null,
                'date_close'        => $item['date_close'] ?? null,
                'warehouse_name'    => $item['warehouse_name'] ?? null,
                'nm_id'             => $item['nm_id'] ?? null,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            default => [],
        };
    }
}