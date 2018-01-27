=== Dependencies install
To facilitate the implementation of the dependencies, jeedom is going to handle the installation of the motion software .

Dans la cadre réservé aux dépendances, vous allez avoir le statut de l'installation.
Nous avons aussi la possibilité de consulter le log d'installation en temps réel.

image::../images/Installation_dependance.jpg[]

=== Configuration du plugin et de ses dépendances
image::../images/motion_screenshot_configuration.jpg[]

Les paramètres de configuration générale sont

* Adresse ou motion est installé : Ce champs est complété automatiquement
* Port où motion est installé : C'est le port sur lequel on va se connecter pour mettre a jour la configuration de motion
* Taille du dossier Snapshot de chaque camera (Mo) :Taille du dossier des snapshots, pour chaque camera, pris depuis motion

Nous pouvons voir le status de configuration et d'activation de motion dans le cadre "Démon"

image::../images/Status_Demon.jpg[]
Si tous les voyants sont au vert, nous pouvons passer à la suite