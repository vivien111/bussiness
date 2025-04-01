<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Wave\Plan;
use Wave\Subscription;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Subscription as CashierSubscription;

class SubscriptionController extends Controller
{
    public function create(Plan $plan)
    {
        return view('subscriptions.create', compact('plan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'duration' => 'required|in:monthly,yearly,lifetime',
            'payment_method' => 'required',
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        // Correspondance avec Stripe Price ID
        $stripePriceIds = [
            'monthly' => $plan->monthly_price_id,
            'yearly' => $plan->yearly_price_id,
            'lifetime' => $plan->onetime_price_id,
        ];

        $priceId = $stripePriceIds[$request->duration] ?? null;

        if (!$priceId) {
            return redirect()->back()->withErrors(['duration' => 'Plan non disponible pour cette durée']);
        }

        // Attacher la méthode de paiement à l'utilisateur
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($request->payment_method);

        // Création de l'abonnement Stripe
        $stripeSubscription = $user->newSubscription('default', $priceId)
            ->create($request->payment_method);

        // Sauvegarde de l'abonnement en base de données
        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'vendor_slug' => 'stripe',
            'stripe_id' => $stripeSubscription->id,
            'stripe_status' => 'active',
            'cycle' => $request->duration,
            'seats' => 1,
            'trial_ends_at' => now()->addDays(30),
            'ends_at' => $request->duration === 'lifetime' ? null : now()->addDays($request->duration === 'yearly' ? 365 : 30),
        ]);

        return redirect()->route('dashboard')->with('success', 'Abonnement créé avec succès');
    }

    public function cancel()
    {
        $user = Auth::user();
        $subscription = $user->subscription('default');

        if ($subscription) {
            $subscription->cancel();
            return redirect()->route('dashboard')->with('success', 'Abonnement annulé');
        }

        return redirect()->route('dashboard')->withErrors(['error' => 'Aucun abonnement actif']);
    }

    public function status()
    {
        $user = Auth::user();
        $subscription = $user->subscription('default');

        if (!$subscription) {
            return response()->json(['status' => 'none']);
        }

        return response()->json([
            'status' => $subscription->stripe_status,
            'ends_at' => $subscription->ends_at,
        ]);
    }
}
