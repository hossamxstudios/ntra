<?php

namespace Database\Seeders;

use App\Models\Mobile;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MobileSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = public_path('Egypt_Mobiles_2000plus_EGP_Estimated.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error('Excel file not found: ' . $filePath);
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Skip header row
        array_shift($rows);

        // Excel columns: 0=Brand, 1=Model, 2=Estimated Price (EGP)
        $count = 0;
        foreach ($rows as $row) {
            $brand = trim($row[0] ?? '');
            $model = trim($row[1] ?? '');
            $price = floatval($row[2] ?? 0);

            if (empty($brand) || empty($model)) {
                continue;
            }

            Mobile::updateOrCreate(
                ['brand' => $brand, 'model' => $model],
                ['estimated_price' => $price]
            );
            $count++;
        }

        $this->command->info("Seeded {$count} mobiles from Excel file.");
    }
}
