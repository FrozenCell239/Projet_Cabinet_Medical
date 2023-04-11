# Changement et ajouts prévus :
- Mettre en erreur si le médecin demandé a déjà un rendez-vous à l'instant demandé.
- Réafficher les besoins des patients dans la liste des patients.
- Inclure delete.php dans server.php. (?)
- Notifier les médecins en cas du changement du digicode. (?)
- Notifier les médecins pour changer régulièrement leur mot de passe.
- Ne pas afficher les rendez-vous passés.
- Supprimer les rendez-vous passés après période définie.
- Reset le $row dans chaque script avec `$row = array();` & tester l'ajout de `mysqli_close($conn);` à chaque fin de script. (?)
- Afficher les logs dans access_manage.php (avec collapsing).
- Ajouter conditions de mots de passe comptes (minimum 8 caractères alphanumériques).
- Ajouter conditions de code de porte (4 à 10 caractères entre '0' et '9', entre 'A' et 'D', et '*').
- Faire une fonction pour récupérer les ID (pour épargner quatre lignes à chaque fois qu'on a besoin).

# Notes personnelles :
- <u>/!\\</u> Enregistrer les badges dans la BDD en enlevant les zéros et les espaces des numéros !
