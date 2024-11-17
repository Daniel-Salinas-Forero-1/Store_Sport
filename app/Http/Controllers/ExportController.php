<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Exportar los productos a un archivo Excel.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportProducts()
    {
        return Excel::download(new ProductsExport, 'productos.xlsx');
    }
}

