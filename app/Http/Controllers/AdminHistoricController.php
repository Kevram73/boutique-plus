<?php

namespace App\Http\Controllers;

use App\Depense;
use App\Vente;
use App\Livraison;
use Illuminate\Http\Request;
use App\Boutique;

class AdminHistoricController extends Controller
{
    /**
     * Affiche la page des Dépenses.
     */
    public function depenses()
    {
        $shops = Boutique::all();
        return view('admin.historic.depenses', compact('shops'));
    }

    /**
     * Affiche la page des Ventes.
     */
    public function ventes()
    {
        $shops = Boutique::all();
        return view('admin.historic.ventes', compact('shops'));
    }

    /**
     * Affiche la page des Livraisons.
     */
    public function livraisons()
    {
        $shops = Boutique::all();
        return view('admin.historic.livraisons', compact('shops'));
    }

    /**
     * Récupère les données filtrées via AJAX.
     */
    public function fetchData(Request $request)
    {
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $shopId = $request->input('shop_id');

        if (!in_array($type, ['depenses', 'ventes', 'livraisons'])) {
            return response()->json(['error' => 'Type non valide'], 400);
        }

        $model = match ($type) {
            'depenses' => Depense::query(),
            'ventes' => Vente::query(),
            'livraisons' => Livraison::query(),
        };

        $data = $model
            ->when($startDate, fn($query) => $query->where('created_at', '>=', $startDate))
            ->when($endDate, fn($query) => $query->where('created_at', '<=', $endDate))
            ->when($shopId, fn($query) => $query->where('boutique_id', $shopId))
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($data);
    }
}
