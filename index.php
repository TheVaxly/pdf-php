<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jõulutervitused</title>
    <style>
        body {
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 100px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 10px;
            display: inline-block;
        }
        input, textarea, button {
            width: 80%;
            margin: 10px auto;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Saada Jõulutervitus</h1>
        <form method="post" action="send.php">
            <input type="text" name="saaja_nimi" placeholder="Saaja nimi" required><br>
            <input type="email" name="saaja_email" placeholder="Saaja e-mail" required><br>
            <textarea name="sonum" rows="5" placeholder="Kirjuta tervitussõnum" required></textarea><br>
            <button type="submit">Saada</button>
        </form>
    </div>
</body>
</html>
