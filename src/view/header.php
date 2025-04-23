<?php

use Figurine\Lib\FlashMessage;

$messages = FlashMessage::getMessages();
if (!empty($messages)): ?>
    <div id="flash-container">
        <?php foreach ($messages as $msg): ?>
            <div class="flash-message <?= htmlspecialchars($msg['type']) ?>">
                <?= htmlspecialchars($msg['message']) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (isset($subHeader)) : ?><div class="bloc-header"><?php endif; ?>

    <header class="header-home">
        <!-- Navigation -->
        <nav>
            <div class="nav-container">
                <div class="nav-left">
                    <!-- Logo et Titre -->
                    <div class="logo"><a href="home"><img src="public/images/logo.png" alt="Logo - Identité visuelle du site"></a></div>
                    <div class="nav-title">Throne Of<br>Miniatures</div>
                </div>

                <button class="burger-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg>
                </button>

                <div class="nav-right">
                    <ul class="nav-menu">
                        <li><a href="home">Accueil</a></li>
                        <li><a href="products">Boutique</a></li>
                        <li><a href="articles">Blog</a></li>
                        <?php if (isset($_SESSION['id_utilisateur'])): ?>
                            <li><a href="profil"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                    </svg></a></li>
                            <li><a href="panier"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-basket2-fill" viewBox="0 0 16 16">
                                        <path d="M5.929 1.757a.5.5 0 1 0-.858-.514L2.217 6H.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h.623l1.844 6.456A.75.75 0 0 0 3.69 15h8.622a.75.75 0 0 0 .722-.544L14.877 8h.623a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1.717L10.93 1.243a.5.5 0 1 0-.858.514L12.617 6H3.383zM4 10a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0zm3 0a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0zm4-1a1 1 0 0 1 1 1v2a1 1 0 1 1-2 0v-2a1 1 0 0 1 1-1" />
                                    </svg></a></li>
                            <li><a href="logout">Déconnexion<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z" />
                                    </svg></a></li>
                        <?php else: ?>
                            <li><a href="panier"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-basket2-fill" viewBox="0 0 16 16">
                                        <path d="M5.929 1.757a.5.5 0 1 0-.858-.514L2.217 6H.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h.623l1.844 6.456A.75.75 0 0 0 3.69 15h8.622a.75.75 0 0 0 .722-.544L14.877 8h.623a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1.717L10.93 1.243a.5.5 0 1 0-.858.514L12.617 6H3.383zM4 10a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0zm3 0a1 1 0 0 1 2 0v2a1 1 0 1 1-2 0zm4-1a1 1 0 0 1 1 1v2a1 1 0 1 1-2 0v-2a1 1 0 0 1 1-1" />
                                    </svg></a></li>
                            <li><a class="btn-primary" href="./login">Connexion</a></li>
                            <li><a class="btn-blue" href="create-account">Inscription</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?php if (isset($subHeader)) {
        echo $subHeader;
        echo "</div>";
    }
    ?>