<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barco extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'vida',
        'velocidad',
        'posicionX',
        'posicionY',
        'pantallaId',
    ];

    /**
     * Get the pantalla that owns the barco.
     */
    public function pantalla()
    {
        return $this->belongsTo(Pantalla::class);
    }
}
