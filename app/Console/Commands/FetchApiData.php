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
    protected $signature = 'fetch:api-data';
    protected $description = 'Fetch all data from WB test API in bulk';

    protected $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';
    protected $apiHost = 'http://109.73.206.144:6969';

    public function handle()
    {
        $this->info("Fetching data...");

        $this->fetchSales();
        $this->fetchOrders();
        $this->fetchStocks();
        $this->fetchIncomes();

        $this->info("Done!");
    }

    private function fetchSales()
    {
        $this->info("Fetching Sales...");
        $this->fetchPaginatedData('/api/sales', Sale::class);
    }

    private function fetchOrders()
    {
        $this->info("Fetching Orders...");
        $this->fetchPaginatedData('/api/orders', Order::class);
    }

    private function fetchStocks()
    {
        $this->info("Fetching Stocks...");
        $this->fetchPaginatedData('/api/stocks', Stock::class, now()->format('Y-m-d'));
    }

    private function fetchIncomes()
    {
        $this->info("Fetching Incomes...");
        $this->fetchPaginatedData('/api/incomes', Income::class);
    }

    private function fetchPaginatedData($endpoint, $modelClass, $dateFrom = null, $dateTo = null)
    {
        $page = 1;
        $dateFrom = $dateFrom ?? now()->subMonth()->format('Y-m-d');
        $dateTo   = $dateTo ?? now()->format('Y-m-d');

        do {
            $response = Http::timeout(60)->get($this->apiHost . $endpoint, [
                'key' => $this->apiKey,
                'dateFrom' => $dateFrom,
                'dateTo'   => $dateTo,
                'page'     => $page,
                'limit'    => 500,
            ]);

            if (!$response->successful()) {
                $this->error("Request failed on page $page");
                break;
            }

            $items = $response->json('data') ?? [];

            if (empty($items)) {
                break;
            }

            $this->bulkSave($modelClass, $items);

            $this->info("Page $page loaded: " . count($items) . " records");
            $page++;
        } while (count($items) === 500);
    }

    private function bulkSave($modelClass, array $items)
    {
        $records = [];

        foreach ($items as $item) {
            switch ($modelClass) {
                case Sale::class:
                    $records[] = [
                        'sale_id'      => $item['g_number'] ?? uniqid('sale_'),
                        'date'         => $item['date'] ?? now()->format('Y-m-d'),
                        'product_name' => $item['supplier_article'] ?? 'Unknown',
                        'sku'          => $item['supplier_article'] ?? 'Unknown',
                        'quantity'     => $item['quantity'] ?? 1,
                        'amount'       => $item['total_price'] ?? 0,
                        'warehouse'    => $item['warehouse_name'] ?? 'Unknown',
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ];
                    break;

                case Order::class:
                    $records[] = [
                        'order_id'      => $item['g_number'] ?? uniqid('order_'),
                        'sku'           => $item['supplier_article'] ?? 'Unknown',
                        'order_date'    => $item['date'] ?? now(),
                        'customer_name' => $item['customer_name'] ?? null,
                        'total_amount'  => $item['total_price'] ?? 0,
                        'status'        => $item['status'] ?? null,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                    break;

                case Stock::class:
                    $records[] = [
                        'stock_id'   => $item['g_number'] ?? uniqid('stock_'),
                        'sku'        => $item['supplier_article'] ?? null,
                        'quantity'   => $item['quantity'] ?? 0,
                        'warehouse'  => $item['warehouse_name'] ?? null,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ];
                    break;

                case Income::class:
                    $records[] = [
                        'income_id' => $item['g_number'] ?? uniqid('income_'),
                        'date'      => $item['date'] ?? now()->format('Y-m-d'),
                        'amount'    => $item['total_price'] ?? 0,
                        'created_at'=> now(),
                        'updated_at'=> now(),
                    ];
                    break;
            }
        }

        if (!empty($records)) {
            switch ($modelClass) {
                case Sale::class:
                    DB::table('sales')->upsert(
                        $records,
                        ['sale_id'],
                        ['date','product_name','sku','quantity','amount','warehouse','updated_at']
                    );
                    break;

                case Order::class:
                    DB::table('orders')->upsert(
                        $records,
                        ['order_id'],
                        ['sku','order_date','customer_name','total_amount','status','updated_at']
                    );
                    break;

                case Stock::class:
                    DB::table('stocks')->upsert(
                        $records,
                        ['stock_id'],
                        ['sku','quantity','warehouse','updated_at']
                    );
                    break;

                case Income::class:
                    DB::table('incomes')->upsert(
                        $records,
                        ['income_id'],
                        ['date','amount','updated_at']
                    );
                    break;
            }
        }
    }
}