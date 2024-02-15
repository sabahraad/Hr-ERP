<?php

namespace App\Exports;

use App\Models\Designation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DesigExport implements FromCollection, WithHeadings
{
       /**
        * @return \Illuminate\Support\Collection
        */
        protected $data;
    
        public function __construct($data)
        {
            $this->data = $data;
        }
    
        public function collection()
        {
            return collect($this->data);
        }
    
        
        public function headings(): array
        {
            return [
                'desig_id',
                'Designation Title',
                'dept_id',
                'Department Title'
            ];
        }
    
}
