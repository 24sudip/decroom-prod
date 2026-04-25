<?php
namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PurchaseItemsImport implements ToCollection {
    public $items = [];

    public function collection(Collection $rows) {
        $rows->shift();

        foreach ($rows as $row) {
            $this->items[] = [
                'product_id'        => $row[0],
                'quantity'          => $row[1],
                'price'             => $row[2],
                'tax_rate'          => $row[3] ?? 0,
                'manufacturer_date' => $row[4] ?? null,
                'expire_date'       => $row[5] ?? null,
            ];
        }

    }

}
