<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;

class UsersExport extends BaseExport implements FromView, WithProperties, ShouldAutoSize
{

    /**
     * @var Illuminate\Database\Eloquent\Collection
     */
    private $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $users = $this->data;
        return view('export.users', compact('users'));
    }

    /**
     * Set file properties
     *
     * @return array
     */
    public function properties(): array
    {
        return [
            'creator'        => env('APP_NAME'),
            'lastModifiedBy' => env('APP_NAME'),
            'title'          => 'Liste des utilisateurs',
            'description'    => 'Liste des utilisateurs Skaalab Test',
            'subject'        => 'Liste des utilisateurs',
            'keywords'       => 'users,export,spreadsheet,excel',
            'category'       => 'Users',
            'manager'        => 'Noual Ali - noualdev@gmail.com',
            'company'        => '(NLDEV)',
        ];
    }
}
