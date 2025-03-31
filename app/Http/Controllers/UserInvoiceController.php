<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInvoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        $invoices = Invoice::where('user_id', $user->id)->latest()->get();

        return response()->json(['invoices' => $invoices]);
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        $invoice = Invoice::where('user_id', $user->id)->where('id', $id)->first();

        if (!$invoice) {
            return response()->json(['message' => 'Facture non trouvée'], 404);
        }

        return response()->json(['invoice' => $invoice]);
    }
}
