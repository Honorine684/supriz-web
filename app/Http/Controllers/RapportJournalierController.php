<?php

namespace App\Http\Controllers;

use App\Models\Bilan;
use App\Models\BilanEntreprise;
use App\Models\BilanMessageSupriz;
use Illuminate\Http\Request;

class RapportJournalierController extends Controller
{
    public function rapportJournalier()
{
    // Eager load the related entreprises and messagesSupriz
    $bilans = Bilan::with(['entreprises', 'messagesSupriz'])->orderBy('created_at', 'desc')->get();

    $active_link = "r";
    return view('rapport-journalier', compact("active_link", 'bilans'));
}

}
