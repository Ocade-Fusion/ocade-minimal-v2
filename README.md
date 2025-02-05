# Template Plugin/Thème Base

* Le dépot du theme ou plugin doit etre public ! 
* Ce template à la base pour pouvoir créer un plugin ou un thème Wordpress.
* Lors du push sur github (branch master), une release est créer avec le numéro de version incrémenté.
* La release le plugin dans un dossier zip  et un fichier version.txt contenant la dernière version du plugin.
* Un hook (inc/theme-updater.php) permet de rechercher les mise a jour tout les 6H ou alors de refresh celle-ci avec `wp transient delete --all`
* Il ne reste plus qu'a: `wp core update && wp plugin update --all && wp theme update --all && wp language core update && wp language plugin update --all && wp language theme update --all` pour tout mettre à jour.

## Comment adapter le code ?

* Le branche du dépôt doit être `master` pas `main`.
* Supprimer le fichier `version.txt`
* Fichier `style.css`
* Fichier `functions.php`
* Fichier `.github/workflows/release.yml` changer `ocade-minimal` par le nom du dépot. 
