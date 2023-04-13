# Changement et ajouts prévus :
- Mettre en erreur si le médecin demandé a déjà un rendez-vous à l'instant demandé.
- Possibilité de modifier un rendez-vous.
- Possibilité de modifier une salle.
- Possibilité de supprimer un rendez-vous (en cas d'annulation par le patient ou le docteur).
- Possibilité de mofidier un patient. (?)
- Dans la section des patients, afficher leurs rendez-vous avec besoin.
- Ajout et suppression de badges.
- Notifier les médecins en cas du changement du digicode (par mail ?). (?)
- Notifier les médecins pour changer régulièrement leur mot de passe.
- Possibilité d'afficher les rendez-vous passés qui ne sont pas encore supprimés.
- Possibilité de régler la période de suppression des rendez-vous passés (X jours ou X mois ou X années).
- Reset le $row dans chaque script avec `$row = array();` & tester l'ajout de `mysqli_close($conn);` à chaque fin de script. (?)
- Afficher les logs dans access_manage.php (avec collapsing).
- Ajouter conditions de mots de passe comptes (minimum 8 caractères alphanumériques).
- Ajouter conditions de code de porte (4 à 8 caractères entre '0' et '9', entre 'A' et 'D', et '*').

# Notes personnelles :
- <u>/!\\</u> Enregistrer les badges dans la BDD en enlevant les zéros et les espaces des numéros !