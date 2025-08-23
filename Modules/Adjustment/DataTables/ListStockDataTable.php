<?php

namespace Modules\Adjustment\DataTables;

use Illuminate\Support\Facades\DB;
use Modules\Adjustment\Entities\AdjustedProduct;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ListStockDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn();
        // ->addColumn('action', function ($data) {
        //     return view('adjustment::partials.actions', compact('data'));
        // });
    }

    public function query(AdjustedProduct $model)
    {
        return $model->newQuery()
            ->select(
                'product_id',
                DB::raw("SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END) as stock")
            )
            ->with('product')
            ->groupBy('product_id');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('stock-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-3'l><'col-md-5 mb-2'B><'col-md-4'f>> .
                                        'tr' .
                                        <'row'<'col-md-5'i><'col-md-7 mt-2'p>>")
            ->orderBy(2)
            ->buttons(
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel-fill"></i> Excel'),
                Button::make('print')
                    ->text('<i class="bi bi-printer-fill"></i> Print'),
                Button::make('reset')
                    ->text('<i class="bi bi-x-circle"></i> Reset'),
                Button::make('reload')
                    ->text('<i class="bi bi-arrow-repeat"></i> Reload')
            );
    }

    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex') // ✅ must use DT_RowIndex
                ->title('No')
                ->searchable(false)
                ->orderable(false)
                ->className('text-center align-middle'),

            Column::make('product.product_name')
                ->title('Product')
                ->className('text-center align-middle'),

            Column::make('product.product_quantity')
                ->title('Stock')
                ->className('text-center align-middle'),
        ];
    }

    protected function filename(): string
    {
        return 'Stock_' . date('YmdHis');
    }
}
