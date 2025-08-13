<!DOCTYPE html>
<html>
<head>
    <title>Nova Submissão de Demanda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        p {
            font-size: 16px;
            color: #555;
        }
        p strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nova Submissão de Demanda</h1>
        <p><strong>Nome:</strong> {{ $data['name'] }}</p>
        <p><strong>E-mail:</strong> {{ $data['email'] }}</p>
        <p><strong>Descrição da Demanda:</strong> {{ $data['demand_description'] }}</p>
        <p><strong>Utilidade Esperada:</strong> {{ $data['expected_utility'] }}</p>
    </div>
</body>
</html>