<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Toevoegen of Afrekenen</title>
    <style>
        /* Algemene reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Basisstijl voor het lichaam */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        /* Stijl voor de koptekst */
        h1 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 30px;
        }

        /* Stijl voor de links naar toevoegen en afrekenen */
        .actie-links {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .actie-links a {
            text-decoration: none;
            padding: 15px 25px;
            background-color: #4CAF50;
            color: white;
            border-radius: 8px;
            width: 200px;
            text-align: center;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .actie-links a:hover {
            background-color: #45a049;
        }

        /* Stijl voor mobiele apparaten */
        @media (max-width: 768px) {
            .actie-links a {
                width: 100%;
                padding: 12px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <h1>Wat wilt u doen?</h1>
    <div class="actie-links">
        <?php
        $idTafel = $_GET['idtafel'] ?? false;
        if ($idTafel) {
            echo "<a href='product.php?idtafel={$idTafel}'>Toevoegen</a>";
            echo "<a href='rekening.php?idtafel={$idTafel}'>Afrekenen</a>";
        } else {
            http_response_code(404);
            include('error_404.php');
            die();
        }
        ?>
    </div>
</body>
</html>
