<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoiceCsv implements FromView
{
    public function __construct($invoices,$date,$class,$status){
        $this->invoices = $invoices;
        $this->date     = $date;
        $this->class    = $class;
        $this->status   = $status;
    }

    public function view(): View
    {
        return view('admin.partials.invoice.layout.csv', [
            'invoices' => $this->invoices,
            'date'     => $this->date,
            'class'    => $this->class,
            'status'   => $this->status
        ]);

    }
}
