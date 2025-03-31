<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentInvoiceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'total_amount' => 'required|numeric',
            'status' => 'required|string',
            'paid_at' => 'required|date',
        ]);

        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['message' => 'Utilisateur non authentifié'], 401);
            }

            $invoiceData = json_decode($request->input('invoice_data'), true);

            $invoice = Invoice::create([
                'user_id' => $user->id,
                'total_amount' => $invoiceData['total_amount'] ?? 0,
                'status' => $invoiceData['status'] ?? 'pending',
                'paid_at' => $invoiceData['paid_at'] ?? now(),
            ]);

            return response()->json(['message' => 'Facture enregistrée avec succès', 'invoice' => $invoice], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la facture : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de l\'enregistrement de la facture'], 500);
        }
    }
}
