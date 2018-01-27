=== Abhängigkeiten Installation 
Pour faciliter la mise en place des dépendances, jeedom va gérer seul l'installation de motion.

Dans la cadre réservé aux dépendances, vous allez avoir le statut de l'installation.
Nous avons aussi la possibilité de consulter le log d'installation en temps réel.

image::../images/Installation_dependance.jpg[]

=== Konfiguration des Plugins und seine Abhängigkeiten
image::../images/motion_screenshot_configuration.jpg[]

Die Parameter der allgemeinen Konfiguration sind

* Adresse ou motion est installé : Ce champs est complété automatiquement
* Port où motion est installé : C'est le port sur lequel on va se connecter pour mettre a jour la configuration de motion
* Taille du dossier Snapshot de chaque camera (Mo) :Taille du dossier des snapshots, pour chaque camera, pris depuis motion

Nous pouvons voir le status de configuration et d'activation de motion dans le cadre "Démon"

image::../images/Status_Demon.jpg[]
Si tous les voyants sont au vert, nous pouvons passer à la suite