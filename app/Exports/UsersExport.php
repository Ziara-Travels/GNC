<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected $filters;
    protected $role;

    public function __construct($filters, $role)
    {
        $this->filters = $filters;
        $this->role = $role;
    }

    public function collection()
    {
        $query = User::with('roles');
        
        // Apply filters based on role permissions
        if ($this->role === 'admin') {
            $query->whereHas('roles', function($q) {
                $q->where('name', 'user');
            });
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Country',
            'Gender',
            'Status',
            'Role',
            'Created At'
        ];
    }
}