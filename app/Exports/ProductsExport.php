<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Obtener todos los productos
        return Product::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Definir los encabezados del archivo Excel
        return [
            'ID',
            'Nombre',
            'Descripción',
            'Precio',
            'Stock',
            'Categoría',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }
}

