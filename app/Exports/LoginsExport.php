<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;

class LoginsExport extends BaseExport implements FromView, WithProperties, ShouldAutoSize
{

    /**
     * @var Illuminate\Database\Eloquent\Collection
     */
    private $data;

    public function __construct(User $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $logins = $this->data?->logins;
        $user = $this->data;
        return view('export.logins', compact('logins', 'user'));
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
            'title'          => 'Liste des authentifications utilisateur',
            'description'    => 'Liste des authentifications utilisateur',
            'subject'        => 'Liste des authentifications utilisateur',
            'keywords'       => 'logins,export,spreadsheet,excel',
            'category'       => 'Authentifications',
            'manager'        => 'Noual Ali - noualdev@gmail.com',
            'company'        => '(NLDEV)',
        ];
    }
}
