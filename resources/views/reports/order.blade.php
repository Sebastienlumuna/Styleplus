<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des commandes</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; }
        h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f5f5; }
        .total { font-weight: bold; background: #e9ecef; }
    </style>
</head>
<body>
    <h2>Rapport des commandes</h2>
    <p>Total de commandes : <strong>{{ $totalCommandes }}</strong></p>
    <p>Total de commandes payées : <strong>{{ $totalPayees }}</strong></p>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name ?? 'N/A' }}</td>
                <td>{{ number_format($order->total, 2, ',', ' ') }} $</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>