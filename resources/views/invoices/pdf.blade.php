<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $invoice->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: auto; }
        .header { text-align: center; font-size: 20px; font-weight: bold; }
        .details { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Facture #{{ $invoice->id }}</div>
        <div class="details">
            <p>Client : {{ $invoice->user->name }}</p>
            <p>Montant : {{ number_format($invoice->total_amount, 2) }} €</p>
            <p>Status : {{ ucfirst($invoice->status) }}</p>
            <p>Date de paiement : {{ $invoice->paid_at ? \Carbon\Carbon::parse($invoice->paid_at)->format('d/m/Y') : 'Non payé' }}</p>
            </div>
    </div>
</body>
</html>
