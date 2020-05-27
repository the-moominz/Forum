// Déclaration du mode strict
'use strict';

/*************************************************************/
/********************** Code principal **********************/
/*************************************************************/
window.addEventListener('DOMContentLoaded', function(event) {
// Quand le document est chargé, lance le chargement des fonctions

  // Gestionnaire d'évènement pour faire remonter l'utilisateur tout en haut de la page
  toTop.addEventListener('click', backToTop);

  // Gestionnaire d'évènement pour ouvrir le menu de navigation
  burgerMenu.addEventListener('click', onClickDisplayNav);

  // Gestionnaire d'évènement pour vérifier les champs des formulaires
  run();

});
