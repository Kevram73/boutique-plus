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
    ->join('users', "{$tableName}.user_id", '=', 'users.id') // Joindre avec la table users
    ->join('boutiques', "{$tableName}.boutique_id", '=', 'boutiques.id') // Joindre avec la table boutiques
    ->when(isset($validated['start_date']), fn($query) => $query->where("{$tableName}.created_at", '>=', $validated['start_date'])) // Filtrer par date de début
    ->when(isset($validated['end_date']), fn($query) => $query->where("{$tableName}.created_at", '<=', $validated['end_date'])) // Filtrer par date de fin
    ->when(isset($validated['shop_id']), fn($query) => $query->where("{$tableName}.boutique_id", $validated['shop_id'])) // Filtrer par boutique
    ->orderBy("{$tableName}.created_at", 'desc') // Trier par date
    ->select(
        "{$tableName}.*", // Toutes les colonnes du modèle principal
        'users.nom as user_nom', // Nom de l'utilisateur
        'users.prenom as user_prenom', // Prénom de l'utilisateur
        'boutiques.nom as boutique_name' // Nom de la boutique
    )
    ->paginate(10);

    // Retour des données sous format JSON
    return response()->json($data);
}

}
