<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* @mixin Builder */
class Producto extends Model
{
    protected $fillable = ['nombre'];
    public function tiendas()
    {
        return $this->belongsToMany(Tienda::class, 'tiendas_productos');
    }
    static public function findOrCreateByName($nombre): Producto
    {
        $p = Producto::where('nombre', $nombre)->take(1)->get()[0] ?? null;
        if ( $p instanceof Producto )
            return $p;
        $p = new Producto();
        $p->nombre = $nombre;
        $p->save();
        return $p;
    }
}
