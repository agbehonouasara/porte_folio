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
    'phone' => '+33 7 58 31 88 59',
    'email' => 'agbehonouaesther@gmail.com',
    'linkedin' => 'linkedin.com/in/sara-agbehonou',
];

// -----------------------------------------------------------------------
// 2) COMPÉTENCES
// Tableau associatif : la clé = nom de la catégorie (Front-end, Back-end...),
// la valeur = liste de compétences avec un nom, un "slug" et un "level" en
// pourcentage (0-100) affiché comme une petite jauge arrondie sous le nom
// (voir .skill-gauge en CSS). Le "slug" sert de nom de fichier logo dans
// assets/img/logos/<slug>.(svg|png|webp) : voir la boucle de scan plus bas,
// qui affiche ce logo s'il existe, ou de simples initiales sinon (jamais
// d'emoji). NB : les niveaux sont des estimations, à ajuster selon le
// ressenti réel.
// -----------------------------------------------------------------------
$skillCategories = [
    'Front-end' => [
        ['name' => 'HTML5 / CSS3', 'slug' => 'html-css', 'level' => 85],
        ['name' => 'JavaScript', 'slug' => 'javascript', 'level' => 75],
        ['name' => 'UX / UI · Figma', 'slug' => 'figma', 'level' => 65],
        ['name' => 'Nunjucks', 'slug' => 'nunjucks', 'level' => 60],
    ],
    'Back-end' => [
        ['name' => 'PHP (MVC)', 'slug' => 'php', 'level' => 75],
        ['name' => 'Node.js / Express', 'slug' => 'nodejs', 'level' => 65],
        ['name' => 'MySQL / MariaDB', 'slug' => 'mysql', 'level' => 70],
        ['name' => 'Python', 'slug' => 'python', 'level' => 55],
    ],
    'Système & Réseau' => [
        ['name' => 'Linux (Debian/Ubuntu)', 'slug' => 'linux', 'level' => 70],
        ['name' => 'Windows Server / AD', 'slug' => 'windows-server', 'level' => 65],
        ['name' => 'VLAN / DHCP / DNS / TCP-IP', 'slug' => 'network', 'level' => 65],
        ['name' => 'Cisco Packet Tracer / WLC', 'slug' => 'cisco', 'level' => 60],
        ['name' => 'Wireshark', 'slug' => 'wireshark', 'level' => 60],
        ['name' => 'Bash / PowerShell / Cron', 'slug' => 'bash', 'level' => 65],
    ],
    'Outils' => [
        ['name' => 'Git / GitHub / GitLab', 'slug' => 'git', 'level' => 75],
        ['name' => 'VMware / VirtualBox', 'slug' => 'vmware', 'level' => 65],
        ['name' => 'Trello / Gantt', 'slug' => 'trello', 'level' => 70],
    ],
];

$logoExtensions = ['svg', 'png', 'webp'];

// Pour chaque compétence : cherche un logo dans assets/img/logos/<slug>.*
// et calcule des initiales de repli (1res lettres des mots significatifs
// du nom) si aucun logo n'est encore fourni. Même principe que le scan des
// images de projets plus bas : il suffit de déposer un fichier logo au bon
// endroit pour qu'il apparaisse automatiquement, sans toucher au code.
foreach ($skillCategories as &$skills) {
    foreach ($skills as &$skill) {
        $skill['logo'] = null;

        foreach ($logoExtensions as $ext) {
            $logoPath = __DIR__ . '/assets/img/logos/' . $skill['slug'] . '.' . $ext;
            if (file_exists($logoPath)) {
                $skill['logo'] = 'assets/img/logos/' . $skill['slug'] . '.' . $ext;
                break;
            }
        }

        // Initiales de repli : garde uniquement les mots de plus de 2 lettres
        // (ignore "de", "à"...) et prend leur première lettre, sur 2 max
        // (ex: "Windows Server / AD" -> "WS", "Python" -> "P")
        $words = preg_split('/[\s\/·()]+/', $skill['name'], -1, PREG_SPLIT_NO_EMPTY);
        $letters = '';
        foreach ($words as $word) {
            if (mb_strlen($word) > 2) {
                $letters .= mb_strtoupper(mb_substr($word, 0, 1));
            }
            if (mb_strlen($letters) >= 2) {
                break;
            }
        }
        $skill['initials'] = $letters !== '' ? $letters : mb_strtoupper(mb_substr($skill['name'], 0, 2));
    }
}
unset($skills, $skill);

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
        'meta' => 'Chef de projet · Groupe de 5 · 03/2026 → Terminé',
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
    ],
    [
        'slug' => 'tp-windows-server',
        'category' => 'sys',
        'title' => 'TP — Administration Windows Server',
        'meta' => 'Projet individuel · ESIEA · 2026',
        'description' => "Déploiement d'une infrastructure virtualisée (Windows Server + Client), configuration Active Directory (domaine, OUs, groupes, utilisateurs), gestion des permissions NTFS différenciées, mise en place de partages réseau et lecteurs mappés (Z:, Y:).",
        'tags' => ['Windows Server 2022', 'Active Directory', 'NTFS', 'VMware'],
    ],
    [
        'slug' => 'tp-wireshark',
        'category' => 'sys',
        'title' => 'TP — Analyse réseau Wireshark',
        'meta' => 'Projet individuel · ESIEA · 2026',
        'description' => "Capture et analyse de trames Ethernet et datagrammes IP, identification des protocoles TCP/UDP/ICMP et correspondance avec le modèle OSI/TCP-IP, mise en réseau de VMs (Host-only / NAT) avec filtrage de trafic ciblé.",
        'tags' => ['Wireshark', 'TCP/IP', 'OSI', 'VMware'],
    ],
    [
        'slug' => 'tp-bash-cron',
        'category' => 'sys',
        'title' => 'TP — Automatisation Linux (Bash & Cron)',
        'meta' => 'Binôme · ESIEA · 2026',
        'description' => "Scripts Bash interactifs (création d'utilisateurs, nettoyage de /tmp/), planification de tâches via cron (mardis et samedis à 12h00 et 14h00), gestion des droits et automatisation de la maintenance système.",
        'tags' => ['Bash', 'Cron', 'Linux'],
    ],
];

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
    <!-- Applique tout de suite les préférences d'accessibilité déjà enregistrées
         (texte agrandi, police dyslexie, contraste élevé, animations réduites) en
         ajoutant les classes correspondantes sur <html> AVANT que la page ne
         s'affiche. Placé ici (tout en haut, en synchrone) plutôt que dans
         script.js pour éviter un "flash" au chargement où la page s'afficherait
         d'abord en réglages par défaut avant de sauter aux réglages sauvegardés.
         Le reste de la logique du widget (boutons, sauvegarde) est dans script.js. -->
    <script>
        (function () {
            try {
                var prefs = JSON.parse(localStorage.getItem('a11yPrefs') || '{}');
                var html = document.documentElement;
                if (prefs.textSize === 1) html.classList.add('a11y-text-lg');
                if (prefs.textSize === 2) html.classList.add('a11y-text-xl');
                if (prefs.lightMode) html.classList.add('a11y-light');
                if (prefs.dyslexic) html.classList.add('a11y-dyslexic');
                if (prefs.contrast) html.classList.add('a11y-contrast');
                if (prefs.reduceMotion) html.classList.add('a11y-reduce-motion');
            } catch (e) {
                // localStorage indisponible (navigation privée très restrictive, etc.) :
                // on ignore simplement, la page reste utilisable avec les réglages par défaut
            }
        })();
    </script>
    <!-- 3 polices Google Fonts pour le thème "comic / street-art" :
         Bangers = gros titres façon bande dessinée, Space Mono = étiquettes
         techniques (dates, méta-infos) façon plan/schéma, Inter = texte courant -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bangers&family=Space+Mono:wght@400;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- ============================== ACCESSIBILITÉ : LIEN D'ÉVITEMENT ==============================
         Premier élément focusable de la page : invisible tant qu'il n'a pas le focus clavier,
         il permet à une personne qui navigue au clavier ou avec un lecteur d'écran de sauter
         directement au contenu principal sans devoir retraverser tout le menu à chaque page. -->
    <a href="#main-content" class="skip-link">Aller au contenu principal</a>

    <!-- ============================== WIDGET D'ACCESSIBILITÉ ==============================
         Bouton flottant toujours visible (bas droite) qui ouvre un panneau de réglages :
         taille du texte, police adaptée à la dyslexie, contraste élevé, animations réduites.
         Le bouton affiche "Aa" (texte, pas d'emoji/pictogramme) : convention courante des
         widgets d'accessibilité, et ça reste lisible/recolorable dans tous les modes (y
         compris le contraste élevé, contrairement à un emoji qui garde ses propres couleurs).
         Toute la logique (ouverture/fermeture, application des réglages, sauvegarde) est
         dans script.js ; voir aussi le petit script en haut du <head> qui réapplique les
         réglages déjà enregistrés dès le chargement de la page. -->
    <div class="a11y-widget">
        <button type="button" class="a11y-toggle" id="a11y-toggle" aria-expanded="false" aria-controls="a11y-panel" aria-label="Ouvrir les options d'accessibilité">
            Aa
        </button>
        <div class="a11y-panel" id="a11y-panel" hidden>
            <div class="a11y-panel-header">
                <h2>Accessibilité</h2>
                <button type="button" class="a11y-close" id="a11y-close" aria-label="Fermer les options d'accessibilité">✕</button>
            </div>

            <p class="a11y-group-label" id="a11y-text-size-label">Taille du texte</p>
            <!-- role="group" + aria-labelledby : indique aux lecteurs d'écran que ces 3
                 boutons forment un ensemble lié au libellé "Taille du texte" juste au-dessus -->
            <div class="a11y-btn-row" role="group" aria-labelledby="a11y-text-size-label">
                <button type="button" class="a11y-btn" id="a11y-text-smaller" aria-label="Réduire la taille du texte">A−</button>
                <button type="button" class="a11y-btn" id="a11y-text-reset" aria-label="Taille de texte par défaut">A</button>
                <button type="button" class="a11y-btn" id="a11y-text-larger" aria-label="Augmenter la taille du texte">A+</button>
            </div>

            <!-- aria-pressed reflète l'état actif/inactif de chaque bascule, mis à jour par
                 script.js : un lecteur d'écran annonce alors "activé"/"désactivé" -->
            <button type="button" class="a11y-toggle-btn" id="a11y-light-toggle" aria-pressed="false">
                Mode clair
            </button>
            <button type="button" class="a11y-toggle-btn" id="a11y-dyslexic-toggle" aria-pressed="false">
                Police adaptée dyslexie
            </button>
            <button type="button" class="a11y-toggle-btn" id="a11y-contrast-toggle" aria-pressed="false">
                Contraste élevé
            </button>
            <button type="button" class="a11y-toggle-btn" id="a11y-motion-toggle" aria-pressed="false">
                Réduire les animations
            </button>

            <button type="button" class="a11y-reset-btn" id="a11y-reset">Réinitialiser les réglages</button>
        </div>
    </div>

    <!-- ============================== NAVIGATION ============================== -->
    <!-- Barre de navigation en pilule flottante, fixée en haut de page (sticky) -->
    <header class="navbar">
        <div class="container">
            <span class="logo">SARA.AGBEHONOU</span>
            <!-- Bouton "hamburger" affiché uniquement en mobile (voir CSS + script.js).
                 aria-expanded est mis à jour par script.js à chaque ouverture/fermeture pour
                 informer les lecteurs d'écran de l'état actuel du menu. -->
            <button class="nav-toggle" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="nav-links">☰</button>
            <nav class="nav-links" id="nav-links" aria-label="Navigation principale">
                <a href="#accueil">Accueil</a>
                <a href="#a-propos">À propos</a>
                <a href="#formation">Formation</a>
                <a href="#competences">Compétences</a>
                <a href="#projets">Projets</a>
                <a href="#contact">Contact</a>
            </nav>
        </div>
    </header>

    <!-- id="main-content" = cible du lien d'évitement ci-dessus. tabindex="-1" permet de lui
         donner le focus par script (clic sur le lien) sans l'ajouter à la navigation au Tab. -->
    <main id="main-content" tabindex="-1">

    <!-- ============================== HERO / ACCUEIL ============================== -->
    <section id="accueil" class="hero">
        <!-- Traits façon toile d'araignée décoratifs, en pur CSS (voir .web-field
             dans style.css) : remplacent les formes organiques de l'ancien thème
             pour évoquer l'esprit "comic/super-héros" sans image réelle.
             aria-hidden="true" : ces traits sont purement visuels. -->
        <div class="web-field" aria-hidden="true">
            <div class="web-line web-line--1"></div>
            <div class="web-line web-line--2"></div>
            <div class="web-line web-line--3"></div>
            <div class="web-line web-line--4"></div>
        </div>
        <div class="container">
            <p class="hero-eyebrow">Sara Agbehonou · Ingénieure informatique</p>
            <h1 class="hero-title">PORTFOLIO</h1>
            <span class="hero-year">2026</span>
            <p class="hero-subtitle">Développement Web &amp; Systèmes/Réseau — parcours Expert à l'ESIEA</p>
            <p class="hero-subtitle hero-subtitle--small">À la recherche d'une alternance de 12 mois</p>
            <!-- Boutons d'action principaux : ancres vers d'autres sections de la même page -->
            <div class="hero-actions">
                <a href="#projets" class="btn btn-primary">Voir mes projets</a>
                <a href="#contact" class="btn">Me contacter</a>
            </div>
            <!-- Téléchargement direct des CV en PDF (attribut "download" = pas d'ouverture dans un nouvel onglet) -->
            <div class="hero-actions hero-actions--cv">
                <a href="assets/cv/CV_Sara_Agbehonou_DevWeb.pdf" class="btn btn-cv" download>
                    <svg class="btn-icon" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M12 3v12m0 0l-5-5m5 5l5-5M4 20h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    CV Développement Web
                </a>
                <a href="assets/cv/CV_Sara_Agbehonou_SysReseau.pdf" class="btn btn-cv" download>
                    <svg class="btn-icon" viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M12 3v12m0 0l-5-5m5 5l5-5M4 20h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    CV Système &amp; Réseau
                </a>
            </div>
        </div>
    </section>

    <!-- ============================== À PROPOS ============================== -->
    <section id="a-propos">
        <div class="container">
            <h2 class="section-title">À propos</h2>
            <div class="card about-grid reveal">
                <div class="about-text">
                    <p class="about-greeting">Salut, moi c'est Sara !</p>
                    <p>
                        Je suis étudiante ingénieure à l'ESIEA, en parcours Expert double compétence
                        <strong> Systèmes &amp; Réseau</strong> et <strong>Développement Web</strong>.
                        J'aime autant configurer une infrastructure réseau ou un serveur que construire
                        une application web de bout en bout.
                    </p>
                    <p>
                        Récemment cheffe de projet sur <strong>NutriCare+</strong>, une application de
                        suivi médical développée et livrée en équipe de 5, j'y ai piloté aussi bien la
                        technique (architecture MVC, base de données, sécurité) que l'organisation
                        (roadmap, répartition des tâches, sprints).
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

                    <!-- Centres d'intérêt : étiquettes claires (fond blanc, bordure fine)
                         plutôt que les pilules noires réutilisées ailleurs, pour ce bloc précis -->
                    <p class="interests-title">Centres d'intérêt</p>
                    <div class="interest-tags">
                        <span class="interest-tag">Art séquentiel</span>
                        <span class="interest-tag">Cinéma (Marvel)</span>
                        <span class="interest-tag">Tir à l'arc</span>
                        <span class="interest-tag">Basket</span>
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
            <div class="card reveal">
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
                <div class="card reveal skills-category">
                    <h3 class="skills-category-title"><?= htmlspecialchars($category) ?></h3>
                    <div class="skills-grid">
                        <?php // Deuxième boucle (imbriquée) : chaque compétence DANS la catégorie ?>
                        <?php foreach ($skills as $skill): ?>
                            <!-- Badge carré coloré : affiche le logo déposé dans assets/img/logos/
                                 s'il existe (voir le scan en haut du fichier), sinon les initiales
                                 de la compétence en repli (jamais d'emoji). La couleur de fond du
                                 badge de repli change automatiquement selon la position grâce à
                                 :nth-child. + petite jauge de niveau arrondie sous le nom. -->
                            <div class="skill-slot">
                                <?php if ($skill['logo']): ?>
                                    <span class="skill-icon skill-icon--logo">
                                        <img src="<?= htmlspecialchars($skill['logo']) ?>" alt="" width="28" height="28">
                                    </span>
                                <?php else: ?>
                                    <span class="skill-icon" aria-hidden="true"><?= htmlspecialchars($skill['initials']) ?></span>
                                <?php endif; ?>
                                <span><?= htmlspecialchars($skill['name']) ?></span>
                                <div class="skill-gauge" role="progressbar" aria-valuenow="<?= (int) $skill['level'] ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Niveau en <?= htmlspecialchars($skill['name']) ?>">
                                    <div class="skill-gauge-fill" style="width: <?= (int) $skill['level'] ?>%;"></div>
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
            <h3 class="projects-subtitle">
                <svg class="projects-subtitle-icon" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path d="M8 6L2 12l6 6M16 6l6 6-6 6M14 4l-4 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Développement
            </h3>
            <div class="projects-grid">
                <?php foreach ($projects as $index => $project): ?>
                    <?php // On saute (continue) les projets qui ne sont pas de catégorie "dev" ?>
                    <?php if ($project['category'] !== 'dev') continue; ?>
                    <div class="card project-card reveal">
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
            <h3 class="projects-subtitle">
                <svg class="projects-subtitle-icon" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><rect x="3" y="4" width="18" height="5" rx="1" fill="none" stroke="currentColor" stroke-width="2"/><rect x="3" y="15" width="18" height="5" rx="1" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="7" cy="6.5" r="0.8" fill="currentColor"/><circle cx="7" cy="17.5" r="0.8" fill="currentColor"/></svg>
                Systèmes &amp; Réseau
            </h3>
            <div class="projects-grid">
                <?php foreach ($projects as $index => $project): ?>
                    <?php // Ici on garde uniquement les projets de catégorie "sys" ?>
                    <?php if ($project['category'] !== 'sys') continue; ?>
                    <div class="card project-card reveal">
                        <h3><?= htmlspecialchars($project['title']) ?></h3>
                        <p class="project-meta"><?= htmlspecialchars($project['meta']) ?></p>
                        <p><?= htmlspecialchars($project['description']) ?></p>
                        <div class="project-tags">
                            <?php foreach ($project['tags'] as $tag): ?>
                                <span class="tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="project-links">
                            <!-- Pas de bouton "Code" ici : ce sont des TP système/réseau (configurations
                                 Cisco, Windows Server...), pas des dépôts de code à consulter. -->
                            <button type="button" class="btn-demo" data-project-index="<?= (int) $index ?>">Démo</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================== LIGHTBOX (galerie plein écran) ==============================
         Fenêtre modale cachée par défaut (attribut "hidden"). Le bouton "Démo" d'un projet
         l'ouvre via script.js et y affiche les images correspondantes une par une.
         role="dialog" + aria-modal="true" : indique aux lecteurs d'écran qu'il s'agit d'une
         fenêtre modale qui doit capter toute l'attention (voir le piège de focus dans script.js).
         aria-labelledby pointe vers le titre du projet affiché, utilisé comme "nom" de la fenêtre. -->
    <div id="lightbox" class="lightbox" role="dialog" aria-modal="true" aria-labelledby="lightbox-title" hidden>
        <div class="lightbox-panel card">
            <div class="lightbox-header">
                <span id="lightbox-title"></span>
                <button type="button" class="lightbox-close" aria-label="Fermer">✕</button>
            </div>
            <div class="lightbox-body">
                <button type="button" class="lightbox-nav lightbox-prev" aria-label="Image précédente">◀</button>
                <!-- L'attribut alt est rempli dynamiquement par script.js (titre du projet +
                     numéro d'image) car son contenu dépend de l'image actuellement affichée -->
                <img id="lightbox-image" src="" alt="">
                <!-- Message affiché si le projet n'a pas encore d'images dans son dossier -->
                <p id="lightbox-empty" class="lightbox-empty" hidden>Images à venir pour ce projet.</p>
                <button type="button" class="lightbox-nav lightbox-next" aria-label="Image suivante">▶</button>
            </div>
            <!-- aria-live="polite" : quand le texte change (ex: "2 / 5"), les lecteurs d'écran
                 l'annoncent automatiquement sans que l'utilisateur ait à redéplacer son focus -->
            <p id="lightbox-counter" class="lightbox-counter" aria-live="polite"></p>
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

            <?php // Message de confirmation ou d'erreur après soumission du formulaire (voir contact.php).
                   // role="status"/"alert" + aria-live : un lecteur d'écran annonce automatiquement
                   // ce message dès qu'il apparaît, même si l'utilisateur n'a pas déplacé son focus dessus
                   // ("alert" est plus insistant que "status", adapté à un message d'erreur). ?>
            <?php if ($status === 'success'): ?>
                <div class="flash-message success" role="status">Message envoyé avec succès, merci !</div>
            <?php elseif ($status === 'error'): ?>
                <div class="flash-message error" role="alert">Merci de remplir correctement tous les champs.</div>
            <?php endif; ?>

            <div class="contact-grid">
                <!-- Bloc coordonnées : affiché à gauche (voir .contact-grid en CSS).
                     Les icônes sont de petits SVG en ligne (currentColor, donc recolorables
                     par le CSS, y compris en mode contraste élevé — contrairement à des
                     emojis qui gardent toujours leurs propres couleurs) et marquées
                     aria-hidden : le texte/lien qui suit porte déjà tout le sens utile. -->
                <div class="card contact-info reveal">
                    <h3>Coordonnées</h3>
                    <ul class="contact-list">
                        <!-- str_replace supprime les espaces du numéro pour que le lien tel: fonctionne -->
                        <li>
                            <svg class="contact-icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M4 4h4l2 5-2.5 1.5a11 11 0 005 5L14 13l5 2v4a2 2 0 01-2 2A16 16 0 014 6a2 2 0 012-2z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                            <a href="tel:<?= htmlspecialchars(str_replace(' ', '', $profile['phone'])) ?>"><?= htmlspecialchars($profile['phone']) ?></a>
                        </li>
                        <li>
                            <svg class="contact-icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M3 6l9 7 9-7" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
                            <a href="mailto:<?= htmlspecialchars($profile['email']) ?>"><?= htmlspecialchars($profile['email']) ?></a>
                        </li>
                        <li>
                            <svg class="contact-icon" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M9 15l6-6M10 8l1-1a3.5 3.5 0 015 5l-1 1M14 16l-1 1a3.5 3.5 0 01-5-5l1-1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            <a href="https://<?= htmlspecialchars($profile['linkedin']) ?>" target="_blank" rel="noopener">LinkedIn <span class="sr-only">(ouvre dans un nouvel onglet)</span></a>
                        </li>
                    </ul>
                </div>

                <!-- Formulaire de contact : envoyé en POST vers contact.php qui valide et
                     enregistre le message dans data/messages.json (voir ce fichier) -->
                <div class="card contact-panel reveal">
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

    </main>

    <footer>
        <!-- date('Y') affiche automatiquement l'année en cours, pas besoin de la changer chaque année -->
        <p>&copy; <?= date('Y') ?> Sara Agbehonou — Fait avec PHP, HTML, CSS &amp; JS</p>
    </footer>

    <!-- Chargé en fin de body pour que le HTML soit déjà présent quand le JS s'exécute -->
    <script src="js/script.js"></script>
</body>
</html>
