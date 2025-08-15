<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Recebido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #eee;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Cadastro Recebido</h1>
    </div>

    <div class="content">
        <p>Olá {{ $user->name }},</p>

        <p>Seu cadastro foi recebido com sucesso em nossa plataforma! Agradecemos seu interesse em fazer parte da nossa associação.</p>

        <p>Seu cadastro será analisado por nossa equipe administrativa, e você receberá uma notificação assim que for aprovado.</p>

        <p>Importante: Por padrão, todos os novos cadastros ficam inativos até a aprovação por um administrador. Este processo garante a segurança e integridade da nossa comunidade.</p>

        <p>Caso tenha alguma dúvida, fique à vontade para entrar em contato conosco através do nosso formulário de contato.</p>
    </div>

    <div class="footer">
        <p>Atenciosamente,<br>Equipe Administrativa</p>
    </div>
</body>
</html>
