<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #{{ $payment->id }}</title>
    <style>
        /* Variables globales */
        :root {
            --primary-dark: #1a1a1a;
            --secondary-dark: #2d2d2d;
            --light-gray: #f8f9fa;
            --medium-gray: #6c757d;
            --accent-color: #e63946;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            color: var(--primary-dark);
            background: #fff;
            margin: 20px;
            font-size: 14px;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 30px;
        }

        .invoice-header {
            text-align: center;
            border-bottom: 2px solid var(--light-gray);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .invoice-header h1 {
            font-size: 1.8rem;
            margin: 0;
            color: var(--primary-dark);
        }

        .invoice-header p {
            margin: 5px 0;
            color: var(--medium-gray);
        }

        h3 {
            color: var(--accent-color);
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #eee;
            padding: 12px;
            text-align: left;
        }

        th {
            background: var(--light-gray);
            color: var(--primary-dark);
            font-weight: 600;
        }

        td {
            color: var(--secondary-dark);
        }

        .section {
            margin-top: 25px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: var(--medium-gray);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background: #d4edda;
            color: #155724;
        }

        .status-badge.failed {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <!-- HEADER -->
        <div class="invoice-header">
            <h1>Facture de Paiement</h1>
            <p>Transaction #{{ $payment->transaction_id }}</p>
            <p class="status-badge {{ $payment->status !== 'succeeded' ? 'failed' : '' }}">
                {{ ucfirst($payment->status) }}
            </p>
        </div>

        <!-- Détails Paiement -->
        <div class="section">
            <h3>Détails du Paiement</h3>
            <table>
                <tr><th>ID Paiement</th><td>#{{ $payment->id }}</td></tr>
                <tr><th>ID Commande</th><td>#{{ $payment->order_id }}</td></tr>
                <tr><th>Méthode</th><td>{{ ucfirst($payment->method) }}</td></tr>
                <tr><th>Montant</th><td><strong>{{ number_format($payment->amount/100, 2) }} {{ strtoupper($payment->currency) }}</strong></td></tr>
                <tr><th>Date</th><td>{{ $payment->created_at->format('d/m/Y H:i') }}</td></tr>
            </table>
        </div>

        <!-- Informations Client -->
        <div class="section">
            <h3>Informations Client</h3>
            <table>
                <tr><th>Nom</th><td>{{ $payment->user->name }}</td></tr>
                <tr><th>Email</th><td>{{ $payment->user->email }}</td></tr>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p>Merci pour votre confiance 🙏</p>
            <p>Ce document a été généré automatiquement et ne nécessite pas de signature.</p>
        </div>
    </div>
</body>
</html>
