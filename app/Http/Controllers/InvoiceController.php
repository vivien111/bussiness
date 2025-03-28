<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Http\Request;
use PDF; // Pour la génération de PDF (si vous utilisez dompdf/laravel-dompdf)

class InvoiceController extends Controller
{
    /**
     * Affiche la liste des factures
     */
    public function index()
    {
        $invoices = Invoice::with(['user', 'items'])
            ->latest()
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $users = User::all();
        return view('invoices.create', compact('users'));
    }

    /**
     * Enregistre une nouvelle facture
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Calcul du montant total
        $totalAmount = collect($validated['items'])->sum(function ($item) {
            return $item['amount'] * $item['quantity'];
        });

        // Création de la facture
        $invoice = Invoice::create([
            'user_id' => $validated['user_id'],
            'total_amount' => $totalAmount,
            'status' => 'draft',
        ]);

        // Ajout des items
        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'amount' => $item['amount'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture créée avec succès');
    }

    /**
     * Affiche une facture spécifique
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['user', 'items']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Invoice $invoice)
    {
        $users = User::all();
        $invoice->load('items');
        return view('invoices.edit', compact('invoice', 'users'));
    }

    /**
     * Met à jour une facture
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,sent,paid,cancelled',
            'items' => 'required|array',
            'items.*.id' => 'sometimes|exists:invoice_items,id',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Calcul du nouveau montant total
        $totalAmount = collect($validated['items'])->sum(function ($item) {
            return $item['amount'] * $item['quantity'];
        });

        // Mise à jour de la facture
        $invoice->update([
            'user_id' => $validated['user_id'],
            'total_amount' => $totalAmount,
            'status' => $validated['status'],
        ]);

        // Synchronisation des items
        $currentItemIds = $invoice->items->pluck('id')->toArray();
        $updatedItemIds = [];

        foreach ($validated['items'] as $item) {
            if (isset($item['id'])) {
                // Mise à jour d'un item existant
                $invoiceItem = $invoice->items()->find($item['id']);
                $invoiceItem->update([
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                    'quantity' => $item['quantity'],
                ]);
                $updatedItemIds[] = $item['id'];
            } else {
                // Création d'un nouvel item
                $newItem = $invoice->items()->create([
                    'description' => $item['description'],
                    'amount' => $item['amount'],
                    'quantity' => $item['quantity'],
                ]);
                $updatedItemIds[] = $newItem->id;
            }
        }

        // Suppression des items qui ne sont plus dans la liste
        $itemsToDelete = array_diff($currentItemIds, $updatedItemIds);
        if (!empty($itemsToDelete)) {
            $invoice->items()->whereIn('id', $itemsToDelete)->delete();
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Facture mise à jour avec succès');
    }

    /**
     * Supprime une facture
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Facture supprimée avec succès');
    }

    /**
     * Télécharge le PDF d'une facture
     */
    public function download(Invoice $invoice)
    {
        $invoice->load(['user', 'items']);
        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        
        return $pdf->download("facture-{$invoice->id}.pdf");
    }

    /**
     * Marque une facture comme payée
     */
    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Facture marquée comme payée');
    }
}