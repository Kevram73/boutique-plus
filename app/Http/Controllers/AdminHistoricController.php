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
    // Validation des paramètres d'entrée
    $validated = $request->validate([
        'type' => 'required|in:depenses,ventes,livraisons',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'shop_id' => 'nullable|integer',
    ]);

    // Mapping du type vers le modèle approprié
    $model = match ($validated['type']) {
        'depenses' => Depense::query(),
        'ventes' => Vente::query(),
        'livraisons' => Livraison::query(),
        default => throw new \InvalidArgumentException('Type non valide'),
    };
    $tableName = $validated['type'];

    // Application des filtres conditionnels
    $data = $model
    ->join('users', "{$tableName}.user_id", '=', 'users.id')
    ->join('boutiques', "{$tableName}.boutique_id", '=', 'boutiques.id')
    ->when($validated['boutique'] ?? null, fn($q) => $q->where('boutique_id', $validated['boutique']))
    ->when($validated['date_deb'] ?? null, fn($q) => $q->where('date_dep', '>=', $validated['date_deb']))
    ->when($validated['date_fin'] ?? null, fn($q) => $q->where('date_dep', '<=', $validated['date_fin']))
    ->when($validated['search'] ?? null, function ($q) use ($validated) {
        $q->where(function ($subQuery) use ($validated) {
            $subQuery->where('users.nom', 'like', "%{$validated['search']}%")
                     ->orWhere('users.prenom', 'like', "%{$validated['search']}%")
                     ->orWhere('motif', 'like', "%{$validated['search']}%");
        });
    })
    ->select("{$tableName}.*", 'users.nom as user_nom', 'users.prenom as user_prenom', 'boutiques.nom as boutique_name')
    ->orderBy("{$tableName}.created_at", 'desc')
    ->paginate(25);

    // Retour des données sous format JSON
    return response()->json($data);
}

}
