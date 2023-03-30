<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TiendaController extends Controller
{
    public function callAction($method, $parameters)
    {
        $transactionNeeded = in_array(strtolower($method), ['delete', 'post', 'patch', 'put']);
        if ($transactionNeeded) DB::beginTransaction();
        try {
            $result = parent::callAction($method, $parameters);
        } catch (\Exception $e) {
            if ($transactionNeeded) DB::rollBack();
            return [ 'status' => 'ko', 'error' => $e->getMessage() ];
        }
        if ($transactionNeeded) DB::commit();
        return $result;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return cache()->remember('tiendas', 5, function () {
            return Tienda::all();
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $nombre = $request->get('nombre');
        if (Tienda::where('nombre', $nombre)->count()>0) {
            return ['status' => 'ko', 'error' => 'ya existe una tienda con este nombre'];
        }
        $t = new Tienda();
        $t->setAttribute('nombre', $nombre);
        $t->save();
        $productos = $request->get('productos');
        if ($productos !== null) {
            if (is_string($productos)) $productos = json_decode($productos, true);
            foreach($productos as $data) {
                $p = Producto::findOrCreateByName($data['nombre']);
                $t->productos()->attach($p->id, ['cantidad' => $data['cantidad'] ?? 0]);
            }
        }

        $this->clearCache(null);
        return $t;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return cache()->remember('tienda:'.$id, 5, function () use ($id) {
            /* @var $t Tienda */
            $t = Tienda::where('id', $id)->take(1)->get()[0];
            $arr = $t->toArray();
            $arr['productos'] = $t->productos()->get(['nombre', 'cantidad']);
            return $arr;
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $t = Tienda::where('id', $id)->get()[0];
        if (!$t) return ['status' => 'ko', 'error' => 'not found'];
        $modified = false;
        $nombreNuevo = $request->get('nombre', null);
        if ($nombreNuevo) {
            $modified = $modified || $t->update(['nombre' => $nombreNuevo]);
        }
        $productosNuevos = $request->get('productos');
        if ($productosNuevos !== null) {
            if (is_string($productosNuevos)) $productosNuevos = json_decode($productosNuevos, true);
            $t->productos()->detach();
            foreach($productosNuevos as $data) {
                $p = Producto::findOrCreateByName($data['nombre']);
                $t->productos()->attach($p->id, ['cantidad' => $data['cantidad'] ?? 0]);
            }
            $modified = true;
        }
        if ($modified) $this->clearCache($id);
        return $t;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $t = Tienda::where('id', $id)->get()[0];
        $t->productos()->detach();
        $rowsAffected = $t->delete();
        if ($rowsAffected) $this->clearCache($id);
        return ['rowsAffected' => $rowsAffected];
    }
    protected function clearCache($id=null):void {
        cache()->delete('tiendas');
        if ($id!=null) cache()->delete('tienda:'.$id);
    }
}
