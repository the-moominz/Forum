// Déclaration du mode strict
'use strict';

/*************************************************************/
/************************** Variables **********************/
/*************************************************************/
let submit = document.querySelector('.login-form'); // On cible le bouton formulaire
let password = document.querySelector('.password'); // On cible l'input qui reçoit le mot de passe
let passwordConfirm = document.querySelector('.passwordConfirm'); // On cible l'input qui reçoit la confirmation du mot de passe
let totalErrors; // Tableau vide où on va stocker les différentes erreurs repérées par le validateur pour les afficher sur la vue
let aside = document.querySelector('.errors'); // On cible l'élément aside qui recevra dynamiquement le détail des erreurs

/*************************************************************/
/************************** Fonctions **********************/
/*************************************************************/

/**
 * Fonction dédiée à la vérification du champ Mot de passe
 */
 function checkPassword() {
   // On créé un tableau vide pour stocker les erreurs relatives au pseudo
   let errors = [];

   if (password.value == '' && passwordConfirm.value == '') {
     // Si les champs dédiés aux mot de passe sont vides
     // On ajoute un message d'erreur à notre tableau
     errors.push({
       fieldname: password.dataset.name,
       content: ' est requis.'
     }, {
       fieldname: passwordConfirm.dataset.name,
       content: ' est requis.'
     });

     // On met la bordure en rouge
     password.style.borderBottom = '3px solid red';
     passwordConfirm.style.borderBottom = '3px solid red';

   } else if (password.value.length < 8) {
     // Si le mot de passe fait moins de 8 caractères
     errors.push({
       fieldname: password.dataset.name,
       content: ' doit faire au moins 8 caractères.'
     });

     // On met la bordure en rouge
     password.style.borderBottom = '3px solid red';

   } else if (password.value != passwordConfirm.value) {
     // Si la valeur du mot de passe est différente de la valeur de la confirmation
     errors.push({
       fieldname: password.dataset.name,
       content: ' est différent de la ' + passwordConfirm.dataset.name
     });

     // On met la bordure en rouge
     password.style.borderBottom = '3px solid red';
     passwordConfirm.style.borderBottom = '3px solid red';

   } else {

     // On met la bordure en rouge
     password.style.borderBottom = 'none';
     passwordConfirm.style.borderBottom = 'none';

   }

   // On fusionne les 2 tableaux
    $.merge(totalErrors, errors);

 }

/**
* Fonction dédiée à lancer toutes les fonctions qui vont vérifier un à un tous les champs de formulaire
*/
function onClickCheckSubmitForm(event) {

 // On vide l'intérieur de l'élément HTML
 aside.innerHTML = '';

 // On crée un nouveau tableau vide
 totalErrors = [];
 console.log(totalErrors);

 // Fonction qui vérifie le mot de passe & sa confirmation
 checkPassword();

 // Affichage, en cas d'erreurs, du tableau à la vue dans l'élément spécifié
 if (totalErrors.length > 0) {
   // S'il y a des erreurs
   totalErrors.forEach(function (error) {
     let message = 'Le champ <strong>' + error.fieldname + '</strong> ' + error.content + '<br>';

     // Ajout du message à l'élément HTML
     aside.innerHTML += message;
   });

   // On stoppe la propagation de l'event
   event.preventDefault();
 }
}

/**
* Fonction qui va gérer le lancement de la validation du formulaire
*/
function run() {
  // Gestionnaire d'évènement pour la soumission du formulaire
  submit.addEventListener('submit', onClickCheckSubmitForm);
}
