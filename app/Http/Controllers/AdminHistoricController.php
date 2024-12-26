<?php

namespace App\Http\Controllers;

use App\Depense;
use App\vente;
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
    $dateColumn = $validated['type'] === 'ventes' ? 'date_vente' : 'date_dep';

    // Application des filtres conditionnels
    $data = $model
    ->join('users', "{$tableName}.user_id", '=', 'users.id') // Association avec la table users
    ->leftJoin('clients', "{$tableName}.client_id", '=', 'clients.id') // Client optionnel
    ->join('boutiques', "{$tableName}.boutique_id", '=', 'boutiques.id') // Association avec boutiques
    ->when($validated['shop_id'] ?? null, fn($q) => $q->where("{$tableName}.boutique_id", $validated['shop_id'])) // Filtre par boutique
    ->when($validated['start_date'] ?? null, fn($q) => $q->where("{$tableName}.{$dateColumn}", '>=', $validated['start_date'])) // Filtre par date de début
    ->when($validated['end_date'] ?? null, fn($q) => $q->where("{$tableName}.{$dateColumn}", '<=', $validated['end_date'])) // Filtre par date de fin
    ->when($validated['search'] ?? null, function ($q) use ($validated, $tableName) {
        $q->where(function ($subQuery) use ($validated, $tableName) {
            $subQuery->where('users.nom', 'like', "%{$validated['search']}%")
                     ->orWhere('users.prenom', 'like', "%{$validated['search']}%")
                     ->orWhere('boutiques.nom', 'like', "%{$validated['search']}%")
                     ->orWhere('clients.nom', 'like', "%{$validated['search']}%");

            // Ajouter condition pour montant et motif uniquement si non ventes
            if ($tableName !== 'ventes') {
                $subQuery->orWhere("{$tableName}.montant", 'like', "%{$validated['search']}%")
                         ->orWhere("{$tableName}.motif", 'like', "%{$validated['search']}%");
            }
        });
    })
    ->select(
        "{$tableName}.*", // Toutes les colonnes de la table principale
        'users.nom as user_nom',
        'users.prenom as user_prenom',
        'boutiques.nom as boutique_name',
        'clients.nom as client_name'
    )
    ->orderBy("{$tableName}.created_at", 'desc') // Trier par la date
    ->paginate(25); // Pagination avec 25 résultats par page

    if ($validated['type'] === 'ventes') {
        $data = $data->map(function ($item) {
            // Conversion et formatage des montants
            $item->totaux = number_format((float)$item->totaux, 2, ',', '.');
            $item->montant_reduction = number_format((float)$item->montant_reduction, 2, ',', '.');
            return $item;
        });
    } elseif ($validated['type'] === 'depenses') {
        $data = $data->map(function ($item) {
            // Conversion et formatage des montants
            $item->montant = number_format((float)$item->montant, 2, ',', '.');
            return $item;
        });
    }



    // Retour des données sous format JSON
    return response()->json($data);
}


}
