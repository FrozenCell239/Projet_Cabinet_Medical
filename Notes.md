# Changement et ajouts prévus :
- Numéro de téléphone du patient.
- Dans la gestion des rendez-vous, possibiliter de filtrer les médecins et les salles.
- Possibilité de modifier une salle.
- Possibilité de supprimer un rendez-vous (en cas d'annulation par le patient ou le docteur).
- Possibilité de modifier un rendez-vous.
- Possibilité de mofidier un patient. (?)
- Ajout et suppression de badges.
- Notifier les médecins en cas du changement du digicode (par mail ?). (?)
- Notifier les médecins pour changer régulièrement leur mot de passe.
- Possibilité d'afficher les rendez-vous passés qui ne sont pas encore supprimés.
- Afficher les logs dans access_manage.php (avec collapsing).
- Ajouter conditions de mots de passe comptes (minimum 8 caractères alphanumériques).
- Pour les confirmations de suppression, remplacer le `confirm()` par un modal.
- Mise en beauté avec Tailwind et/ou Bootstrap.

# Autres idées :
- Dans la section des patients, ajouter une action "Gérer rendez-vous" pour chaque patient. → Redirection vers la page de création de rendez-vous avec le formulaire affiché et les cases "Prénom patient" et "Nom patient" pré-remplies avec le nom du patient.
- Idem avec médecins.

# Notes personnelles :
- <u>/!\\</u> Enregistrer les badges dans la BDD en enlevant les zéros et les espaces des numéros !