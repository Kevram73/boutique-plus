<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modele;
use App\Livraison;
use App\livraisonCommande;

class LivFictifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modeles = Modele::all();
        foreach($modeles as $modele){
            
            $id=DB::table('livraisons')->max('id');
            $ed=1+$id;
            $livraison = new Livraison();
            $livraison ->numero="LIV".now()->format('Y')."-".$ed;
            $livraison ->date_livraison= now();
            $livraison ->boutique_id=$modele->boutique_id;
            $livraison->save();

            $livraisonCom = new livraisonCommande();
            $livraisonCom ->livraison_id=$livraison->id;
            $livraisonCom->quantite_livre = $modele->quantite;
            $livraisonCom->quantite_restante = 0;
            $livraisonCom->quantite_vendue = 0;
            $livraisonCom->livraison_id = $livraison->id;
            $livraisonCom->modele_id = $modele->id;

            $my_modele = Modele::where('libelle', $modele->libelle)->where('boutique_id', 1)->get()->first();
            $livraisonCom->commande_modele_id = $my_modele->id;
            $livraisonCom->save();

            $my_modele->quantite -= $modele->quantite;
            $my_modele->save();
        }


    }
}
