<?php

namespace App\Exports;

use App\Functions\HollandDAL;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TestsHollandExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    use Exportable;

    private $user;
    private $filtro;
    private $page;
    private $rowsPerPage;
    private $sortBy;
    private $descending;


    public function __construct($user, $filtro, $page, $rowsPerPage, $sortBy, $descending)
    {
        $this->user = $user;
        $this->filtro = $filtro;
        $this->page = $page;
        $this->rowsPerPage  = $rowsPerPage;
        $this->sortBy = $sortBy;
        $this->descending = $descending;
    }

    public function collection()
    {
        Log::info('user: ' . json_encode($this->user));
        Log::info('filtro: ' . json_encode($this->filtro));
        Log::info('page: ' . json_encode($this->page));
        Log::info('rowsPerPage: ' . json_encode($this->rowsPerPage));
        Log::info('sortBy: ' . json_encode($this->sortBy));
        Log::info('descending: ' . json_encode($this->descending));
        $dal = new HollandDAL($this->user, $this->filtro, $this->page, $this->rowsPerPage, $this->sortBy, $this->descending);
        return $dal->getAllTests();
    }

    public function title(): string
    {
        return substr('Holland_respuestas', 0, 31);
    }

    public function headings(): array
    {
        return collect($this->collection()->first())->keys()->toArray();
    }
}
