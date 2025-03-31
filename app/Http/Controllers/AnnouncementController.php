<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Country;
use App\Models\State;
use App\Models\Invoice;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with(['country', 'state', 'invoice'])
                        ->where('user_id', auth()->id())
                        ->latest()
                        ->get();
        
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        $countries = Country::all();
        $invoices = app(UserInvoiceController::class)->index()->getData()->invoices; // Récupère les factures
        
        return view('filament.annonce', compact('countries', 'invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'image' => 'nullable|image|max:2048',
            'link' => 'nullable|url',
            'price' => 'nullable|numeric',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('announcements');
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending'; // Statut par défaut

        Announcement::create($validated);

        return redirect()->route('announcements.index')->with('success', 'Annonce créée avec succès');
    }

    public function show(Announcement $announcement)
    {
        $this->authorize('view', $announcement);
        
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        $this->authorize('update', $announcement);
        
        $countries = Country::all();
        $states = State::where('country_id', $announcement->country_id)->get();
        $invoices = Invoice::where('user_id', auth()->id())->get();
        
        return view('announcements.edit', compact('announcement', 'countries', 'states', 'invoices'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $this->authorize('update', $announcement);
        
        $validated = $request->validate([
            // Mêmes règles que store
        ]);

        // Gestion de l'image...
        
        $announcement->update($validated);

        return redirect()->route('announcements.index')->with('success', 'Annonce mise à jour');
    }

    public function destroy(Announcement $announcement)
    {
        $this->authorize('delete', $announcement);
        
        $announcement->delete();
        
        return back()->with('success', 'Annonce supprimée');
    }

    // AJAX: Récupérer les états d'un pays
    public function getStates($country_id)
    {
        return State::where('country_id', $country_id)->get();
    }
}