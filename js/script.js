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
  // 1) MENU MOBILE (bouton "hamburger" ☰)
  // Sur petit écran, la CSS cache .nav-links par défaut. Un clic sur le bouton
  // ajoute/retire la classe "open" qui rend le menu visible (voir style.css).
  // ===========================================================================
  const navToggle = document.querySelector(".nav-toggle");
  const navLinks = document.querySelector(".nav-links");

  if (navToggle && navLinks) {
    // Ouvre/ferme le menu à chaque clic sur le bouton hamburger
    navToggle.addEventListener("click", () => {
      navLinks.classList.toggle("open");
    });

    // Referme automatiquement le menu quand on clique sur un lien
    // (évite qu'il reste ouvert après avoir navigué vers une section)
    navLinks.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => navLinks.classList.remove("open"));
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
  // Chaque bloc marqué "reveal" (les panneaux pixel-art) démarre invisible et
  // légèrement décalé vers le bas, puis apparaît en fondu dès qu'il devient
  // visible à l'écran pour la première fois.
  // ===========================================================================
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

  // ===========================================================================
  // 4) LIGHTBOX — GALERIE D'IMAGES DES PROJETS
  // Quand on clique sur le bouton "Démo" d'un projet, une fenêtre modale
  // s'ouvre et affiche les captures d'écran de ce projet (une à la fois, avec
  // navigation précédent/suivant). Les données (titre + liste d'images de
  // chaque projet) sont fournies par PHP dans la balise <script id="projects-data">
  // sous forme de JSON, généré dynamiquement dans porte_fo.php.
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

    // Variables d'état : quelles images sont actuellement affichées, et laquelle
    let currentImages = [];
    let currentIndex = 0;

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
        lightboxCounter.textContent = `${currentIndex + 1} / ${currentImages.length}`;
      } else {
        lightboxImage.src = "";
        lightboxCounter.textContent = "";
      }
    }

    // Ouvre la lightbox pour le projet dont l'index est donné (voir data-project-index en PHP)
    function openLightbox(projectIndex) {
      const project = projectsData[projectIndex];
      if (!project) return; // Sécurité : si l'index ne correspond à rien, on ne fait rien

      currentImages = project.images || [];
      currentIndex = 0; // On repart toujours de la première image
      lightboxTitle.textContent = project.title;
      renderImage();
      lightbox.hidden = false; // Rend la fenêtre modale visible
    }

    function closeLightbox() {
      lightbox.hidden = true;
    }

    // Chaque bouton "Démo" ouvre la lightbox avec les images de SON projet
    document.querySelectorAll(".btn-demo").forEach((btn) => {
      btn.addEventListener("click", () => {
        openLightbox(Number(btn.dataset.projectIndex));
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

    // Raccourcis clavier : Échap pour fermer, flèches gauche/droite pour naviguer
    // (uniquement actifs quand la lightbox est ouverte)
    document.addEventListener("keydown", (event) => {
      if (lightbox.hidden) return;
      if (event.key === "Escape") closeLightbox();
      if (event.key === "ArrowLeft") prevBtn.click();
      if (event.key === "ArrowRight") nextBtn.click();
    });
  }

  // ===========================================================================
  // 5) VALIDATION CÔTÉ CLIENT DU FORMULAIRE DE CONTACT
  // Vérification rapide avant envoi (retour visuel immédiat pour l'utilisateur).
  // Attention : ce n'est qu'un confort, la vraie validation de sécurité se fait
  // côté serveur dans contact.php (le JS peut toujours être contourné).
  // ===========================================================================
  const contactForm = document.querySelector("#contact-form");
  if (contactForm) {
    contactForm.addEventListener("submit", (event) => {
      const name = contactForm.querySelector("#name");
      const email = contactForm.querySelector("#email");
      const message = contactForm.querySelector("#message");
      let valid = true;

      // On vérifie chaque champ obligatoire et on colore la bordure en rouge
      // s'il est vide, en revenant à la couleur normale sinon
      [name, email, message].forEach((field) => {
        if (!field.value.trim()) {
          field.style.borderColor = "#d3352b";
          valid = false;
        } else {
          field.style.borderColor = "";
        }
      });

      // Si un champ est invalide, on bloque l'envoi du formulaire
      if (!valid) {
        event.preventDefault();
      }
    });
  }
});
