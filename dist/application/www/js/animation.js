// Déclaration du mode strict
'use strict';

// Document Javascript dédiée aux effets (back to top etc.)
/*************************************************************/
/************************** Variables **********************/
/*************************************************************/
let toTop = document.querySelector('.backToTop'); // On cible la flèche qui permet de remonter en haut de la page
let burgerMenu = document.querySelector('.nav-burger-btn'); // On cible l'icône du menu burger
let navigation = document.querySelector('.navigation'); // On cible la navigation
let pseudo = document.querySelector('#pseudo-rwd'); // On cible l'affichage du pseudo


/*************************************************************/
/************************** Fonctions **********************/
/*************************************************************/

/**
 * Fonction dédiée à retourner en haut de la page
 * @param event
 */
 function backToTop(event) {

   // On stoppe la propagation de l'event
   event.preventDefault();

   // On fait scroller automatiquement jusqu'en haut de la page
   $('html,body').animate({ scrollTop: 0 }, 'slow');
 }

/**
 * Fonction dédiée à faire apparaître la navigation au clic sur le menu burger
 */
 function onClickDisplayNav() {
   if (navigation.style.display != "block") {
     // Si la navigation n'est pas en display: block
     navigation.style.display = "block"; // On le fait passer en block
     burgerMenu.classList.add('open'); // On change l'aspect du menu burger en ajoutant la classe Open
     pseudo.style.display = "none"; // On cache l'affichage du pseudo
   } else {
     navigation.style.display = "none";
     burgerMenu.classList.remove('open');
     pseudo.style.display = "block";
   }
 }
