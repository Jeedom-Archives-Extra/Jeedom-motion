Il est maintenant temps de passer à la configuration des caméras

image::../images/motion_screenshot_Equipement.jpg[]

Sur cette image, j’ai dejà configuré une caméra, vous pouvez depuis cette page soit editer les caméras dejà parametrées soit en créer une autre


*Attention: * Ne pas configurer 2 fois la même caméra, cela peut entrainer un plantage du logiciel motion.

=== Configuration générale

image::../images/Configuration_Eqlogic.jpg[]

Les paramètres de configuration de cette page sont standard à ce que l'on retrouve sur jeedom.
Seul le champs "Execution de l'analyse" est particulier, il permet de choisir sur quel jeedom de notre réseaux on va demander l'execution de l'analyse du flux de la caméra configurée.

=== Configuration des paramètres de l’appareil de capture

image::../images/Configuration_Capture.jpg[]

*   V4l2_palette permet de choisir la palette préférable d’être utilisé par le mouvement

*   La norme vidéo à utiliser (seulement pour la capture vidéo et TV tuner cartes)

*   La fréquence du tuner (kHz) à régler (uniquement pour les cartes tuner TV)

*   Faire pivoter l’image de ce nombre de degrés. La rotation affecte toutes les images enregistrées comme

*   Largeur de l’image (pixels).

*   Hauteur de l’image (pixels).

*   Le nombre maximum d’images à être capturées par seconde.(Plage valide: 2-100.)

*   Délai minimum en secondes entre la capture des cadres de la caméra. Default: 0 = désactivé - le taux de capture est donné par le framerate de la caméra. Cette option est utilisée lorsque vous souhaitez capturer des images à un taux inférieur à 2 par seconde. / Définir contrôles jpeg moins stricts pour les caméras réseau avec un firmware buggé pauvres /.

*   Que mouvement réguler la luminosité d’un dispositif vidéo (par défaut: off). La fonction de auto_brightness utilise l’option de luminosité que sa valeur cible. Si la luminosité est auto_brightness zéro va ajuster à la valeur de luminosité moyenne de 128. Seulement recommandé pour les caméras sans luminosité automatique

*   Réglez la luminosité initiale d’un appareil vidéo. Si auto_brightness est activée, cette valeur définit le niveau de luminosité moyenne Qui Mouvement va essayer et ajuster.

*   Réglez le contraste d’un dispositif vidéo.

*   Réglez la saturation d’un dispositif vidéo.

*   Réglez la teinte d’un dispositif vidéo (option NTSC).

=== Configuration Round Robin (entrées multiples sur le même nom de périphérique vidéo)

image::../images/Configuration_RundRobin.jpg[]


*   Nombre de cadres de capturer à chaque étape de roundrobin

*   Nombre d’images à ignorer avant chaque étape de roundrobin

*   Essayez de filtrer le bruit généré par roundrobin

=== Configuration Paramètres de détection de mouvement:

image::../images/Configuration_Detection.jpg[]

*   Seuil de nombre de pixels modifiés dans une image Déclenche la détection de mouvement

*   Régler automatiquement le seuil bas si possible

*   Seuil de bruit pour la détection de mouvement

*   Automatiquement ajuster le seuil de bruit Image en mouvement Despeckle utilisant (e) Rode ou (d) ilate ou (l) abel (par défaut: non défini) Valeur recommandée est EedDl. Toute combinaison (et nombre) de E, E, D et D est valide. (L) Abeling ne doit être utilisé une fois et le «l» doit être la dernière lettre. Commentez désactiver despeckle_filter EedDl

*   Fichier PGM à utiliser comme un masque de sensibilité. Nom complet de chemin d’accès.

*   Créer dynamiquement un fichier de masque pendant le fonctionnement

*   Ajustez la vitesse des changements de masque de 0 (désactivé) à 10 (rapide)

*   Ignorer les changements massifs soudaine de l’intensité lumineuse donnée en pourcentage de l’image Zone qui a changé l’intensité. Plage valide: 0 - 100, défaut: 0 = désactivé

*   Cadres d’image doivent contenir le mouvement au moins le nombre d’images spécifié Dans une rangée avant qu’ils sont détecté comme un véritable mouvement. Au défaut de 1, tous les Mouvement est détecté. Plage valide: 1 à des milliers, recommandé 1-5

*   Indique le nombre de photos (mémoire tampon) pré-capturées à partir avant le mouvement A été détecté qui sera sortie à détection de mouvement. Plage recommandée: 0 à 5 (par défaut: 0) Ne pas utiliser de grandes valeurs! De grandes valeurs vont provoquer de mouvement pour sauter des images vidéo et Provoquent des films saccadés. Pour lisser les films utilisent de plus grandes valeurs de post_capture place.

*   Nombre de cadres pour capturer après le mouvement est plus détecté

*   Gap événement est les secondes sans détection de mouvement qui déclenche la fin d’un événement. Un événement est défini comme une série d’images en mouvement prises dans un délai court. Valeur recommandée est de 60 secondes (par défaut). La valeur -1 est autorisée et handicapés événements causant tout mouvement à écrire dans un fichier de film unique et aucun pre_capture. Si la valeur 0, le mouvement est exécuté en mode sans intervalle. Films ne ont plus de lacunes. Une Événement se termine juste après pas plus de mouvement est détecté et post_capture est terminée.

*   Longueur maximale en quelques secondes d’un film Quand la valeur est dépassée un nouveau fichier vidéo est créé.

*   Toujours enregistrer les images, même si il n’y avait pas de mouvement

=== Configuration Ficher image

image::../images/Configuration_ImageFile.jpg[]

*   Assurez instantané automatisé tous les N secondes

*   Sortie images «normales» lorsqu’un mouvement est détecté (par défaut: activé) Les valeurs valides: on, off, d’abord, le meilleur, centre Lorsque réglé sur «première», seule la première image d’un événement est enregistré. Image avec la plupart mouvement d’un événement est enregistré lorsqu’il est réglé sur «le meilleur». Image avec le mouvement centre le plus proche de l’image est enregistrée lorsque la valeur 'centre'. Peut être utilisé comme aperçu tir pour le film correspondant.

*   Photos de sortie avec seulement les pixels objet en mouvement (images fantômes)

*   La qualité (en %) pour être utilisé par la compression jpeg

=== Options FFMPEG liés au fichier vidéo, et le désentrelacement de l’entrée vidéo

image::../images/Configuration_FFMPEG.jpg[]

*   Utilisez ffmpeg pour encoder des vidéos en temps réel

*   Utilisez ffmpeg pour faire des films avec seulement les pixels en mouvement

*   Utilisez ffmpeg pour encoder un film timelapse Valeur par défaut 0 = off - autre chose que encadrer chaque seconde Nième

*   Le mode de la vidéo timelapse fichier de substitution Les valeurs valides: toutes les heures, tous les jours (par défaut), hebdomadaire dimanche, lundi hebdomadaire, mensuelle, manuel

*   Bitrate pour être utilisé par le codeur de ffmpeg Cette option est ignorée si ffmpeg_variable_bitrate est pas 0 (désactivé)

*   Active et définit un débit variable pour l’encodeur ffmpeg. Ffmpeg_bps est ignorée si le bitrate variable est activée. Les valeurs valides: 0 (par défaut) = débit fixe défini par ffmpeg_bps, Ou la gamme de 2 à 31 où 2 signifie meilleure qualité et 31 est pire.

*   Codec à utiliser par ffmpeg pour la compression vidéo. Mpegs Timelapse sont toujours faites en format MPEG1 indépendant de cette option. Les formats supportés sont: MPEG1 (ffmpeg-0.4.8 uniquement), mpeg4 (par défaut), et msmpeg4. MPEG1 - vous donne fichiers avec l’extension .mpg Mpeg4 ou msmpeg4 - vous donne les fichiers avec l’extension .avi Msmpeg4 est recommandé pour une utilisation avec Windows Media Player, car Il ne nécessite aucune installation de codec sur le client Windows. Swf - vous donne un film flash avec l’extension .swf Flv - vous donne une vidéo flash avec l’extension .flv Ffv1 - FF codec vidéo 1 pour Lossless Encoding (expérimental) Mov - QuickTime (test) Ogg - Ogg / Theora (test)

*   Utilisation ffmpeg pour désentrelacer vidéo. Nécessaire si vous utilisez une caméra analogique Voir peignage horizontal sur des objets dans la vidéo ou des images animées.

=== Configuration Affichage de texte

image::../images/Configuration_Text.jpg[]

% Y = année,% m = mois,% d = jour % H = heure, M = minute%,% S = seconde,% T = HH: MM: SS, % V = événement, q =% numéro de trame,% t = fil (caméra) nombre, % D = changé pixels,% N = niveau de bruit, \ n = nouvelle ligne, % I et% J = largeur et la hauteur de la zone de mouvement, % K et% L = coordonnées X et Y du centre de mouvement % C = valeur définie par text_event - ne pas utiliser avec text_event! Vous pouvez mettre des guillemets autour du texte pour permettre grands espaces

*   Localisez et dessiner un cadre autour de l’objet en mouvement. Les valeurs valides: on, off, aperçu (par défaut: off) Défini à «aperçu» ne fera que dessiner une boîte en images preview_shot.

*   Réglez le look et le style de la boîte de localiser si activé.# Les valeurs valides: boîte, Redbox, croisées, redcross (par défaut: case)

*   Réglez «boîte» tirera la boîte traditionnelle.

*   Réglez 'redbox' tirera une boîte rouge.

*   Réglez «croix» tirera une petite croix pour marquer le centre.

*   Réglez 'redcross' tirera une petite croix rouge pour marquer le centre.

*   Dessine l’horodatage utilisant les mêmes options que la fonction C strftime (3) Defaut: % Y-% m-% d \ n% T = date au format ISO et l’heure dans horloge de 24 heures Le texte est placé dans le coin inférieur droit

*   Dessinez un texte défini par l’utilisateur sur les images à l’aide mêmes options que la fonction C strftime (3) Par défaut: Non défini = aucun texte Le texte est placé dans le coin inférieur gauche

*   Dessinez le nombre de changement Pixed sur les images (par défaut: off) Sera normalement réglé à off, sauf lors de la configuration et de régler les paramètres de mouvement Le texte est placé dans le coin supérieur droit

*   Cette option définit la valeur de l’événement spécial spécificateur% C Vous pouvez utiliser n’importe quel indicateur de conversion dans cette option, sauf% C. Date et heure Les valeurs sont de l’horodatage de la première image de l’événement en cours. Defaut: % Y% m% d% H% M% S L’idée est que C% peut être utilisé les noms de fichiers et text_left / droite pour la création Un identifiant unique pour chaque événement.

*   Dessiner des personnages à deux fois la taille normale sur les images. (Par défaut: off)

*   Texte à inclure dans un commentaire JPEG EXIF Peut être tout texte, y compris les indicateurs de conversion. L’horodatage EXIF ​​est inclus indépendante de ce texte.

=== Configuration Nom de fichier de detection

image::../images/Configuration_DetectionFile.jpg[]

% Y = année,% m = mois,% d = jour % H = heure, M = minute%,% S = seconde,% T = HH: MM: SS, % V = événement, q =% numéro de trame,% t = fil (caméra) nombre, % D = changé pixels,% N = niveau de bruit, \ n = nouvelle ligne, % I et% J = largeur et la hauteur de la zone de mouvement, % K et% L = coordonnées X et Y du centre de mouvement % C = valeur définie par text_event - ne pas utiliser avec text_event! Vous pouvez mettre des guillemets autour du texte pour permettre grands espaces

*   Chemin du fichier de pour les instantanés (jpeg ou ppm) par rapport à target_dir Defaut: % v-% Y% m% d% H% M% S-snapshot Valeur par défaut est équivalente à l’option héritage oldlayout Pour mouvement mode compatible 3.0 choisir: % Y /% m /% d /% H /% M / S%-snapshot Fichier extension .jpg ou .ppm est automatiquement ajouté afin ne comprennent pas cela. Note: Un lien appelé lastsnap.jpg symbolique créé dans le target_dir toujours Le point avec le dernier snapshot, sauf snapshot_filename est exactement 'lastsnap'

*   Chemin du fichier pour le mouvement déclenché images (JPEG ou ppm) par rapport à target_dir Defaut: % v-% Y% m% d% H% M% S% q Valeur par défaut est équivalente à l’option héritage oldlayout Pour mouvement mode compatible 3.0 choisir: % Y /% m /% d /% H /% M / S%% q Fichier extension .jpg ou .ppm est automatiquement ajouté afin de ne pas inclure cette Réglez sur 'preview' avec caractéristique de meilleure prévisualisation permet nommage spéciale Convention pour aperçu coups. Voir guide de mouvement pour plus de détails

*   Chemin du fichier pour le mouvement déclenché ffmpeg films (films) par rapport à target_dir Defaut: % v-% Y% m% d% H% M% S Valeur par défaut est équivalente à l’option héritage oldlayout Pour mouvement mode compatible 3.0 choisir: % Y /% m /% d /% H% M% S Fichier extension .mpg ou .avi est automatiquement ajouté afin de ne pas inclure cette Cette option a été précédemment appelé ffmpeg_filename

*   Chemin du fichier pour les films timelapse rapport à target_dir Defaut: % Y% m% d-timelapse Valeur par défaut est près équivalente à l’option héritage oldlayout Pour mouvement mode compatible 3.0 choisir: % Y /% m /% d-timelapse Extension de fichier .mpg est automatiquement ajouté afin de ne pas inclure cette