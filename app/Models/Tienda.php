<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/* @mixin Builder */
class Tienda extends Model
{
    protected $fillable = ['nombre'];
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'tiendas_productos');
    }
    public function toArray()
    {
        return ['id' => $this->id, 'nombre' => $this->nombre, 'productos' => $this->productos()->sum('cantidad')];
    }
    static public function findOrCreateByName($nombre): Tienda
    {
        $p = Tienda::where('nombre', $nombre)->take(1)->get()[0] ?? null;
        if ( $p instanceof Tienda)
            return $p;
        $p = new Tienda();
        $p->nombre = $nombre;
        $p->save();
        return $p;
    }
}
