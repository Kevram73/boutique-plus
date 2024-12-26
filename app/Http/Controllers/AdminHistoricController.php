<?php

namespace App\Http\Controllers;

use App\Depense;
use App\vente;
use App\Livraison;
use Illuminate\Http\Request;
use App\Boutique;

class AdminHistoricController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

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
        'date_deb' => 'nullable|date',
        'date_fin' => 'nullable|date|after_or_equal:date_deb',
        'boutique' => 'nullable|integer',
        'search' => 'nullable|string|max:255',
    ]);

    // Mapping du type vers le modèle approprié
    $model = match ($validated['type']) {
        'depenses' => Depense::query(),
        'ventes' => vente::query(),
        'livraisons' => Livraison::query(),
        default => throw new \InvalidArgumentException('Type non valide'),
    };
    $tableName = $validated['type'];

    // Définir la colonne de date à utiliser dynamiquement
    $dateColumn = match ($validated['type']) {
        'ventes' => 'date_vente',
        'depenses' => 'date_dep',
        'livraisons' => 'date_livraison',
        default => throw new \InvalidArgumentException('Type non valide'),
    };

    // Application des filtres conditionnels
    if ($validated['type'] === 'ventes') {
        $data = $model
            ->join('users', "{$tableName}.user_id", '=', 'users.id') // Join avec la table users
            ->leftJoin('clients', "{$tableName}.client_id", '=', 'clients.id') // Join optionnel avec clients
            ->join('boutiques', "{$tableName}.boutique_id", '=', 'boutiques.id') // Join avec boutiques
            ->when(isset($validated['boutique']) && $validated['boutique'] != 0, fn($q) => $q->where("{$tableName}.boutique_id", $validated['boutique']))
            ->when($validated['date_deb'] ?? null, fn($q) => $q->where("{$tableName}.date_vente", '>=', $validated['date_deb']))
            ->when($validated['date_fin'] ?? null, fn($q) => $q->where("{$tableName}.date_vente", '<=', $validated['date_fin'])) // Utilisation correcte de date_fin
            ->when($validated['search'] ?? null, function ($q) use ($validated) {
                $q->where(function ($subQuery) use ($validated) {
                    $subQuery->where('users.nom', 'like', "%{$validated['search']}%")
                             ->orWhere('users.prenom', 'like', "%{$validated['search']}%")
                             ->orWhere('boutiques.nom', 'like', "%{$validated['search']}%")
                             ->orWhere('clients.nom', 'like', "%{$validated['search']}%");
                });
            })
            ->select(
                "{$tableName}.*",
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                'boutiques.nom as boutique_name',
                'clients.nom as client_name'
            )
            ->orderBy("{$tableName}.created_at", 'desc')
            ->paginate(25);
    } elseif ($validated['type'] === 'depenses') {
        $data = $model
            ->join('users', "{$tableName}.user_id", '=', 'users.id') // Join avec la table users
            ->join('boutiques', "{$tableName}.boutique_id", '=', 'boutiques.id') // Join avec boutiques
            ->when(isset($validated['boutique']) && $validated['boutique'] != 0, fn($q) => $q->where("{$tableName}.boutique_id", $validated['boutique']))
            ->when($validated['date_deb'] ?? null, fn($q) => $q->where("{$tableName}.date_dep", '>=', $validated['date_deb']))
            ->when($validated['date_fin'] ?? null, fn($q) => $q->where("{$tableName}.date_dep", '<=', $validated['date_fin'])) // Correction ici
            ->when($validated['search'] ?? null, function ($q) use ($validated) {
                $q->where(function ($subQuery) use ($validated) {
                    $subQuery->where('users.nom', 'like', "%{$validated['search']}%")
                             ->orWhere('users.prenom', 'like', "%{$validated['search']}%")
                             ->orWhere('boutiques.nom', 'like', "%{$validated['search']}%")
                             ->orWhere('motif', 'like', "%{$validated['search']}%")
                             ->orWhere('montant', 'like', "%{$validated['search']}%");
                });
            })
            ->select(
                "{$tableName}.*",
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                'boutiques.nom as boutique_name'
            )
            ->orderBy("{$tableName}.created_at", 'desc')
            ->paginate(25);
    } elseif ($validated['type'] === 'livraisons') {
        $data = $model
            ->join('boutiques', "{$tableName}.boutique_id", '=', 'boutiques.id') // Join avec boutiques
            ->when(isset($validated['boutique']) && $validated['boutique'] != 0, fn($q) => $q->where("{$tableName}.boutique_id", $validated['boutique']))
            ->when($validated['date_deb'] ?? null, fn($q) => $q->where("{$tableName}.date_livraison", '>=', $validated['date_deb']))
            ->when($validated['date_fin'] ?? null, fn($q) => $q->where("{$tableName}.date_livraison", '<=', $validated['date_fin'])) // Correction ici
            ->when($validated['search'] ?? null, function ($q) use ($validated) {
                $q->where(function ($subQuery) use ($validated) {
                    $subQuery->where('boutiques.nom', 'like', "%{$validated['search']}%")
                             ->orWhere('status', 'like', "%{$validated['search']}%");
                });
            })
            ->select(
                "{$tableName}.*",
                'boutiques.nom as boutique_name'
            )
            ->orderBy("{$tableName}.created_at", 'desc')
            ->paginate(25);
    }
    

    // Retour des données sous format JSON
    return response()->json($data);
}


}
