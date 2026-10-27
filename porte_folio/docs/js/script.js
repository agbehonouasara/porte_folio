// =============================================================================
// SCRIPT.JS — Interactivité du portfolio (menu mobile, lien actif au scroll,
// animations d'apparition, galerie "lightbox" des projets, validation du
// formulaire de contact).
// Tout est regroupé dans un seul écouteur DOMContentLoaded pour être sûr que
// le HTML généré par porte_fo.php est bien présent avant qu'on cherche les
// éléments avec document.querySelector.
// =============================================================================

document.addEventListener("DOMContentLoaded", () => {
  // ===========================================================================
  // 0) WIDGET D'ACCESSIBILITÉ (bouton flottant ♿ en bas à droite)
  // Panneau de réglages : taille du texte, police adaptée à la dyslexie,
  // contraste élevé, réduction des animations. Chaque réglage est appliqué en
  // ajoutant/retirant une classe sur <html> (voir les styles ".a11y-*" et
  // "html.a11y-*" dans style.css), et sauvegardé dans localStorage pour être
  // réappliqué automatiquement aux prochaines visites (voir le petit script
  // synchrone en haut du <head> de porte_fo.php, qui relit ce même localStorage
  // avant l'affichage de la page pour éviter un "flash" de réglages par défaut).
  // ===========================================================================
  const A11Y_STORAGE_KEY = "a11yPrefs";
  const html = document.documentElement;

  const a11yToggle = document.querySelector("#a11y-toggle");
  const a11yPanel = document.querySelector("#a11y-panel");
  const a11yClose = document.querySelector("#a11y-close");
  const a11yTextSmaller = document.querySelector("#a11y-text-smaller");
  const a11yTextReset = document.querySelector("#a11y-text-reset");
  const a11yTextLarger = document.querySelector("#a11y-text-larger");
  const a11yLightToggle = document.querySelector("#a11y-light-toggle");
  const a11yDyslexicToggle = document.querySelector("#a11y-dyslexic-toggle");
  const a11yContrastToggle = document.querySelector("#a11y-contrast-toggle");
  const a11yMotionToggle = document.querySelector("#a11y-motion-toggle");
  const a11yReset = document.querySelector("#a11y-reset");

  // Lit les préférences déjà enregistrées (le script du <head> les a déjà
  // appliquées visuellement ; ici on relit juste les mêmes valeurs pour que
  // les boutons du panneau affichent le bon état "activé"/"désactivé" au chargement).
  function readA11yPrefs() {
    try {
      return JSON.parse(localStorage.getItem(A11Y_STORAGE_KEY) || "{}");
    } catch (e) {
      return {};
    }
  }

  function saveA11yPrefs(prefs) {
    try {
      localStorage.setItem(A11Y_STORAGE_KEY, JSON.stringify(prefs));
    } catch (e) {
      // localStorage indisponible : le réglage reste actif pour la session en
      // cours (la classe est bien appliquée), simplement non mémorisé pour la
      // prochaine visite.
    }
  }

  if (a11yToggle && a11yPanel) {
    let prefs = readA11yPrefs();

    // Met à jour l'apparence des 2 boutons "bascule" (dyslexie/contraste) pour
    // refléter l'état actuellement enregistré dans "prefs"
    function syncToggleButton(button, isActive) {
      button.setAttribute("aria-pressed", String(Boolean(isActive)));
    }

    function applyPrefs() {
      html.classList.toggle("a11y-text-lg", prefs.textSize === 1);
      html.classList.toggle("a11y-text-xl", prefs.textSize === 2);
      html.classList.toggle("a11y-light", Boolean(prefs.lightMode));
      html.classList.toggle("a11y-dyslexic", Boolean(prefs.dyslexic));
      html.classList.toggle("a11y-contrast", Boolean(prefs.contrast));
      html.classList.toggle("a11y-reduce-motion", Boolean(prefs.reduceMotion));

      syncToggleButton(a11yLightToggle, prefs.lightMode);
      syncToggleButton(a11yDyslexicToggle, prefs.dyslexic);
      syncToggleButton(a11yContrastToggle, prefs.contrast);
      syncToggleButton(a11yMotionToggle, prefs.reduceMotion);

      saveA11yPrefs(prefs);
    }

    // Affiche l'état initial des boutons (classes déjà posées par le script du <head>)
    syncToggleButton(a11yLightToggle, prefs.lightMode);
    syncToggleButton(a11yDyslexicToggle, prefs.dyslexic);
    syncToggleButton(a11yContrastToggle, prefs.contrast);
    syncToggleButton(a11yMotionToggle, prefs.reduceMotion);

    // Ouverture/fermeture du panneau, même logique que le menu mobile
    // (classe + aria-expanded synchronisé pour les lecteurs d'écran)
    function openPanel() {
      a11yPanel.hidden = false;
      a11yToggle.setAttribute("aria-expanded", "true");
    }

    function closePanel() {
      a11yPanel.hidden = true;
      a11yToggle.setAttribute("aria-expanded", "false");
    }

    a11yToggle.addEventListener("click", () => {
      if (a11yPanel.hidden) {
        openPanel();
      } else {
        closePanel();
      }
    });

    a11yClose.addEventListener("click", () => {
      closePanel();
      a11yToggle.focus(); // Rend le focus au bouton qui a ouvert le panneau
    });

    // Ferme le panneau si on clique en dehors du widget
    document.addEventListener("click", (event) => {
      if (!a11yPanel.hidden && !event.target.closest(".a11y-widget")) {
        closePanel();
      }
    });

    // Échap ferme le panneau, comme pour la lightbox
    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && !a11yPanel.hidden) {
        closePanel();
        a11yToggle.focus();
      }
    });

    // Taille du texte : 0 = normal, 1 = grand, 2 = très grand (bornée dans cet intervalle)
    a11yTextSmaller.addEventListener("click", () => {
      prefs.textSize = Math.max(0, (prefs.textSize || 0) - 1);
      applyPrefs();
    });

    a11yTextLarger.addEventListener("click", () => {
      prefs.textSize = Math.min(2, (prefs.textSize || 0) + 1);
      applyPrefs();
    });

    a11yTextReset.addEventListener("click", () => {
      prefs.textSize = 0;
      applyPrefs();
    });

    // Bascules simples : chaque clic inverse l'état actuel (activé <-> désactivé)
    a11yLightToggle.addEventListener("click", () => {
      prefs.lightMode = !prefs.lightMode;
      applyPrefs();
    });

    a11yDyslexicToggle.addEventListener("click", () => {
      prefs.dyslexic = !prefs.dyslexic;
      applyPrefs();
    });

    a11yContrastToggle.addEventListener("click", () => {
      prefs.contrast = !prefs.contrast;
      applyPrefs();
    });

    a11yMotionToggle.addEventListener("click", () => {
      prefs.reduceMotion = !prefs.reduceMotion;
      applyPrefs();
    });

    // Réinitialise tous les réglages d'accessibilité à leur valeur par défaut
    a11yReset.addEventListener("click", () => {
      prefs = {};
      applyPrefs();
    });
  }

  // Vrai si l'utilisateur a activé "Réduire les animations" dans son système
  // OU s'il a activé la bascule équivalente dans le widget d'accessibilité
  // ci-dessus. Réutilisé plus bas pour respecter ce choix.
  const prefersReducedMotion =
    window.matchMedia("(prefers-reduced-motion: reduce)").matches ||
    html.classList.contains("a11y-reduce-motion");

  // ===========================================================================
  // 1) MENU MOBILE (bouton "hamburger" ☰)
  // Sur petit écran, la CSS cache .nav-links par défaut. Un clic sur le bouton
  // ajoute/retire la classe "open" qui rend le menu visible (voir style.css).
  // ===========================================================================
  const navToggle = document.querySelector(".nav-toggle");
  const navLinks = document.querySelector(".nav-links");

  if (navToggle && navLinks) {
    // Ouvre/ferme le menu à chaque clic sur le bouton hamburger.
    // aria-expanded reflète l'état réel du menu pour les lecteurs d'écran
    // (sans ça, un menu visuellement ouvert peut être annoncé "fermé").
    navToggle.addEventListener("click", () => {
      const isOpen = navLinks.classList.toggle("open");
      navToggle.setAttribute("aria-expanded", String(isOpen));
    });

    // Referme automatiquement le menu quand on clique sur un lien
    // (évite qu'il reste ouvert après avoir navigué vers une section)
    navLinks.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        navLinks.classList.remove("open");
        navToggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  // ===========================================================================
  // 2) LIEN DE NAVIGATION ACTIF SELON LA SECTION VISIBLE À L'ÉCRAN
  // On utilise un IntersectionObserver : l'API du navigateur qui détecte
  // automatiquement quand un élément entre/sort de la zone visible, sans avoir
  // à écouter l'évènement "scroll" nous-mêmes (plus performant).
  // ===========================================================================
  const sections = document.querySelectorAll("section[id]"); // Toutes les sections avec un id (#accueil, #a-propos, ...)
  const links = document.querySelectorAll(".nav-links a"); // Tous les liens du menu

  const observer = new IntersectionObserver(
    (entries) => {
      // "entries" contient un objet par section observée, avec un booléen
      // isIntersecting = true si la section est actuellement visible à l'écran
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          links.forEach((link) => {
            // On ajoute la classe "active" uniquement au lien qui correspond
            // à la section actuellement visible (comparaison sur le href="#id")
            link.classList.toggle(
              "active",
              link.getAttribute("href") === `#${entry.target.id}`
            );
          });
        }
      });
    },
    // rootMargin réduit la zone de détection : la section doit être proche du
    // centre de l'écran pour être considérée comme "active" (évite un
    // changement de lien actif trop précoce quand on commence juste à scroller)
    { rootMargin: "-40% 0px -55% 0px" }
  );

  sections.forEach((section) => observer.observe(section));

  // ===========================================================================
  // 3) ANIMATION D'APPARITION AU SCROLL DES BLOCS ".reveal"
  // Chaque bloc marqué "reveal" (les cartes du site) démarre invisible et
  // légèrement décalé vers le bas, puis apparaît en fondu dès qu'il devient
  // visible à l'écran pour la première fois.
  // Si l'utilisateur préfère un mouvement réduit, on saute complètement cette
  // animation : les blocs restent simplement visibles en permanence.
  // ===========================================================================
  if (!prefersReducedMotion) {
    const revealObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = "1";
            entry.target.style.transform = "translateY(0)";
            // unobserve : on arrête de surveiller cet élément une fois révélé,
            // pas besoin de répéter l'animation à chaque scroll
            revealObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 } // Se déclenche dès que 15% de l'élément est visible
    );

    document.querySelectorAll(".reveal").forEach((el) => {
      // État de départ (invisible, décalé) défini en JS pour ne pas flasher le
      // contenu si JavaScript est désactivé (dans ce cas, le CSS seul l'affiche)
      el.style.opacity = "0";
      el.style.transform = "translateY(20px)";
      el.style.transition = "opacity 0.5s ease, transform 0.5s ease";
      revealObserver.observe(el);
    });
  }

  // ===========================================================================
  // 4) LIGHTBOX — GALERIE D'IMAGES DES PROJETS
  // Quand on clique sur le bouton "Démo" d'un projet, une fenêtre modale
  // s'ouvre et affiche les captures d'écran de ce projet (une à la fois, avec
  // navigation précédent/suivant). Les données (titre + liste d'images de
  // chaque projet) sont fournies par PHP dans la balise <script id="projects-data">
  // sous forme de JSON, généré dynamiquement dans porte_fo.php.
  //
  // Accessibilité : la fenêtre a role="dialog"/aria-modal (voir porte_fo.php),
  // et ce script s'occupe en plus de trois choses indispensables pour une
  // vraie fenêtre modale accessible :
  //  - déplacer le focus clavier à l'intérieur à l'ouverture,
  //  - empêcher le focus de "s'échapper" vers le contenu en dessous (piège de
  //    focus) tant que la fenêtre est ouverte,
  //  - rendre le focus au bouton qui a ouvert la fenêtre, à la fermeture.
  // ===========================================================================
  const projectsDataEl = document.querySelector("#projects-data");
  const lightbox = document.querySelector("#lightbox");

  if (projectsDataEl && lightbox) {
    // JSON.parse transforme le texte JSON écrit par PHP en tableau JS utilisable
    const projectsData = JSON.parse(projectsDataEl.textContent);

    // Récupération des éléments internes de la lightbox (voir porte_fo.php)
    const lightboxTitle = lightbox.querySelector("#lightbox-title");
    const lightboxImage = lightbox.querySelector("#lightbox-image");
    const lightboxEmpty = lightbox.querySelector("#lightbox-empty");
    const lightboxCounter = lightbox.querySelector("#lightbox-counter");
    const prevBtn = lightbox.querySelector(".lightbox-prev");
    const nextBtn = lightbox.querySelector(".lightbox-next");
    const closeBtn = lightbox.querySelector(".lightbox-close");

    // Variables d'état : quelles images sont actuellement affichées, laquelle,
    // et quel élément avait le focus juste avant l'ouverture (pour lui rendre
    // le focus à la fermeture).
    let currentImages = [];
    let currentIndex = 0;
    let currentTitle = "";
    let triggerElement = null;

    // Met à jour l'affichage (image + compteur + flèches) selon currentIndex
    function renderImage() {
      const hasImages = currentImages.length > 0;

      // On cache l'image OU le message "images à venir", jamais les deux à la fois
      lightboxImage.hidden = !hasImages;
      lightboxEmpty.hidden = hasImages;

      // Les flèches ne sont utiles que s'il y a au moins 2 images
      prevBtn.hidden = !hasImages || currentImages.length < 2;
      nextBtn.hidden = !hasImages || currentImages.length < 2;

      if (hasImages) {
        lightboxImage.src = currentImages[currentIndex];
        // Texte alternatif descriptif, reconstruit à chaque image affichée
        // (indispensable pour un lecteur d'écran : sans lui, l'image serait
        // ignorée ou annoncée juste comme "image" sans contexte)
        lightboxImage.alt = `${currentTitle} — capture d'écran ${currentIndex + 1} sur ${currentImages.length}`;
        lightboxCounter.textContent = `${currentIndex + 1} / ${currentImages.length}`;
      } else {
        lightboxImage.src = "";
        lightboxImage.alt = "";
        lightboxCounter.textContent = "";
      }
    }

    // Renvoie la liste des éléments focusables actuellement visibles dans la
    // lightbox, dans l'ordre où Tab doit les parcourir (utilisé par le piège
    // de focus ci-dessous).
    function getFocusableElements() {
      return Array.from(
        lightbox.querySelectorAll("button:not([hidden])")
      );
    }

    // Ouvre la lightbox pour le projet dont l'index est donné (voir data-project-index en PHP)
    function openLightbox(projectIndex, trigger) {
      const project = projectsData[projectIndex];
      if (!project) return; // Sécurité : si l'index ne correspond à rien, on ne fait rien

      triggerElement = trigger; // Mémorise le bouton "Démo" cliqué, pour lui rendre le focus à la fermeture
      currentImages = project.images || [];
      currentIndex = 0; // On repart toujours de la première image
      currentTitle = project.title;
      lightboxTitle.textContent = project.title;
      renderImage();
      lightbox.hidden = false; // Rend la fenêtre modale visible

      // Déplace le focus clavier dans la fenêtre modale (sur le bouton
      // fermer, toujours présent) : sans ça, le focus resterait "derrière"
      // sur la page, ce qui est très déroutant au clavier/lecteur d'écran.
      closeBtn.focus();
    }

    function closeLightbox() {
      lightbox.hidden = true;
      // Rend le focus à l'endroit d'où l'utilisateur est parti, plutôt que
      // de le laisser au début de la page (comportement attendu pour un
      // utilisateur clavier qui referme une fenêtre modale).
      if (triggerElement) {
        triggerElement.focus();
      }
    }

    // Chaque bouton "Démo" ouvre la lightbox avec les images de SON projet
    document.querySelectorAll(".btn-demo").forEach((btn) => {
      btn.addEventListener("click", () => {
        openLightbox(Number(btn.dataset.projectIndex), btn);
      });
    });

    // Image précédente : le "%" (modulo) permet de revenir à la dernière image
    // quand on est sur la première (boucle infinie dans les deux sens)
    prevBtn.addEventListener("click", () => {
      currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
      renderImage();
    });

    // Image suivante, avec le même principe de boucle
    nextBtn.addEventListener("click", () => {
      currentIndex = (currentIndex + 1) % currentImages.length;
      renderImage();
    });

    closeBtn.addEventListener("click", closeLightbox);

    // Clic en dehors du panneau (sur le fond sombre) = fermeture de la lightbox
    lightbox.addEventListener("click", (event) => {
      if (event.target === lightbox) closeLightbox();
    });

    // Raccourcis clavier : Échap pour fermer, flèches gauche/droite pour naviguer,
    // Tab/Maj+Tab piégés à l'intérieur de la fenêtre (uniquement quand elle est ouverte)
    document.addEventListener("keydown", (event) => {
      if (lightbox.hidden) return;

      if (event.key === "Escape") {
        closeLightbox();
        return;
      }
      if (event.key === "ArrowLeft") prevBtn.click();
      if (event.key === "ArrowRight") nextBtn.click();

      // Piège de focus : tant que la lightbox est ouverte, Tab ne doit
      // circuler qu'entre ses propres boutons, jamais repartir vers le
      // contenu de la page en dessous.
      if (event.key === "Tab") {
        const focusable = getFocusableElements();
        if (focusable.length === 0) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey && document.activeElement === first) {
          event.preventDefault();
          last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
          event.preventDefault();
          first.focus();
        }
      }
    });
  }

  // ===========================================================================
  // 5) FORMULAIRE DE CONTACT — version statique (GitHub Pages)
  // Cette page est un export statique de porte_fo.php : il n'y a pas de serveur
  // PHP ici pour traiter le formulaire (voir contact.php dans la version
  // dynamique du site). À la place, on construit un lien "mailto:" à partir
  // des champs remplis et on y redirige le navigateur, qui ouvre alors le
  // client mail de la visiteuse ou du visiteur avec le message déjà rempli.
  // ===========================================================================
  const contactForm = document.querySelector("#contact-form");
  if (contactForm) {
    contactForm.addEventListener("submit", (event) => {
      event.preventDefault(); // Jamais de vraie soumission HTTP ici : que du mailto

      const name = contactForm.querySelector("#name");
      const email = contactForm.querySelector("#email");
      const message = contactForm.querySelector("#message");
      let valid = true;

      // On vérifie chaque champ obligatoire : bordure rouge + aria-invalid="true"
      // s'il est vide (aria-invalid permet à un lecteur d'écran d'annoncer le
      // champ comme invalide, pas seulement de le montrer visuellement en rouge).
      [name, email, message].forEach((field) => {
        if (!field.value.trim()) {
          field.style.borderColor = "#c8324f";
          field.setAttribute("aria-invalid", "true");
          valid = false;
        } else {
          field.style.borderColor = "";
          field.removeAttribute("aria-invalid");
        }
      });

      if (!valid) {
        return;
      }

      // encodeURIComponent échappe les espaces/retours à la ligne/caractères
      // spéciaux pour qu'ils tiennent dans une URL "mailto:"
      const subject = encodeURIComponent(`Message depuis le portfolio — ${name.value}`);
      const body = encodeURIComponent(
        `${message.value}\n\n— ${name.value} (${email.value})`
      );
      window.location.href = `mailto:agbehonouaesther@gmail.com?subject=${subject}&body=${body}`;
    });
  }
});
