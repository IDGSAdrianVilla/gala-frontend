<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblDirecciones extends Model
{
    use HasFactory;
    protected $primaryKey = 'PkTblDireccion';
    protected $table = 'tbldirecciones';
    protected $fillable = 
    [
        'PkTblDireccion',
        'FkCatPoblacion',
        'FkTblCliente',
        'FkTblEmpleado',
        'Coordenadas',
        'Referencias',
        'Caracteristicas',
        'Calle'
    ];
}
