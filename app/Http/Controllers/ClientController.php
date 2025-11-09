<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    //
    public function client()
    {

        $active_link = "c"; 
        $users_ = User::All();
        return view('client',compact("active_link","users_"));
    }

    public function searchClients(Request $request)
    {
        $query = $request->input('query');
        $users = User::where('nom', 'like', "%$query%")
                     ->orWhere('prenom', 'like', "%$query%")
                     ->get();
    
        return view('partials.client-list', compact('users'));
    }
    

}