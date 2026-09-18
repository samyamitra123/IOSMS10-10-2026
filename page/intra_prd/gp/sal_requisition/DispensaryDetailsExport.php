<?php

namespace App\Exports;

use App\Models\Medicine;
use App\Models\Gp;
use App\Models\Block;
use App\Models\District;
use Session;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class GpMedicineStocklist implements FromView
{
    protected $data;

    // Constructor to receive the data
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
{
    $gpNames = $this->data['Gpnames'];
    $medicineList = $this->data['Medicinelist'];
    $gpMedicineStock = $this->data['GpMedicine'];

    // Use the dedicated export view
    return view('stocks.gpmedicinestocklistexcel', compact('gpNames', 'medicineList', 'gpMedicineStock'));
}
}

