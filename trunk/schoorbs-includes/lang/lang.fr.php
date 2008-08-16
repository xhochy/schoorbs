<?php
# $Id: lang.fr,v 1.23 2004/07/28 10:01:13 jberanek Exp $

# This file contains PHP code that specifies language specific strings
# The default strings come from lang.en, and anything in a locale
# specific file will overwrite the default. This is a French file.
#
# Translations provided by: Thierry Wehr, thierry_bo
#
#
# This file is PHP code. Treat it as such.

# The charset to use in "Content-type" header
$vocab["charset"]            = "utf-8";

# Used in style.inc
$vocab["mrbs"]               = "Système de Réservation de Ressources";

# Used in functions.inc
$vocab["report"]             = "Rapport";
$vocab["admin"]              = "Gestion";
$vocab["help"]               = "Aide";
$vocab["search"]             = "Recherche";
$vocab["not_php3"]             = "<h1>ATTENTION: Cette application peut ne pas fonctionner correctement avec PHP3</H1>";

# Used in day.php
$vocab["bookingsfor"]        = "Réservation pour<br />";
$vocab["bookingsforpost"]    = "";
$vocab["areas"]              = "Lieux";
$vocab["daybefore"]          = "Aller au jour précédent";
$vocab["dayafter"]           = "Aller au jour suivant";
$vocab["gototoday"]          = "Aujourd'hui";
$vocab["goto"]               = " Afficher";
$vocab["highlight_line"]     = "Surligner cette ligne";
$vocab["click_to_reserve"]   = "Cliquer sur la case pour réserver.";

# Used in trailer.inc
$vocab["viewday"]            = "Voir la journée";
$vocab["viewweek"]           = "Voir la semaine";
$vocab["viewmonth"]          = "Voir le mois";
$vocab["ppreview"]           = "Format imprimable";

# Used in edit_entry.php
$vocab["addentry"]           = "Ajouter une réservation";
$vocab["editentry"]          = "Editer une réservation";
$vocab["editseries"]         = "Editer une périodicité";
$vocab["namebooker"]         = "Brève description :";
$vocab["fulldescription"]    = "Description complète:<br />&nbsp;&nbsp;(Nombre de personnes,<br />&nbsp;&nbsp;Interne/Externe etc)";
$vocab["date"]               = "Date :";
$vocab["start_date"]         = "Date de début :";
$vocab["end_date"]           = "Date de fin :";
$vocab["time"]               = "Heure :";
$vocab["period"]             = "Période:";
$vocab["duration"]           = "Durée :";
$vocab["seconds"]            = "secondes";
$vocab["minutes"]            = "minutes";
$vocab["hours"]              = "heures";
$vocab["days"]               = "jours";
$vocab["weeks"]              = "semaines";
$vocab["years"]              = "années";
$vocab["periods"]            = "periodes";
$vocab["all_day"]            = "Journée entière";
$vocab["type"]               = "Type :";
$vocab["internal"]           = "Interne";
$vocab["external"]           = "Externe";
$vocab["save"]               = "Enregistrer";
$vocab["rep_type"]           = "Type de périodicité :";
$vocab["rep_type_0"]         = "Aucune";
$vocab["rep_type_1"]         = "Jour";
$vocab["rep_type_2"]         = "Semaine";
$vocab["rep_type_3"]         = "Mois";
$vocab["rep_type_4"]         = "Année";
$vocab["rep_type_5"]         = "Mois, même jour semaine";
$vocab["rep_type_6"]         = "tous les n semaines";
$vocab["rep_end_date"]       = "Date de fin de périodicité :";
$vocab["rep_rep_day"]        = "Jour :";
$vocab["rep_for_weekly"]     = "(pour n-semaines)";
$vocab["rep_freq"]           = "Fréquence :";
$vocab["rep_num_weeks"]      = "Intervalle de semaines";
$vocab["rep_for_nweekly"]    = "(pour n-semaines)";
$vocab["ctrl_click"]         = "CTRL + clic souris pour sélectionner plusieurs salles";
$vocab["entryid"]            = "Réservation N° ";
$vocab["repeat_id"]          = "périodicité N° ";
$vocab["you_have_not_entered"] = "Vous n'avez pas saisi";
$vocab["you_have_not_selected"] = "Vous n'avez pas sélectionné";
$vocab["valid_room"]         = "salle.";
$vocab["valid_time_of_day"]  = "une heure valide.";
$vocab["brief_description"]  = "la description brève.";
$vocab["useful_n-weekly_value"] = "un intervalle de semaines valide.";

# Used in view_entry.php
$vocab["description"]        = "Description :";
$vocab["room"]               = "Salle ";
$vocab["createdby"]          = "Créée par :";
$vocab["lastupdate"]         = "Dernière mise à jour :";
$vocab["deleteentry"]        = "Effacer une réservation";
$vocab["deleteseries"]       = "Effacer une périodicité";
$vocab["confirmdel"]         = "Etes-vous sûr\\nde vouloir effacer\\ncette réservation ?\\n\\n";
$vocab["returnprev"]         = "Retour à la page précédente";
$vocab["invalid_entry_id"]   = "N° de réservation non valide";
$vocab["invalid_series_id"]  = "Invalid series id.";

# Used in edit_entry_handler.php
$vocab["error"]              = "Erreur";
$vocab["sched_conflict"]     = "Conflit entre réservations ";
$vocab["conflict"]           = "La nouvelle réservation entre en conflit avec la(les) réservation(s) suivante(s) :";
$vocab["too_may_entrys"]     = "Les options choisies créerons trop de réservations.<BR>Choisissez des options différentes !";
$vocab["returncal"]          = "Retour au calendrier";
$vocab["failed_to_acquire"]  = "Erreur, impossible d'obtenir l'accès exclusif à la base de données"; 
$vocab["mail_subject_entry"] = $mail["subject"];
$vocab["mail_body_new_entry"] = $mail["new_entry"];
$vocab["mail_body_del_entry"] = $mail["deleted_entry"];
$vocab["mail_body_changed_entry"] = $mail["changed_entry"];
$vocab["mail_subject_delete"] = $mail["subject_delete"];

# Authentication stuff
$vocab["accessdenied"]       = "Accès refusé";
$vocab["norights"]           = "Vous n'avez pas les droits suffisants pour faire une modification.";
$vocab["please_login"]       = "Veuillez vous identifier";
$vocab["user_name"]          = "Nom";
$vocab["user_password"]      = "Mot de passe";
$vocab["unknown_user"]       = "Utilisateur non identifié";
$vocab["you_are"]            = "Vous êtes";
$vocab["login"]              = "S'identifier";
$vocab["logoff"]             = "Se déconnecter";

# Authentication database
$vocab["user_list"]          = "Liste des utilisateurs";
$vocab["edit_user"]          = "Modification de l'utilisateur";
$vocab["delete_user"]        = "Supprimer cet utilisateur";
#$vocab["user_name"]         = Use the same as above, for consistency.
#$vocab["user_password"]     = Use the same as above, for consistency.
$vocab["user_email"]         = "Adresse mél";
$vocab["password_twice"]     = "Pour changer le mot de passe, tapez le nouveau mot de passe ici deux fois";
$vocab["passwords_not_eq"]   = "Erreur: Les mots de passe ne sont pas identiques.";
$vocab["add_new_user"]       = "Ajouter un nouvel utilisateur";
$vocab["rights"]             = "Droits";
$vocab["action"]             = "Action";
$vocab["user"]               = "Utilisateur";
$vocab["administrator"]      = "Administrateur";
$vocab["unknown"]            = "Inconnu";
$vocab["ok"]                 = "OK";
$vocab["show_my_entries"]    = "Afficher mes réservations à venir";

# Used in search.php
$vocab["invalid_search"]     = "Recherche non valide.";
$vocab["search_results"]     = "Résultats de la recherche pour :";
$vocab["nothing_found"]      = "Aucune réservation n'a été trouvée.";
$vocab["records"]            = "Enregistrements ";
$vocab["through"]            = " à ";
$vocab["of"]                 = " sur ";
$vocab["previous"]           = "Précédent";
$vocab["next"]               = "Suivant";
$vocab["entry"]              = "Réservation";
$vocab["view"]               = "Voir";
$vocab["advanced_search"]    = "Recherche avancée"; 
$vocab["search_button"]      = "Recherche"; 
$vocab["search_for"]         = "Rechercher"; 
$vocab["from"]               = "A partir de"; 

# Used in report.php
$vocab["report_on"]          = "Rapport des réservations :";
$vocab["report_start"]       = "Date de début du rapport :";
$vocab["report_end"]         = "Date de fin du rapport :";
$vocab["match_area"]         = "Lieu :";
$vocab["match_room"]         = "Salle :";
$vocab["match_type"]         = "Type :";
$vocab["ctrl_click_type"]    = "CTRL + clic souris pour sélectionner plusieurs types";
$vocab["match_entry"]        = "Brève description :";
$vocab["match_descr"]        = "Description complète :";
$vocab["include"]            = "Inclure :";
$vocab["report_only"]        = "le rappport seulement";
$vocab["summary_only"]       = "le résumé seulement";
$vocab["report_and_summary"] = "le rapport et le résumé";
$vocab["summarize_by"]       = "Résumé par :";
$vocab["sum_by_descrip"]     = "Brève description";
$vocab["sum_by_creator"]     = "Créateur";
$vocab["entry_found"]        = "réservation trouvée";
$vocab["entries_found"]      = "réservations trouvées";
$vocab["summary_header"]     = "Décompte des Heures Réservées";
$vocab["summary_header_per"] = "Décompte des Périodes Réservées";
$vocab["total"]              = "Total";
$vocab["submitquery"]        = "Afficher le rapport";
$vocab["sort_rep"]           = "Trier par :";
$vocab["sort_rep_time"]      = "Date/Heure";
$vocab["rep_dsp"]            = "Afficher :";
$vocab["rep_dsp_dur"]        = "la durée";
$vocab["rep_dsp_end"]        = "l'heure de fin";

# Used in week.php
$vocab["weekbefore"]         = "Voir la semaine précédente";
$vocab["weekafter"]          = "Voir la semaine suivante";
$vocab["gotothisweek"]       = "Voir cette semaine-ci";

# Used in month.php
$vocab["monthbefore"]        = "Voir le mois précédent";
$vocab["monthafter"]         = "Voir le mois suivant";
$vocab["gotothismonth"]      = "Voir ce mois-ci";

# Used in {day week month}.php
$vocab["no_rooms_for_area"]  = "Aucune salle n'est définie pour ce lieu";

# Used in admin.php
$vocab["edit"]               = "Modifier";
$vocab["delete"]             = "Supprimer";
$vocab["rooms"]              = "Salles";
$vocab["in"]                 = "de :";
$vocab["noareas"]            = "Pas de lieux";
$vocab["addarea"]            = "Ajouter un lieu";
$vocab["name"]               = "Nom";
$vocab["noarea"]             = "Sélectionnez d'abord un lieu";
$vocab["browserlang"]        = "Votre navigateur est configuré pour utiliser la langue";
$vocab["postbrowserlang"]    = ".";
$vocab["addroom"]            = "Ajouter une salle";
$vocab["capacity"]           = "Maximum de personnes";
$vocab["norooms"]            = "Aucune salle n'est créée pour ce lieu.";
$vocab["administration"]     = "Administration";

# Used in edit_area_room.php
$vocab["editarea"]           = "Modifier le lieu";
$vocab["change"]             = "Changer";
$vocab["backadmin"]          = "Revenir à l'écran de gestion";
$vocab["editroomarea"]       = "Modifiez la description d'un lieu ou d'une salle";
$vocab["editroom"]           = "Modifier la salle";
$vocab["update_room_failed"] = "La mise à jour de la salle a échoué: ";
$vocab["error_room"]         = "Erreur: salle ";
$vocab["not_found"]          = " non trouvée";
$vocab["update_area_failed"] = "La mise à jour du lieu a échoué: ";
$vocab["error_area"]         = "Erreur: lieu ";
$vocab["room_admin_email"]   = "Emails des responsables:";
$vocab["area_admin_email"]   = "Emails des responsables:";
$vocab["invalid_email"]      = "Adresse email non valide !";

# Used in del.php
$vocab["deletefollowing"]    = "Vous allez supprimer les réservations suivantes";
$vocab["sure"]               = "Etes-vous certains ?";
$vocab["YES"]                = "OUI";
$vocab["NO"]                 = "NON";
$vocab["delarea"]            = "Vous devez supprimer toutes les salles de ce lieu avant de pouvoir le supprimer<p>";

# Used in help.php
$vocab["about_mrbs"]         = "A propos de MRBS (Meeting Room Booking System)";
$vocab["database"]           = "Base de donnée: ";
$vocab["system"]             = "Système d'exploitation: ";
$vocab["please_contact"]     = "Contactez ";
$vocab["for_any_questions"]  = "si vous avez une question qui n'est pas traitée ici.";

# Used in mysql.inc AND pgsql.inc
$vocab["failed_connect_db"]  = "Erreur grave: Echec de la connexion à la base de données";

?>
