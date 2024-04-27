<?php

namespace App\Http\Controllers;


use App\Boutique;
use App\Caisse;
use App\Categorie;
use App\Historique;
use App\Http\Middleware\Authenticate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function liste()
    {
        $type = DB::table('model_has_roles')->where(['model_id' => Auth::user()->id])->first();
        $Roles = $type && $type->role_id < 5 ? Role::where('id', '<', 5)->get() : Role::all();
        $boutiques =Boutique::all();
        $historique=new Historique();
        $historique->actions = "Liste";
        $historique->cible = "Utilisateurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return view('utilisateur',compact('Roles','boutiques'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $boutiques =Boutique::all();
        return $boutiques;
    }
    public function compte()
    {
        $Roles =Role::all();
        $boutiques =Boutique::all();
        $historique=new Historique();
        $historique->actions = "Affichage";
        $historique->cible = "Compte";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return view('compte',compact('Roles','boutiques'));    }
    /**
   public function changement(Request $request)
    {
        $ancien= Hash::make($request->input('ancien'));
        $nouveau= Hash::make($request->input('nouveau'));

        if (Auth::user()->password==$ancien){
            DB::table('users')
                ->where('id', Auth::user()->id)
                ->update(['password' =>$nouveau ]);
    }
        else{
            Alert::warning('Attention.....','Ancien mot de passe different du celui saisi');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->sexe = $request->input('sexe');
        $user->email = $request->input('email');
        $user->boutique_id = $request->input('boutique');
        $user->contact = $request->input('contact');
        $user->password = Hash::make('password');
        $user->api_token = Str::random(80);
        $user->save();

        DB::table('model_has_roles')->insert([
            ['role_id' =>$request->input('profil'),
                'model_id' => $user->id,
                'model_type'=>"App\User"]
        ]);

        if ($user->hasRole('CAISSIER')){
            $id=DB::table('caisses')->max('id');
            $ed=1+$id;
            $caisse=new Caisse();
            $caisse->libelle="CAISSE N*"." ".$ed;
            $caisse->user_id=$user->id;
            $caisse->save();
        }

        $historique=new Historique();
        $historique->actions = "Creer";
        $historique->cible = "Utilisateurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return $request ->input();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $profil = DB::table('model_has_roles')
            ->join('roles', function ($join) {
                $join->on('roles.id', '=', 'model_has_roles.role_id');
            })
            ->where('model_id','=',$user->id)->first();
        $historique=new Historique();
        $historique->actions = "Detail";
        $historique->cible = "Utilisateurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return ['user'=>$user,'profil'=>$profil];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = User::findOrFail($request->input('id'));
        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->sexe = $request->input('sexe');
        $user->email = $request->input('email');
        $user->boutique_id = $request->input('boutique');
        $user->contact = $request->input('contact');

        $user->update();

        DB::table('model_has_roles')->where(['model_id' => $user->id])->delete();

        DB::table('model_has_roles')->insert([
            ['role_id' =>$request->input('profil'),
                'model_id' => $user->id,
                'model_type'=>"App\User"]
        ]);

        if ($user->hasRole('CAISSIER')){
            $id=DB::table('caisses')->max('id');
            $ed=1+$id;
            $caisse=new Caisse();
            $caisse->libelle="CAISSE N*"." ".$ed;
            $caisse->user_id=$user->id;
            $caisse->save();
        }

        $historique=new Historique();
        $historique->actions = "Modifier";
        $historique->cible = "Utilisateurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();
       return [];
    }
    public function update2(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $ancien=$request->input('ancien');
        $confirm=$request->input('confirm');
        $new=$request->input('new');
        if (Hash::check($ancien, $user->password)) {
            if ($new==$confirm) {
                $user->password=Hash::make($new);
                $user->update();
                Alert::success('Succes','Mot de passe modifié');
            }
            else{
                Alert::warning('Attention.....Confirmation de mot de passe incorrecte','Veuillez vous renseigner votre mot de passe');
            }
        }
        else{
            Alert::warning('Attention.....Ancien mot de passe incorrecte','Veuillez vous renseigner votre mot de passe');
        }
        $historique=new Historique();
        $historique->actions = "Modifier";
        $historique->cible = "Compte";
        $historique->user_id =Auth::user()->id;
        $historique->save();
        return[];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function index(){
        $users = null;
        if (Auth::user()->hasRole('SUPER ADMINISTRATEUR')){
            $users = User::with('boutique')->get();
        } else {
            $users = User::with('boutique')
            ->where ('boutique_id', '=',Auth::user()->boutique->id )
            ->get();;
        }

        return datatables()->of($users)
            ->addColumn('action', function ($role){
                 if($role->flag_etat == false){ 
                return ' <a class="btn btn-info " onclick="showUser('.$role->id.')" ><i class="fa  fa-info"></i></a>
                <a class="btn btn-success" onclick="editUser('.$role->id.')"> <i class="fa fa-pencil"></i></a>
                <a class="btn btn-warning" onclick="resetPwd('.$role->id.')"> <i class="fa fa-refresh"></i></a>
                <a class="btn btn-primary" onclick="changeState('.$role->id.')"><i class="fa  fa-unlock"></i></a> ';
                }
                elseif ($role->flag_etat == true){
                return ' <a class="btn btn-info " onclick="showUser('.$role->id.')" ><i class="fa  fa-info"></i></a>
                <a class="btn btn-success" onclick="editUser('.$role->id.')"> <i class="fa fa-pencil"></i></a>
                <a class="btn btn-warning" onclick="resetPwd('.$role->id.')"> <i class="fa fa-refresh"></i></a>
                <a class="btn btn-danger" onclick="changeState('.$role->id.')"><i class="fa   fa-unlock-alt"></i></a> ';
                }

            })
            ->make(true);
    }
    public function  changeState($id){

        $user = User::findOrFail($id);
        if($user->flag_etat==false){
            $user->flag_etat = true;
            $user->update();
        }
        else{
            $user->flag_etat = false;
            $user->update();
        }
        $historique=new Historique();
        $historique->actions = "Bloqué";
        $historique->cible = "Utilisateurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();

        return [];
    }

    public function changePwd($id){

        $user = User::findOrFail($id);
        $user->password = Hash::make('password');
        $user->update();

        $historique=new Historique();
        $historique->actions = "Renitialisé mot de passe";
        $historique->cible = "Utilisateurs";
        $historique->user_id =Auth::user()->id;
        $historique->save();

        return [];
    }

    public function verrouillage($id)
    {
        $user = User::findOrFail(Auth::user()->id);
        if (Hash::check($id, $user->password)) {
            return view('compte');
        }
    }

}
