<?php
// =============================================================================
// PORTFOLIO DE SARA AGBEHONOU
// Page principale du site (page unique avec plusieurs sections ancrées #id).
// Ce fichier PHP prépare toutes les données (profil, compétences, projets)
// puis les affiche en HTML plus bas grâce à des boucles foreach.
// =============================================================================

declare(strict_types=1); // Force PHP à vérifier strictement les types (plus de sécurité)

// -----------------------------------------------------------------------
// 1) DONNÉES DU PROFIL
// Informations de contact affichées dans la section "Contact".
// -----------------------------------------------------------------------
$profile = [
    'name' => 'Sara Agbehonou',
    'role' => 'Ingénieure informatique en formation — Dev Web & Systèmes/Réseau',
    'location' => 'Neuilly-Plaisance (93)',
    'phone' => '+33 7 58 31 88 59',
    'email' => 'agbehonouaesther@gmail.com',
    'linkedin' => 'linkedin.com/in/sara-agbehonou',
];

// -----------------------------------------------------------------------
// 2) COMPÉTENCES
// Tableau associatif : la clé = nom de la catégorie (Front-end, Back-end...),
// la valeur = liste de compétences avec un nom, une icône (emoji) et un
// "level" en pourcentage (0-100) utilisé pour dessiner la barre XP en CSS.
// NB : les niveaux sont des estimations, à ajuster selon le ressenti réel.
// -----------------------------------------------------------------------
$skillCategories = [
    'Front-end' => [
        ['name' => 'HTML5 / CSS3', 'icon' => '🧱', 'level' => 85],
        ['name' => 'JavaScript', 'icon' => '⚡', 'level' => 75],
        ['name' => 'UX / UI · Figma', 'icon' => '🎨', 'level' => 65],
        ['name' => 'Nunjucks', 'icon' => '📄', 'level' => 60],
    ],
    'Back-end' => [
        ['name' => 'PHP (MVC)', 'icon' => '🐘', 'level' => 75],
        ['name' => 'Node.js / Express', 'icon' => '🟢', 'level' => 65],
        ['name' => 'MySQL / MariaDB', 'icon' => '🗄️', 'level' => 70],
        ['name' => 'Python', 'icon' => '🐍', 'level' => 55],
    ],
    'Système & Réseau' => [
        ['name' => 'Linux (Debian/Ubuntu)', 'icon' => '🐧', 'level' => 70],
        ['name' => 'Windows Server / AD', 'icon' => '🖥️', 'level' => 65],
        ['name' => 'VLAN / DHCP / DNS / TCP-IP', 'icon' => '🌐', 'level' => 65],
        ['name' => 'Cisco Packet Tracer / WLC', 'icon' => '📡', 'level' => 60],
        ['name' => 'Wireshark', 'icon' => '🦈', 'level' => 60],
        ['name' => 'Bash / PowerShell / Cron', 'icon' => '⌨️', 'level' => 65],
    ],
    'Outils' => [
        ['name' => 'Git / GitHub / GitLab', 'icon' => '🔧', 'level' => 75],
        ['name' => 'VMware / VirtualBox', 'icon' => '💻', 'level' => 65],
        ['name' => 'Trello / Gantt', 'icon' => '📋', 'level' => 70],
    ],
];

// -----------------------------------------------------------------------
// 3) PROJETS
// Chaque projet a un "slug" (identifiant sans espace) qui sert de nom de
// dossier dans assets/img/projects/<slug>/ : c'est là que les captures
// d'écran du projet doivent être déposées pour apparaître dans la galerie.
// "category" vaut 'dev' (développement) ou 'sys' (système/réseau) et sert
// à ranger le projet dans la bonne grille plus bas dans la page.
// -----------------------------------------------------------------------
$projects = [
    [
        'slug' => 'portfolio-personnel',
        'category' => 'dev',
        'title' => 'Portfolio personnel',
        'meta' => 'Projet individuel · 2026',
        'description' => "Site vitrine développé en PHP, HTML, CSS et JavaScript, avec un formulaire de contact qui enregistre les messages côté serveur.",
        'tags' => ['PHP', 'HTML', 'CSS', 'JS'],
        'code' => '#',
    ],
    [
        'slug' => 'nutricare',
        'category' => 'dev',
        'title' => 'NutriCare+ — Suivi diabétique & nutritionnel',
        'meta' => 'Chef de projet · Groupe de 5 · 03/2026 → en cours',
        'description' => "Application web de suivi médical personnalisé du diabète. Architecture MVC PHP/MySQL avec front controller unique et pattern PRG, conception du MLD, gestion des sessions et protection CSRF/IDOR. Pilotage d'équipe : 39+ issues GitLab, roadmap, sprints Trello/Gantt. Déployée sur VM Debian LAMP, maquette Figma intégrée en HTML/CSS responsive.",
        'tags' => ['PHP', 'MySQL', 'MVC', 'HTML/CSS', 'JavaScript', 'Debian LAMP', 'Figma'],
        'code' => '#',
    ],
    [
        'slug' => 'escape-paws',
        'category' => 'dev',
        'title' => 'Escape-Paws — Escape Game Web',
        'meta' => 'Chef de projet · Binôme · 10/2025 → 01/2026',
        'description' => "Jeu web de type escape game avec serveur Node.js/Express (routing, middlewares) et interface responsive intégrant des mini-jeux interactifs en JavaScript. Architecture front/back dissociée avec intégration d'API et moteur de templating Nunjucks.",
        'tags' => ['Node.js', 'Express', 'JavaScript', 'HTML/CSS', 'Nunjucks'],
        'code' => '#',
    ],
    [
        'slug' => 'tp-cisco',
        'category' => 'sys',
        'title' => 'TP — Infrastructure réseau Cisco',
        'meta' => 'Projet individuel · ESIEA · 2025',
        'description' => "Conception d'un réseau classe B sur Cisco Packet Tracer : configuration des VLANs, trunks inter-switch, DHCP, DNS, intégration d'un WLC (Wireless LAN Controller) et de ses Access Points, diagnostic de pannes (IP, VLAN, gateway, ports en shutdown).",
        'tags' => ['Cisco Packet Tracer', 'VLAN', 'DHCP', 'DNS', 'WLC'],
        'code' => '#',
    ],
    [
        'slug' => 'tp-windows-server',
        'category' => 'sys',
        'title' => 'TP — Administration Windows Server',
        'meta' => 'Projet individuel · ESIEA · 2026',
        'description' => "Déploiement d'une infrastructure virtualisée (Windows Server + Client), configuration Active Directory (domaine, OUs, groupes, utilisateurs), gestion des permissions NTFS différenciées, mise en place de partages réseau et lecteurs mappés (Z:, Y:).",
        'tags' => ['Windows Server 2022', 'Active Directory', 'NTFS', 'VMware'],
        'code' => '#',
    ],
    [
        'slug' => 'tp-wireshark',
        'category' => 'sys',
        'title' => 'TP — Analyse réseau Wireshark',
        'meta' => 'Projet individuel · ESIEA · 2026',
        'description' => "Capture et analyse de trames Ethernet et datagrammes IP, identification des protocoles TCP/UDP/ICMP et correspondance avec le modèle OSI/TCP-IP, mise en réseau de VMs (Host-only / NAT) avec filtrage de trafic ciblé.",
        'tags' => ['Wireshark', 'TCP/IP', 'OSI', 'VMware'],
        'code' => '#',
    ],
    [
        'slug' => 'tp-bash-cron',
        'category' => 'sys',
        'title' => 'TP — Automatisation Linux (Bash & Cron)',
        'meta' => 'Binôme · ESIEA · 2026',
        'description' => "Scripts Bash interactifs (création d'utilisateurs, nettoyage de /tmp/), planification de tâches via cron (mardis et samedis à 12h00 et 14h00), gestion des droits et automatisation de la maintenance système.",
        'tags' => ['Bash', 'Cron', 'Linux'],
        'code' => '#',
    ],
];

// -----------------------------------------------------------------------
// 4) SCAN AUTOMATIQUE DES IMAGES DE CHAQUE PROJET
// Pour chaque projet, on regarde si un dossier assets/img/projects/<slug>/
// existe et on liste toutes les images qu'il contient (extensions autorisées
// ci-dessous). Le résultat est stocké dans $project['images'] et sera
// réutilisé plus bas pour la galerie "lightbox" en JavaScript.
// Avantage : il suffit de déposer des fichiers dans le bon dossier, aucune
// modification de code n'est nécessaire pour qu'ils apparaissent sur le site.
// -----------------------------------------------------------------------
$imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

// Le "&" devant $project permet de MODIFIER directement chaque élément du
// tableau $projects pendant qu'on le parcourt (passage par référence).
foreach ($projects as &$project) {
    $imagesDir = __DIR__ . '/assets/img/projects/' . $project['slug'];
    $images = [];

    if (is_dir($imagesDir)) {
        $files = scandir($imagesDir); // Liste tous les fichiers du dossier
        sort($files); // Tri alphabétique pour un ordre d'affichage stable
        foreach ($files as $file) {
            // pathinfo(...) récupère l'extension du fichier (après le dernier point)
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $imageExtensions, true)) {
                // Chemin relatif utilisable directement dans une balise <img src="...">
                $images[] = 'assets/img/projects/' . $project['slug'] . '/' . $file;
            }
        }
    }

    $project['images'] = $images; // On ajoute la liste d'images au projet
}
unset($project); // Bonne pratique : on "casse" la référence après la boucle foreach

// Récupère le paramètre ?status=success|error dans l'URL (utilisé après
// la soumission du formulaire de contact, voir contact.php).
$status = $_GET['status'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sara Agbehonou — Portfolio</title>
    <!-- Police pixel "Press Start 2P" chargée depuis Google Fonts pour le thème rétro/Minecraft -->
    <!-- + 2 polices "graffiti" pour les gros titres (Rubik Spray Paint = effet peinture au
         pochoir, Permanent Marker = effet feutre épais façon tag) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Press+Start+2P&family=Rubik+Spray+Paint&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- ============================== NAVIGATION ============================== -->
    <!-- Barre de navigation fixée en haut de page (sticky), façon "hotbar" Minecraft -->
    <header class="navbar">
        <div class="container">
            <span class="logo">SARA.AGBEHONOU</span>
            <!-- Bouton "hamburger" affiché uniquement en mobile (voir CSS + script.js) -->
            <button class="nav-toggle" aria-label="Ouvrir le menu">☰</button>
            <nav class="nav-links">
                <a href="#accueil">Accueil</a>
                <a href="#a-propos">À propos</a>
                <a href="#formation">Formation</a>
                <a href="#competences">Compétences</a>
                <a href="#projets">Projets</a>
                <a href="#contact">Contact</a>
            </nav>
        </div>
    </header>

    <!-- ============================== HERO / ACCUEIL ============================== -->
    <section id="accueil" class="hero">
        <!-- Cubes décoratifs façon blocs Minecraft, générés en pur CSS (voir .cube dans style.css) -->
        <div class="cube-field">
            <div class="cube cube--1"><div class="face top"></div><div class="face left"></div><div class="face right"></div></div>
            <div class="cube cube--2"><div class="face top"></div><div class="face left"></div><div class="face right"></div></div>
            <div class="cube cube--3"><div class="face top"></div><div class="face left"></div><div class="face right"></div></div>
            <div class="cube cube--4"><div class="face top"></div><div class="face left"></div><div class="face right"></div></div>
        </div>
        <div class="container">
            <h1 class="hero-title">SARA AGBEHONOU</h1>
            <p class="hero-subtitle">Ingénieure informatique en formation à l'ESIEA — Développement Web &amp; Systèmes/Réseau</p>
            <p class="hero-subtitle hero-subtitle--small">🎓 À la recherche d'une alternance de 12 mois</p>
            <!-- Boutons d'action principaux : ancres vers d'autres sections de la même page -->
            <div class="hero-actions">
                <a href="#projets" class="btn btn-primary">Voir mes projets</a>
                <a href="#contact" class="btn">Me contacter</a>
            </div>
            <!-- Téléchargement direct des CV en PDF (attribut "download" = pas d'ouverture dans un nouvel onglet) -->
            <div class="hero-actions hero-actions--cv">
                <a href="assets/cv/CV_Sara_Agbehonou_DevWeb.pdf" class="btn btn-cv" download>⬇ CV Développement Web</a>
                <a href="assets/cv/CV_Sara_Agbehonou_SysReseau.pdf" class="btn btn-cv" download>⬇ CV Système &amp; Réseau</a>
            </div>
        </div>
    </section>

    <!-- ============================== À PROPOS ============================== -->
    <section id="a-propos">
        <div class="container">
            <h2 class="section-title">À propos</h2>
            <div class="pixel-panel about-grid reveal">
                <div class="about-text">
                    <p>
                        Je suis étudiante ingénieure à l'ESIEA, en parcours Expert double compétence
                        <strong> Systèmes &amp; Réseau</strong> et <strong>Développement Web</strong>.
                        J'aime autant configurer une infrastructure réseau ou un serveur que construire
                        une application web de bout en bout.
                    </p>
                    <p>
                        Actuellement cheffe de projet sur <strong>NutriCare+</strong>, une application de
                        suivi médical développée en équipe de 5, je pilote aussi bien la technique
                        (architecture MVC, base de données, sécurité) que l'organisation (roadmap,
                        répartition des tâches, sprints).
                    </p>
                    <p>
                        Rigoureuse, autonome et à l'aise en équipe, je suis actuellement à la recherche
                        d'une <strong>alternance de 12 mois</strong> pour continuer à monter en
                        compétences sur le terrain.
                    </p>
                    <!-- Liste de "soft skills" affichés comme des étiquettes (même style que les tags de projets) -->
                    <div class="about-tags">
                        <span class="tag">Rigoureuse &amp; autonome</span>
                        <span class="tag">Esprit d'analyse</span>
                        <span class="tag">Proactive &amp; adaptable</span>
                        <span class="tag">Travail en équipe</span>
                        <span class="tag">Communication claire</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================== FORMATION ============================== -->
    <!-- Frise chronologique statique (pas de boucle PHP ici, contenu écrit en dur) -->
    <section id="formation">
        <div class="container">
            <h2 class="section-title">Formation</h2>
            <div class="pixel-panel reveal">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-head">
                            <strong>ESIEA — Ivry-sur-Seine</strong>
                            <span class="timeline-period">2025 → en cours</span>
                        </div>
                        <p>Ingénieur informatique — Parcours Expert : Systèmes &amp; Réseau / Développement Web</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-head">
                            <strong>Université d'Orléans</strong>
                            <span class="timeline-period">09/2024 → 06/2025</span>
                        </div>
                        <p>L1 Mathématiques – Informatique</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-head">
                            <strong>Baccalauréat</strong>
                            <span class="timeline-period"></span>
                        </div>
                        <p>Spécialités NSI / SES</p>                   </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================== COMPÉTENCES ============================== -->
    <section id="competences">
        <div class="container">
            <h2 class="section-title">Compétences</h2>
            <?php // Première boucle : on parcourt chaque CATÉGORIE (Front-end, Back-end, ...) ?>
            <?php foreach ($skillCategories as $category => $skills): ?>
                <div class="pixel-panel reveal skills-category">
                    <h3 class="skills-category-title"><?= htmlspecialchars($category) ?></h3>
                    <div class="skills-grid">
                        <?php // Deuxième boucle (imbriquée) : chaque compétence DANS la catégorie ?>
                        <?php foreach ($skills as $skill): ?>
                            <div class="skill-slot">
                                <div class="skill-head">
                                    <span class="skill-icon"><?= htmlspecialchars($skill['icon']) ?></span>
                                    <span><?= htmlspecialchars($skill['name']) ?></span>
                                </div>
                                <!-- Barre "XP" façon Minecraft : la largeur en % vient directement du tableau PHP -->
                                <div class="xp-bar">
                                    <div class="xp-bar-fill" style="width: <?= (int) $skill['level'] ?>%;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ============================== PROJETS ============================== -->
    <section id="projets">
        <div class="container">
            <h2 class="section-title">Projets</h2>

            <!-- --- Sous-section "Développement" --- -->
            <h3 class="projects-subtitle">💻 Développement</h3>
            <div class="projects-grid">
                <?php foreach ($projects as $index => $project): ?>
                    <?php // On saute (continue) les projets qui ne sont pas de catégorie "dev" ?>
                    <?php if ($project['category'] !== 'dev') continue; ?>
                    <div class="pixel-panel project-card reveal">
                        <h3><?= htmlspecialchars($project['title']) ?></h3>
                        <p class="project-meta"><?= htmlspecialchars($project['meta']) ?></p>
                        <p><?= htmlspecialchars($project['description']) ?></p>
                        <div class="project-tags">
                            <?php foreach ($project['tags'] as $tag): ?>
                                <span class="tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="project-links">
                            <!-- data-project-index correspond à l'index dans $projects : il permet au
                                 script.js de retrouver les bonnes images à afficher dans la lightbox -->
                            <button type="button" class="btn-demo" data-project-index="<?= (int) $index ?>">Démo</button>
                            <a href="<?= htmlspecialchars($project['code']) ?>">Code</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- --- Sous-section "Systèmes & Réseau" --- -->
            <h3 class="projects-subtitle">🖧 Systèmes &amp; Réseau</h3>
            <div class="projects-grid">
                <?php foreach ($projects as $index => $project): ?>
                    <?php // Ici on garde uniquement les projets de catégorie "sys" ?>
                    <?php if ($project['category'] !== 'sys') continue; ?>
                    <div class="pixel-panel project-card reveal">
                        <h3><?= htmlspecialchars($project['title']) ?></h3>
                        <p class="project-meta"><?= htmlspecialchars($project['meta']) ?></p>
                        <p><?= htmlspecialchars($project['description']) ?></p>
                        <div class="project-tags">
                            <?php foreach ($project['tags'] as $tag): ?>
                                <span class="tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="project-links">
                            <button type="button" class="btn-demo" data-project-index="<?= (int) $index ?>">Démo</button>
                            <a href="<?= htmlspecialchars($project['code']) ?>">Code</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================== LIGHTBOX (galerie plein écran) ==============================
         Fenêtre modale cachée par défaut (attribut "hidden"). Le bouton "Démo" d'un projet
         l'ouvre via script.js et y affiche les images correspondantes une par une. -->
    <div id="lightbox" class="lightbox" hidden>
        <div class="lightbox-panel pixel-panel">
            <div class="lightbox-header">
                <span id="lightbox-title"></span>
                <button type="button" class="lightbox-close" aria-label="Fermer">✕</button>
            </div>
            <div class="lightbox-body">
                <button type="button" class="lightbox-nav lightbox-prev" aria-label="Image précédente">◀</button>
                <img id="lightbox-image" src="" alt="">
                <!-- Message affiché si le projet n'a pas encore d'images dans son dossier -->
                <p id="lightbox-empty" class="lightbox-empty" hidden>Images à venir pour ce projet.</p>
                <button type="button" class="lightbox-nav lightbox-next" aria-label="Image suivante">▶</button>
            </div>
            <p id="lightbox-counter" class="lightbox-counter"></p>
        </div>
    </div>

    <!-- Bloc de données JSON invisible (type="application/json" = jamais exécuté ni affiché).
         PHP y écrit le titre + la liste d'images de CHAQUE projet ; script.js lit ensuite ce
         JSON avec JSON.parse() pour savoir quoi afficher dans la lightbox. C'est le "pont"
         entre les données PHP (côté serveur) et le JavaScript (côté navigateur). -->
    <script id="projects-data" type="application/json"><?= json_encode(array_map(
        fn($p) => ['title' => $p['title'], 'images' => $p['images']],
        $projects
    ), JSON_UNESCAPED_SLASHES) ?></script>

    <!-- ============================== CONTACT ============================== -->
    <section id="contact">
        <div class="container">
            <h2 class="section-title">Contact</h2>

            <?php // Message de confirmation ou d'erreur après soumission du formulaire (voir contact.php) ?>
            <?php if ($status === 'success'): ?>
                <div class="flash-message success">Message envoyé avec succès, merci !</div>
            <?php elseif ($status === 'error'): ?>
                <div class="flash-message error">Merci de remplir correctement tous les champs.</div>
            <?php endif; ?>

            <div class="contact-grid">
                <!-- Bloc coordonnées : affiché à gauche (voir .contact-grid en CSS) -->
                <div class="pixel-panel contact-info reveal">
                    <h3>Coordonnées</h3>
                    <ul class="contact-list">
                        <li>📍 <?= htmlspecialchars($profile['location']) ?></li>
                        <!-- str_replace supprime les espaces du numéro pour que le lien tel: fonctionne -->
                        <li>📞 <a href="tel:<?= htmlspecialchars(str_replace(' ', '', $profile['phone'])) ?>"><?= htmlspecialchars($profile['phone']) ?></a></li>
                        <li>✉ <a href="mailto:<?= htmlspecialchars($profile['email']) ?>"><?= htmlspecialchars($profile['email']) ?></a></li>
                        <li>🔗 <a href="https://<?= htmlspecialchars($profile['linkedin']) ?>" target="_blank" rel="noopener">LinkedIn</a></li>
                    </ul>
                </div>

                <!-- Formulaire de contact : envoyé en POST vers contact.php qui valide et
                     enregistre le message dans data/messages.json (voir ce fichier) -->
                <div class="pixel-panel contact-panel reveal">
                    <form id="contact-form" action="contact.php" method="POST" novalidate>
                        <div class="form-row">
                            <label for="name">Nom</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-row">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-row">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <!-- date('Y') affiche automatiquement l'année en cours, pas besoin de la changer chaque année -->
        <p>&copy; <?= date('Y') ?> Sara Agbehonou — Fait avec PHP, HTML, CSS &amp; JS</p>
    </footer>

    <!-- Chargé en fin de body pour que le HTML soit déjà présent quand le JS s'exécute -->
    <script src="js/script.js"></script>
</body>
</html>
