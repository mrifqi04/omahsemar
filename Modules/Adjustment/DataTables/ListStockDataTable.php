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
            ->addIndexColumn()
            ->addColumn('inventory_value', function ($row) {
                $value = ($row->product->product_cost ?? 0) * ($row->product->product_quantity ?? 0);
                return format_currency($value);
            })
            ->addColumn('formated_product_cost', function ($row) {
                $value = ($row->product->product_cost ?? 0);
                return format_currency($value);
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d F, Y') : '-';
            });
    }

    public function query(AdjustedProduct $model)
    {
        return $model->newQuery()
            ->select(
                'adjusted_products.*',
            )
            ->with('product.category');
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
            ->orderBy(0)
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

            Column::make('item_location')
                ->title('Item Location')
                ->className('text-center align-middle'),

            Column::make('product.product_name')
                ->title('Item')
                ->className('text-center align-middle'),

            Column::make('product.category.category_name')
                ->title('Item Category')
                ->className('text-center align-middle'),

            Column::make('product.product_code')
                ->title('Item Number')
                ->className('text-center align-middle'),

            Column::make('created_at')
                ->title('Stock Date')
                ->className('text-center align-middle'),

            Column::make('product.product_quantity')
                ->title('Stock')
                ->className('text-center align-middle'),

            Column::make('product.product_stock_alert')
                ->title('Min Stock')
                ->className('text-center align-middle'),

            Column::computed('inventory_value') // ✅ computed column
                ->title('Inventory Value')
                ->className('text-center align-middle'),

            Column::make('formated_product_cost')
                ->title('Unit Cost')
                ->className('text-center align-middle'),
        ];
    }

    protected function filename(): string
    {
        return 'Stock_' . date('YmdHis');
    }
}
