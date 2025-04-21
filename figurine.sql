-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 20 avr. 2025 à 14:50
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `figurine`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id_article` int(11) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
  `date_publication` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `titre`, `image`, `description`, `date_publication`) VALUES
(29, 'Bien débuter : le matériel essentiel', '6804c1594a9f5_materiel.webp', 'Quand on commence la peinture sur figurines, il est facile de se perdre dans l’abondance de matériel disponible. Pourtant, il suffit de quelques éléments bien choisis pour se lancer dans de bonnes conditions. Un bon éclairage est fondamental : une lampe LED avec lumière naturelle vous aidera à distinguer les couleurs avec précision. Côté pinceaux, privilégiez ceux à pointe fine pour les détails et un pinceau plat pour les aplats et les bases. Les peintures acryliques sont les plus courantes dans ce hobby, car elles sont faciles à utiliser, sèchent rapidement et offrent un large éventail de couleurs. N&#039;oublions pas la palette humide, précieuse pour éviter que vos peintures ne sèchent trop vite, ainsi qu’un pot d’eau propre, du sopalin et une surface de travail protégée. Avec ces quelques outils bien sélectionnés, vous êtes prêt à démarrer l’aventure !', '2025-04-20 11:41:45'),
(30, 'Choisir ses peintures', '6804c1be240c2_peinture.webp', 'Le choix des peintures peut sembler intimidant au premier abord. De nombreuses marques se partagent le marché, chacune avec ses spécificités. Games Workshop (Citadel), Vallejo, Army Painter, ou encore Scale75 sont parmi les plus populaires. Chaque marque propose ses propres textures, conditionnements et nuances. Certaines sont plus couvrantes, d’autres plus fluides. Il est donc utile de tester différentes gammes pour trouver celle qui correspond à votre style. Pensez aussi aux peintures métalliques, contrastes ou aux encres, qui peuvent enrichir votre palette et donner des effets saisissants à vos figurines. À terme, vous apprendrez à combiner les atouts de plusieurs marques pour obtenir des rendus uniques et adaptés à chaque projet.', '2025-04-20 11:43:26'),
(31, 'Techniques de base : les fondations d’un bon rendu', '6804c20b72658_technique.webp', 'Avant de vous lancer dans des effets spéciaux ou des dégradés complexes, il est crucial de maîtriser les techniques de base. L’aplat est la première étape : il consiste à appliquer une couleur de base de manière uniforme. Le brossage à sec, ou drybrush, est idéal pour faire ressortir les détails en relief avec une teinte plus claire. Le lavis, quant à lui, permet d’ombrer les creux de manière subtile à l’aide d’une peinture très diluée. Ces techniques simples apportent immédiatement du volume et de la lisibilité à une figurine. En les combinant, on peut déjà atteindre des résultats très convaincants, même sans être un expert. Avec un peu de pratique, elles deviennent des réflexes naturels dans chaque session de peinture.', '2025-04-20 11:44:43'),
(32, 'Les machines utiles : aérographe, mélangeurs..', '6804c27a3a31c_gadget.webp', 'Même si le pinceau reste l’outil principal, certains appareils peuvent véritablement améliorer votre confort et vos résultats. L’aérographe est probablement l’outil le plus emblématique pour les peintres expérimentés : il permet d’appliquer des couches très fines et de réaliser des dégradés doux sur de grandes surfaces. Idéal pour les figurines de grande taille ou les véhicules. Un mélangeur de peinture peut également vous faire gagner du temps et assurer une homogénéité parfaite. Enfin, les supports rotatifs, pinces, lampes à bras articulés ou cabines d’aérographie sont autant d’accessoires qui rendent vos sessions plus efficaces et agréables. Bien utilisés, ces outils vous aident à travailler plus vite, mieux, et avec plus de plaisir.', '2025-04-20 11:46:34'),
(33, 'Les erreurs courantes à éviter quand on débute', '6804c2c07b094_erreur.jpg', 'Tous les peintres font des erreurs, surtout au début. Mais certaines sont facilement évitables ! Par exemple, peindre directement une figurine sans la sous-coucher est une erreur fréquente : la peinture ne tiendra pas correctement et manquera d’adhérence. Autre piège classique : surcharger son pinceau, ce qui entraîne des débordements et des détails noyés. Le manque de patience est aussi un ennemi redoutable : laisser sécher les couches entre chaque étape est indispensable. Enfin, vouloir absolument tout peindre « parfaitement » dès le début peut décourager. Il vaut mieux viser le progrès plutôt que la perfection. Apprendre de ses erreurs fait partie intégrante du hobby !', '2025-04-20 11:47:44'),
(34, 'Peindre les détails : astuces pour yeux, métaux..', '6804c31cccc69_detail.webp', 'Les détails font toute la différence sur une figurine, mais ils demandent précision et méthode. Pour les yeux, une astuce consiste à peindre d’abord la forme blanche, puis à ajouter la pupille avec un cure-dent ou un pinceau très fin. Les métaux peuvent être traités avec des peintures métalliques classiques, mais aussi enrichis grâce à des techniques comme le NMM (Non-Metallic Metal), qui imitent le métal sans pigments métalliques. Pour les textures comme la peau, le cuir ou les écailles, pensez à varier les tons et à ajouter de petites touches de lumière ou d’ombre pour créer du relief. Chaque détail bien travaillé attire le regard et donne vie à l’ensemble.', '2025-04-20 11:49:16');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id_avis` int(11) NOT NULL,
  `msg_avis` text DEFAULT NULL,
  `date_avis` datetime DEFAULT current_timestamp(),
  `id_produit` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id_avis`, `msg_avis`, `date_avis`, `id_produit`, `id_utilisateur`) VALUES
(11, '“Les détails sur chaque tête sont sublimes. Le rendu des dés est impeccable, c’est une pièce centrale dans ma vitrine.”', '2025-04-20 11:20:37', 25, 1),
(12, '“Un bijou. Je l’ai offerte à un ami MJ, il en est fou.”', '2025-04-20 11:20:57', 25, 1),
(13, '“Incroyablement originale. L’acide qui coule, les corps fondus... ça raconte une histoire sombre.”', '2025-04-20 11:21:29', 35, 1),
(14, '“On dirait une cinématique de jeu figée. La mise en scène est juste dingue.”', '2025-04-20 11:21:48', 38, 1),
(15, '“Les teintes bleues donnent un côté spectral superbe. Figurine très impressionnante.”', '2025-04-20 11:22:01', 38, 1),
(17, 'Très bonne surprise. Parfaite pour un diorama fantasy.', '2025-04-20 11:22:51', 38, 1),
(18, 'Très belle pièce. Peut-être un peu plus sobre que ce que j’imaginais, mais la qualité est là', '2025-04-20 11:23:14', 33, 1),
(19, 'Une des plus belles figurines que j’ai reçues. On dirait un animal totem ancestral. L’ambiance glacée est parfaite.', '2025-04-20 11:23:56', 37, 1),
(20, 'Très bien faite, je regrette juste qu’elle n’ait pas de socle décoratif. Sinon, rien à dire.', '2025-04-20 11:24:23', 24, 1),
(21, 'Hyper original ! On dirait un gladiateur des océans. Très bonne présence.', '2025-04-20 11:24:51', 29, 1),
(22, 'Le style un peu caricatural me plaît beaucoup. Peut-être pas le plus ‘effrayant’ mais super fun.', '2025-04-20 11:25:17', 22, 1),
(23, 'Un petit format très stylé ! Je l’ai offert à un ami fan de pirates, il était ravi.', '2025-04-20 11:25:42', 23, 1),
(24, 'Effrayant comme il faut ! Le sang en cascade est super bien pensé. Une pièce forte.', '2025-04-20 11:26:12', 26, 1),
(25, 'Une touche d’humour macabre ! Ce squelette dans sa baignoire est à mourir de rire. Gros coup de cœur.', '2025-04-20 11:27:06', 32, 1),
(26, 'Parfait pour mon univers elfique. Il a l’air de sortir tout droit d’un conte sombre.', '2025-04-20 11:27:45', 36, 1);

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom`) VALUES
(1, 'Créatures Mythologiques et Fantastiques'),
(2, 'Monstres et Créatures Obscures'),
(3, 'Personnages Épiques et Guerriers'),
(4, 'Créatures Naturelles et Mystiques');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `statut` enum('en cours','prepartion','expedier','livrer') DEFAULT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `date_commande`, `statut`, `id_utilisateur`) VALUES
(20, '2025-04-20 14:25:47', 'en cours', 1);

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` int(11) NOT NULL,
  `msg_blog` text DEFAULT NULL,
  `date_commentaire` datetime DEFAULT current_timestamp(),
  `id_article` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `msg_blog`, `date_commentaire`, `id_article`, `id_utilisateur`) VALUES
(22, 'Super utile pour les débutants ! J’ai commencé avec un vieux pinceau et une lampe de bureau, et je vois la différence maintenant que j’ai investi dans une vraie lampe LED. Merci pour les conseils clairs !', '2025-04-20 11:52:44', 29, 1),
(23, 'Le coup de la palette humide, c’est un game changer ! Je peignais trop vite avant pour éviter que la peinture ne sèche… plus besoin maintenant', '2025-04-20 11:52:55', 29, 1),
(24, 'Le brossage à sec c’est magique ! Je suis débutant, et dès que j’ai testé cette technique, mes figurines ont pris une toute autre allure. Top article !', '2025-04-20 11:53:49', 31, 1),
(25, 'Excellent article. Peut-être que tu pourrais ajouter un conseil sur le fait de ne pas acheter 200 figurines dès le début… on connaît tous ce piège', '2025-04-20 11:54:12', 33, 1),
(26, 'Le coup du cure-dent pour les yeux, c’est vraiment malin ! J’avais jamais pensé à ça. Merci pour l’astuce.', '2025-04-20 11:54:30', 34, 1),
(27, 'Le paragraphe sur le NMM m’a motivé à tenter enfin l’expérience ! Si tu fais un article dédié à ça, je suis preneur !', '2025-04-20 11:54:41', 34, 1);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prix` decimal(19,4) NOT NULL,
  `description` text NOT NULL,
  `taille` decimal(15,2) DEFAULT NULL,
  `image_1` varchar(50) NOT NULL,
  `image_2` varchar(50) DEFAULT NULL,
  `image_3` varchar(50) DEFAULT NULL,
  `date_produit` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `nom`, `prix`, `description`, `taille`, `image_1`, `image_2`, `image_3`, `date_produit`) VALUES
(22, 'Krag', 20.0000, 'Petit par la taille, mais immense par l’énergie, Krag brandit un tronc d’arbre comme un jouet. Ce yéti miniature, au regard curieux et à la fourrure épaisse, respire la vitalité des hauts sommets.\r\nSon attitude évoque l’espièglerie d’un enfant... capable d’écraser un ours. Entre humour, nature brute et charme rustique, Krag trouvera sa place dans n’importe quelle collection.', 8.00, 'yeti.webp', 'yeti2.webp', NULL, '2025-04-20 10:15:00'),
(23, 'Lames Maudites', 20.0000, 'Sabre dans chaque main, visage déformé par la colère, Lames Maudites des Mers fend l’air d’un cri silencieux. Ce pirate damné, surgissant d’une brume imaginaire, semble prêt à bondir hors de son socle.\r\nChaque vêtement est déchiré par le sel, chaque boucle rouillée par les siècles. Une figurine pleine d’élan, digne d’un abordage éternel.', 8.00, '6804af00ed22b_sabre.webp', 'sabre2.webp', NULL, '2025-04-20 10:30:00'),
(24, 'Œil de Gorgone', 22.0000, 'Elle se tient droite, impérieuse, une main sur son arc, les serpents de sa chevelure frémissant d’une vie propre. Œil de Gorgone n’est pas qu’une représentation de Méduse : c’est une relecture guerrière, où la figure maudite devient chasseresse.\r\nSon regard pétrifie toujours, mais c’est désormais en traqueuse qu’elle agit, arme à la main. Les détails sont d’une précision remarquable, des écailles fines aux pupilles fendue, jusqu’aux plumes de ses flèches. Petite en taille, mais immense par sa présence.', 9.00, '6804b06138190_archer.webp', 'archer2.webp', NULL, '2025-04-20 10:45:00'),
(25, 'Les Gardiens', 150.0000, 'Les Gardiens des Dés Anciens présentent cinq têtes de dragons imposantes, chacune ornée d’un dé de jeu de rôle posé sur son crâne. Ces créatures mythologiques, aux écailles détaillées et aux crocs menaçants, incarnent la fusion du pouvoir ancestral et du destin. Les dés, objets mystiques, ajoutent une touche ludique et symbolique, rappelant l’équilibre entre chance et stratégie. Cette figurine est un chef-d&#039;œuvre à la fois majestueux et intrigant, parfait pour les collectionneurs et les passionnés de fantasy. Une pièce incontournable pour tout espace de jeu ou étagère.', 22.00, '6804b1e2ee81a_dragon.webp', 'dragon2.webp', 'dragon3.webp', '2025-04-20 11:00:00'),
(26, 'Puits des Damnés', 110.0000, 'Un démon au torse tendu, cornes de bouc, trône au-dessus d’une fontaine de sang. Son sourire carnassier, ses griffes acérées et sa pose dominatrice font de Le Puits des Damnés une scène de domination infernale.\r\nLe sang semble couler encore. L’ensemble dégage une chaleur oppressante, comme si l’enfer n’était pas loin. Une pièce forte pour les amateurs de l’infernal.', 16.00, '6804b2738e2b1_demon.webp', 'demon2.webp', 'demon3.webp', '2025-04-20 11:15:00'),
(28, 'Cheval de Minuit', 60.0000, 'Dans une main, elle tient un bâton. Au sommet : une tête de cheval. Cheval de Minuit est une créature féminine tordue, voilée, dont la silhouette courbée dissimule une intensité folle.\r\nSon visage est presque humain, mais ses yeux sont creux. Sa robe semble bouger même à l’arrêt. On sent la sorcellerie noire et les rituels interdits. Une pièce dérangeante et obsédante.', 14.00, '6804b41587921_cheval.webp', 'cheval2.webp', 'cheval3.webp', '2025-04-20 11:30:00'),
(29, 'Briseur de Vagues', 95.0000, 'Émergeant des flots, Briseur de Vagues dévoile un torse musculeux et des dents prêtes à déchiqueter. Ce requin humanoïde, mi-chasseur, mi-champion, est une incarnation brute de la rage marine.\r\nSon dos couvert de cicatrices raconte mille combats, et son air renfrogné ne laisse place à aucun doute : il vient pour dominer. Une figurine dynamique, entre mythe marin et gladiateur des abysses.', 15.00, '6804b495a26bd_requin.webp', 'requin2.webp', 'requin3.webp', '2025-04-20 11:45:00'),
(30, 'Visage de Verre', 210.0000, 'Le visage d’une femme s’ouvre comme un coffre. Ses traits sont délicats, mais ses yeux sont surmontés de vitraux aux formes étranges, comme des fragments d’une prophétie oubliée.\r\nÀ l’intérieur, des dés trouvent leur place. Visage de Verre est un objet rituel, une offrande au hasard et au mystère. Elle allie beauté, énigme et fonction, pour ceux qui jouent avec le destin.', 25.00, '6804b522ba331_visage.webp', 'visage2.webp', 'visage3.webp', '2025-04-20 12:00:00'),
(31, 'Lame des Cieux', 130.0000, 'Ses ailes déployées dans un éclat de plumes violacées, La Lame des Cieux Pourpres fend l’air dans un cri silencieux. Cette harpie guerrière, l’arme levée, semble surgir d’un ciel crépusculaire, où la guerre fait rage entre étoiles mortes et dieux oubliés.\r\nSon regard, dur comme la pierre, fixe un ennemi invisible. Son corps tendu, ses serres crispées, son vol figé dans l’instant, capturent une intensité brutale. Chaque plume, chaque pli de sa peau, chaque éclat de sa lame est un hommage à la violence aérienne et à la beauté des guerrières légendaires. La Lame des Cieux Pourpres est l’ultime messagère des batailles célestes.', 18.00, '6804b612cbd99_harpie.webp', 'harpie2.webp', 'harpie3.webp', '2025-04-20 12:15:00'),
(32, 'Bain d’Outretombe', 85.0000, 'Dans une baignoire rustique, un squelette se prélasse comme au premier jour, un canard jaune flottant à ses côtés. Le contraste est saisissant : l’humour léger du bain face à la mort personnifiée, rigolarde et décomplexée.\r\nLa scène regorge de détails : des bulles, une jambe osseuse dépassant de l’eau, un petit savon en forme de crâne. Bain d’Outretombe joue avec le macabre et le quotidien dans une composition qui fait sourire autant qu’elle dérange. Une pièce parfaite pour ceux qui aiment l’absurde, même après la mort.', 14.00, '6804b6c80aae9_squelette.webp', 'squelette2.webp', 'squelette3.webp', '2025-04-20 12:30:00'),
(33, 'Anubis', 180.0000, 'Tel un dieu surgissant des profondeurs du temps et des eaux mystiques, Anubis des Marées Éternelles mêle avec élégance la majesté de l&#039;Égypte antique et les mystères d’un autre monde. Ce buste imposant représente le célèbre gardien des morts, trônant fièrement sur une vague stylisée qui semble figer l’instant dans une tempête divine. Les tons bleu nuit et violet profond enveloppent la figurine d’une aura surnaturelle, rehaussée par des touches d’or qui accentuent son rang divin.\r\n\r\nAu centre de son torse trône une pierre bleue étincelante, comme un cœur d’azur vibrant d’une magie oubliée. Elle capte la lumière et attire le regard, renforçant l’impression que cette relique détient un pouvoir ancien, peut-être celui de juger les âmes ou d’ouvrir les portes d’un royaume englouti. Le visage d’Anubis, à la fois noble et implacable, est sculpté avec une précision remarquable, son regard fixe semblant sonder les profondeurs de l’âme.\r\n\r\nC’est une pièce aussi mystique que puissante, parfaite pour quiconque cherche à ajouter une touche divine et mystérieuse à sa collection. Anubis des Marées Éternelles ne se contemple pas seulement, il impose le silence et le respect.', 20.00, '6804b8807fbae_anubis.webp', 'anubis2.webp', 'anubis3.webp', '2025-04-20 12:45:00'),
(34, 'Aquila Tempestatis', 165.0000, 'Né du souffle des tempêtes oubliées, Aquila Tempestatis incarne l’esprit sauvage des cieux anciens. Ce buste majestueux d’un aigle humanoïde trône sur une spirale de vent solidifiée, une tornade rugissante figée par la magie ou le temps. Son regard acéré perce l’horizon, témoin de millénaires de batailles célestes.\r\nChaque plume semble animée d’un frisson, comme si la créature allait se remettre à battre des ailes et s’envoler d’un instant à l’autre. La tension de ses muscles, la courbe parfaite de ses serres, tout évoque une puissance calme, contenue, prête à éclater. Aquila Tempestatis est plus qu’une sculpture : c’est une relique venue d’un monde où les cieux avaient une volonté propre.', 18.00, '6804b91c9d38f_aigle.webp', 'aigle2.webp', 'aigle3.webp', '2025-04-20 13:45:00'),
(35, 'L’Alchimiste', 205.0000, 'Dans les ténèbres d’un ciel empoisonné plane Nocturne de l’Alchimiste, une chauve-souris immense, ravagée par la maladie, tenant entre ses pattes crochues un chaudron bouillonnant d’acide. Le liquide toxique dégouline lentement, goutte à goutte, sur les malheureux qui se dissolvent sous elle dans une agonie muette.\r\nSon corps déformé trahit les effets de ses propres expériences alchimiques, ses ailes déchirées semblant vibrer d’une malédiction ancienne. Les détails foisonnent : des veines apparentes sous une peau translucide, des dents trop nombreuses pour une simple créature. Nocturne de l’Alchimiste est une scène de cauchemar, figée avec une précision troublante, parfaite pour les amateurs d’univers sombres et dérangeants.', 20.00, '6804b995e8e9f_maladie.webp', 'maladie2.webp', 'maladie3.webp', '2025-04-20 13:30:00'),
(36, 'Sylvarok', 180.0000, 'Sylvarok se dresse, silhouette d’arbre sculptée dans l’écorce ancienne, traversée de cristaux violets étincelants. Ses bras semblent être des branches mouvantes, et son regard, bien que creux, inspire une sagesse millénaire.\r\nIl est le dernier gardien d’une forêt oubliée, dont les secrets résonnent encore dans ses racines. Une figurine majestueuse et sereine, parfaite pour qui cherche la force tranquille du monde végétal.', 20.00, '6804ba2b0cbf9_arbre.webp', 'arbre2.webp', 'arbre3.webp', '2025-04-20 13:50:00'),
(37, 'Hurlevent Polaire', 250.0000, 'Sur un rocher gelé trône Hurlevent Polaire, un hibou gigantesque, aux cornes cristallines et au plumage gorgé de givre. Ses ailes étendues semblent retenir la tempête elle-même.\r\nIl ne vole pas, il plane au-dessus du monde, porteur de présages. Son regard vide transperce la nuit. Cette figurine, imposante et solennelle, évoque les grands esprits des neiges, ceux qu’on implore avant l’hiver. Une œuvre aussi froide que majestueuse.', 19.00, '6804baa1ad55e_hiboux.webp', 'hiboux2.webp', '', '2025-04-20 13:00:00'),
(38, 'Le Trône d’Os', 270.0000, 'Derrière un trône de pierre et d’os, sculpté dans la mémoire même de la guerre, se dresse un squelette colossal aux os teintés de bleu spectral. Assis sur ce trône, un chevalier silencieux, figé pour l’éternité, contemple un royaume perdu.\r\nLa scène est figée comme une fresque antique : le squelette, gardien silencieux du trône, veille, bras croisés, sur son maître défunt. Le contraste entre la fragilité apparente du squelette et la posture fière du chevalier donne à cette pièce une atmosphère à la fois tragique et majestueuse. Le Trône d’Os est une scène muette, pleine d’échos, de batailles perdues, de serments tenus dans la mort.', 22.00, '6804bb658e470_mort.webp', 'mort2.webp', 'mort3.webp', '2025-04-20 14:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `produit_categorie`
--

CREATE TABLE `produit_categorie` (
  `id_produit` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit_categorie`
--

INSERT INTO `produit_categorie` (`id_produit`, `id_categorie`) VALUES
(22, 1),
(23, 3),
(24, 1),
(25, 1),
(26, 2),
(28, 2),
(29, 2),
(29, 3),
(30, 1),
(31, 1),
(31, 3),
(32, 2),
(33, 1),
(33, 3),
(34, 1),
(34, 4),
(35, 2),
(36, 4),
(37, 1),
(38, 3);

-- --------------------------------------------------------

--
-- Structure de la table `produit_commande`
--

CREATE TABLE `produit_commande` (
  `id_produit` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `quantite` tinyint(4) DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit_commande`
--

INSERT INTO `produit_commande` (`id_produit`, `id_commande`, `quantite`, `prix`) VALUES
(36, 20, 1, 180.00);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `date_creation` date DEFAULT current_timestamp(),
  `mdp` varchar(100) NOT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `role` enum('utilisateur','admin') NOT NULL,
  `statut` enum('actif','supprimer') DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `mail`, `date_creation`, `mdp`, `adresse`, `role`, `statut`) VALUES
(1, 'Marchisone', 'Delphine', 'delphine4492@gmail.com', '2025-04-11', '$2y$10$EB/ycZKYngyiZ4E3TXM4vO4m4ljp1yd/liQSGDXvLS7yaGpeBRB3O', 'Mon adresse test.\r\nHéhé, tu ne crois pas que tu vas savoir où j&#039;habite quand même ;)', 'admin', 'actif'),
(3, 'Diego', 'Gogo', 'diego.gogo@email.com', '2025-04-12', '$2y$10$qelsY7P3OmA9g2t235v1UOJ2J7o2rOCHicSaGClfJd9Jyud8vZ0e2', 'Rue de la plage et du sable', '', 'supprimer'),
(6, 'vert', 'actif', 'vert@email.com', '2025-04-13', '$2y$10$yruhNF20.1308l1cugG.xOD3wLkhE8Ty1qyX4V9PBCzTWldeRjWJu', NULL, 'utilisateur', 'supprimer'),
(7, 'chips', 'fromage', 'chips.fromage@email.com', '2025-04-13', '$2y$10$Bjx1HlFRYV9V3Xg7q7yqiuqPz/0Dhyi8PEC0YUVtEXAzhkfNiV5Ea', NULL, 'utilisateur', 'supprimer');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id_avis`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_article` (`id_article`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `produit_categorie`
--
ALTER TABLE `produit_categorie`
  ADD PRIMARY KEY (`id_produit`,`id_categorie`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `produit_commande`
--
ALTER TABLE `produit_commande`
  ADD PRIMARY KEY (`id_produit`,`id_commande`),
  ADD KEY `id_commande` (`id_commande`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id_article` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id_avis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `produit_categorie`
--
ALTER TABLE `produit_categorie`
  ADD CONSTRAINT `produit_categorie_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `produit_categorie_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);

--
-- Contraintes pour la table `produit_commande`
--
ALTER TABLE `produit_commande`
  ADD CONSTRAINT `produit_commande_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `produit_commande_ibfk_2` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
