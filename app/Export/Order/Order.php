<?php

namespace App\Export\Order;

use Crazymeeks\PHPExcel\Contracts\ExcelInterface;

class Order implements ExcelInterface
{

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /** @inheritDoc */
    public function dataToExport()
    {
        return $this->data;
    }

    /** @inheritDoc */
    public function getHeader()
    {
        return [
            'Reference #',
            'Sold To',
            'Grand Total',
            'Store Name',
            'Product Name',
            'Product Brand',
            'Product Category',
            'SKU',
            'Price',
            'Quantity',
            'Unit Of Measure',
            'Color',
            'Size',
        ];
    }


    /** @inheritDoc */
    public function getType()
    {
        // for excel, just return 'xls' here
        return 'csv';
    }

    /** @inheritDoc */
    public function getFilename()
    {
        return 'Orders-' . date('Y-m-d');
    }

    /** @inheritDoc */
    public function getPath()
    {
        return storage_path();
    }
}