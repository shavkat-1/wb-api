<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Income;

class FetchApiData extends Command
{
    protected $signature = 'fetch:api-data';
    protected $description = 'Fetch all data from WB test API';

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

    protected function fetchSales()
    {
        $this->info("Fetching Sales...");
        $this->fetchPaginatedData('/api/sales', Sale::class);
    }

    protected function fetchOrders()
    {
        $this->info("Fetching Orders...");
        $this->fetchPaginatedData('/api/orders', Order::class);
    }

    protected function fetchStocks()
    {
        $this->info("Fetching Stocks...");
        $this->fetchPaginatedData('/api/stocks', Stock::class, date('Y-m-d'));
    }

    protected function fetchIncomes()
    {
        $this->info("Fetching Incomes...");
        $this->fetchPaginatedData('/api/incomes', Income::class);
    }

    protected function fetchPaginatedData($endpoint, $modelClass, $dateFrom = null, $dateTo = null)
    {
        $page = 1;
        $dateFrom = $dateFrom ?? now()->subMonth()->format('Y-m-d');
        $dateTo = $dateTo ?? now()->format('Y-m-d');

        do {
            $response = Http::get($this->apiHost.$endpoint, [
                'key' => $this->apiKey,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => $page,
                'limit' => 500,
            ]);

            $data = $response->json();

            foreach ($data['items'] ?? [] as $item) {
                $modelClass::updateOrCreate(['id' => $item['id']], $item);
            }

            $page++;
        } while ($page <= ($data['pages'] ?? 1));
    }
}