<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnonceRequest;
use App\Http\Requests\UpdateAnnonceRequest;
use App\Models\Annonce;
use App\Models\Annoncecandidat;
use App\Models\Annoncerecruteur;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\ElseIf_;

class AnnonceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return Annonce::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnnonceRequest $request)
    {
        $annonce = Annonce::create($request->all());
        if ($request->type_annonce == 'candidat') {
          

        $candidat = new Annoncecandidat();
        $candidat->cv_candidat = $request->cv_candidat;
        $candidat->user_id = $annonce->user_id;
        $candidat->type_annonce_candidat = $request->type_annonce_candidat;
        $candidat->annonce_id = $annonce->id;


        $candidat->save();
          
        } 
        elseif ($request->type_annonce == 'recruteur') {
          
        $recruteur = new Annoncerecruteur();
        $recruteur->salaire = $request->salaire;
        $recruteur->contrat = $request->contrat;
        $recruteur->user_id = $annonce->user_id;
        $recruteur->annonce_id = $annonce->id;
        
        
        $recruteur->save();
          
        }

        return response()->json([
            'message'=>"L'annonce a été Ajouter "
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $annonce = Annonce::leftJoin('annoncecandidats','annonces.id',"annoncecandidats.annonce_id")->where('annonces.id', $id)->first();
        return $annonce;
    }

    public function showByTitre($titre){
    
            $annonce=Annonce::where('secteur_activite','=',$titre)->get();
            return $annonce;

    }
    public function showByType($type){

            if($type == 'candidat')
            {
                $annonce=Annonce::leftJoin('annoncecandidats','annonces.id',"annoncecandidats.annonce_id")->leftjoin('users','users.id',"annoncecandidats.user_id")->where('type_annonce','=',$type)->get();
            }
            elseif ($type == 'recruteur') {
                $annonce=Annonce::leftJoin('annoncerecruteurs','annonces.id',"annoncerecruteurs.annonce_id")->leftjoin('users','users.id',"annoncerecruteurs.user_id")->where('type_annonce','=',$type)->get();
            }
        return $annonce;

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
    public function update(UpdateAnnonceRequest $request, $id)
    {
        $annonce = Annonce::find($id);
        $annonce->update($request->all());

    if ($annonce->type_annonce == 'candidat') {

        $annoncecandidat=Annoncecandidat::where('annonce_id',$id);
        $annoncecandidat->cv_candidat = $request->cv_candidat;
        $annoncecandidat->user_id = $annonce->user_id;
        $annoncecandidat->update($request->only(['cv_candidat', 'type_annonce_candidat']));
    } 
    elseif ($annonce->type_annonce == 'recruteur') {
      
        $annoncerecruteur=Annoncerecruteur::where('annonce_id',$id);
        $annoncerecruteur->salaire = $request->salaire;
        $annoncerecruteur->contrat = $request->contrat;
        $annoncerecruteur->user_id = $annonce->user_id;
        $annoncerecruteur->update($request->only(['contrat','salaire',]));
      
    }
    return response()->json([
        'message'=>"L'annonce a été modifier !"
    ]);
            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $annonce = Annonce::find($id);

        if ($annonce->type_annonce == 'candidat') {
            $cand = Annoncecandidat::where('annonce_id','=',$annonce->id);
            $cand->delete();
          
        } 
        elseif ($annonce->type_annonce == 'recruteur') {
          
            $rec = Annoncerecruteur::where('annonce_id','=',$annonce->id);
            $rec->delete();
                  
        }
        $annonce->delete();

        return response()->json([
            'message'=>"L'annonce a été supprimer !"
        ]);

    }

}
