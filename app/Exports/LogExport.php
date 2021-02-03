<?php

namespace App\Exports;

use App\Log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
	public function __construct($data)
	{
		$this->data = $data;
	}

	public function array(): array{
		return $this->data;
	}

	public function headings(): array
	{
		return [
			'Type',
			'Module',
			'Name',
			'Ip Address',
			'URI',
			'Method',
			'Headers',
			'Body',
			'Status Code',
			'Response',
			'Request Time'
		];
	}
    
}
