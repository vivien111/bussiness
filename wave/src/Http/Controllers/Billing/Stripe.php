<?php

namespace Wave\Http\Controllers\Billing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Checkout\Session;
use Wave\Plan;
use Wave\Subscription;

class Stripe extends Controller
{
    public function redirect_to_customer_portal()
    {
        $latest_active_subscription = auth()->user()->latestSubscription();
    
        if (!$latest_active_subscription) {
            return redirect()->route('settings.subscription')->with('error', 'Aucune souscription active trouvée.');
        }
    
        $stripe = new \Stripe\StripeClient(config('wave.stripe.secret_key'));
    
        $billingPortal = $stripe->billingPortal->sessions->create([
            'customer' => $latest_active_subscription->vendor_customer_id,
            'return_url' => route('settings.subscription'),
        ]);
    
        return redirect($billingPortal->url);
    }
    
}