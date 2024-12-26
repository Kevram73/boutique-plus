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
    $validated = $request->validate([
        'boutique' => 'nullable|integer|exists:boutiques,id',
        'date_deb' => 'nullable|date',
        'date_fin' => 'nullable|date|after_or_equal:date_deb',
        'type' => 'required|in:depenses,ventes,livraisons',
    ]);

    $tableName = match ($validated['type']) {
        'depenses' => 'depenses',
        'ventes' => 'ventes',
        'livraisons' => 'livraisons',
    };

    $query = \DB::table($tableName)
        ->join('users', "{$tableName}.user_id", '=', 'users.id')
        ->join('boutiques', "{$tableName}.boutique_id", '=', 'boutiques.id')
        ->when($validated['boutique'] ?? null, fn($q) => $q->where('boutique_id', $validated['boutique']))
        ->when($validated['date_deb'] ?? null, fn($q) => $q->where('date_dep', '>=', $validated['date_deb']))
        ->when($validated['date_fin'] ?? null, fn($q) => $q->where('date_dep', '<=', $validated['date_fin']))
        ->select("{$tableName}.*", 'users.nom as user_nom', 'users.prenom as user_prenom', 'boutiques.nom as boutique_name');

    $data = $query->paginate(10); // Retourner 10 résultats par page

    return response()->json($data);
}


}
