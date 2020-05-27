// Déclaration du mode strict
'use strict';

/*************************************************************/
/************************** Variables **********************/
/*************************************************************/
let submit = document.querySelector('.login-form'); // On cible le bouton formulaire
let name = document.querySelector('.pseudo'); // On cible l'input qui reçoit le pseudo
let birthDate = document.querySelector('.birthDate'); // On cible l'input qui reçoit la date de naissance
let mail = document.querySelector('.mail'); // On cible l'input qui reçoit le mail
let password = document.querySelector('.password'); // On cible l'input qui reçoit le mot de passe
let passwordConfirm = document.querySelector('.passwordConfirm'); // On cible l'input qui reçoit la confirmation du mot de passe
let regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/; // Regex pour l'email
let totalErrors; // Tableau vide où on va stocker les différentes erreurs repérées par le validateur pour les afficher sur la vue
let aside = document.querySelector('.errors'); // On cible l'élément aside qui recevra dynamiquement le détail des erreurs

/*************************************************************/
/************************** Fonctions **********************/
/*************************************************************/
/**
 * Fonction destinée à vérifier la validité du pseudo
 */
 function checkUserName() {

   // On créé un tableau vide pour stocker les erreurs relatives au pseudo
   let errors = [];

   // On vérifie d'abord que le champ n'est pas vide à l'envoi
   if (name.value == '') {

      // On ajoute un message d'erreur à notre tableau
      errors.push({
        fieldname: name.dataset.name,
        content: ' est requis.'
      });

      // On met la bordure en rouge
      name.style.borderBottom = '3px solid red';

   } else if (!isNaN(name.value)) {
     // Si le pseudo contient des chiffres, on affiche un message d'erreur
     errors.push({
       fieldname: name.dataset.name,
       content: ' ne doit pas comporter de chiffres.'
     });

     // On met la bordure en rouge
     name.style.borderBottom = '3px solid red';

   } else {

     name.style.borderBottom = 'none';

   }

   // On fusionne les 2 tableaux
    $.merge(totalErrors, errors);

 }

 /**
  * Fonction dédiée à la vérification de l'email
  */
  function checkUserMail() {
    // On créé un tableau vide pour stocker les erreurs relatives au pseudo
    let errors = [];

    if (mail.value == '') {
      // Si le champ email est vide
      // On ajoute un message d'erreur à notre tableau
      errors.push({
        fieldname: mail.dataset.name,
        content: ' est requis.'
      });

      // On met la bordure en rouge
      mail.style.borderBottom = '3px solid red';


    } else if (regex.test(mail.value) == false) {
      // On teste si le mail respecte le format du regex
      errors.push({
        fieldname: mail.dataset.name,
        content: ' est invalide.'
      });

      // On met la bordure en rouge
      mail.style.borderBottom = '3px solid red';

    } else {

      mail.style.borderBottom = 'none';

    }


    // On fusionne les 2 tableaux
     $.merge(totalErrors, errors);

  }

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

   // Fonction qui vérifie le pseudo
   checkUserName();

   // Fonction qui vérifie l'email
   checkUserMail();

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
