<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= isset($description) ? htmlspecialchars($description) : 'Figurine, boutique et figurines artisanales' ?>">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Figurine' ?></title>
    <base href="<?= FULL_URL_PATH ?>">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="shortcut icon" type="image/png" href="public/images/logo.ico"/>
</head>

<body class="<?= isset($isHome) && $isHome ? 'home' : 'inner' ?>">
    <?php
    require SRC_DIR . "/view/header.php";
    ?>
    <main><?= $content ?></main>
    <?php
    require SRC_DIR . "/view/footer.php";
    ?>
</body>

</html>