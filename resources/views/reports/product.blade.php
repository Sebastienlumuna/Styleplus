<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des produits</title>
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
    <h2>Rapport des produits</h2>
    <p>Total général en stock : <strong>{{ $totalStock }}</strong></p>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Tailles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>{{ number_format($product->price,2,',',' ') }} $</td>
                <td>{{ $product->stock }}</td>
                <td>
                    @foreach(explode(',', $product->sizes) as $size)
                        <span>{{ strtoupper(trim($size)) }}</span>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>