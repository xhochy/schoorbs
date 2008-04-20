<?php
# $Id: lang.it,v 1.11 2004/07/28 10:01:13 jberanek Exp $

# This file contains PHP code that specifies language specific strings
# The default strings come from lang.en, and anything in a locale
# specific file will overwrite the default. This is an Italian file.
#
# Translations provided by: Gianni
#
#
# This file is PHP code. Treat it as such.

# The charset to use in "Content-type" header
$vocab["charset"]            = "iso-8859-1";

# Used in style.inc
$vocab["mrbs"]               = "Sistema di Prenotazione Sale";

# Used in functions.inc
$vocab["report"]             = "Report";
$vocab["admin"]              = "Admin";
$vocab["help"]               = "Aiuto";
$vocab["search"]             = "Ricerca";
$vocab["not_php3"]             = "<h1>WARNING: This probably doesn't work with PHP3</H1>";

# Used in day.php
$vocab["bookingsfor"]        = "Prenotazioni per";
$vocab["bookingsforpost"]    = "";
$vocab["areas"]              = "Aree";
$vocab["daybefore"]          = "Vai al Giorno Prima";
$vocab["dayafter"]           = "Vai al Giorno Dopo";
$vocab["gototoday"]          = "Vai a oggi";
$vocab["goto"]               = "Vai a";
$vocab["highlight_line"]     = "Highlight this line";
$vocab["click_to_reserve"]   = "Click on the cell to make a reservation.";

# Used in trailer.inc
$vocab["viewday"]            = "Vedi Giorno";
$vocab["viewweek"]           = "Vedi Settimana";
$vocab["viewmonth"]          = "Vedi Mese";
$vocab["ppreview"]           = "Anteprima Stampa";

# Used in edit_entry.php
$vocab["addentry"]           = "Aggiungi";
$vocab["editentry"]          = "Modifica";
$vocab["editseries"]         = "Modifica Serie";
$vocab["namebooker"]         = "Breve Descrizione:";
$vocab["fulldescription"]    = "Descrizione Completa:<br />&nbsp;&nbsp;(Numero di persone,<br />&nbsp;&nbsp;Interno/Esterno ecc..)";
$vocab["date"]               = "Data:";
$vocab["start_date"]         = "Ora Inizio:";
$vocab["end_date"]           = "Ora Fine:";
$vocab["time"]               = "Ora:";
$vocab["period"]             = "Period:";
$vocab["duration"]           = "Durata:";
$vocab["seconds"]            = "secondi";
$vocab["minutes"]            = "minuti";
$vocab["hours"]              = "ora";
$vocab["days"]               = "giorni";
$vocab["weeks"]              = "settimane";
$vocab["years"]              = "anni";
$vocab["periods"]            = "periods";
$vocab["all_day"]            = "Tutto il giorno";
$vocab["type"]               = "Tipo:";
$vocab["internal"]           = "Interno";
$vocab["external"]           = "Esterno";
$vocab["save"]               = "Salva";
$vocab["rep_type"]           = "Ripeti Tipo:";
$vocab["rep_type_0"]         = "Nessuno";
$vocab["rep_type_1"]         = "Giornaliero";
$vocab["rep_type_2"]         = "Settimanale";
$vocab["rep_type_3"]         = "Mensile";
$vocab["rep_type_4"]         = "Annuale";
$vocab["rep_type_5"]         = "Mensile, giorno corrispondente";
$vocab["rep_type_6"]         = "nSettimanale";
$vocab["rep_end_date"]       = "Ripeti data di Fine:";
$vocab["rep_rep_day"]        = "Ripeti Girno:";
$vocab["rep_for_weekly"]     = "(per (n-)settimanale)";
$vocab["rep_freq"]           = "Frequenza:";
$vocab["rep_num_weeks"]      = "Numero di settimane";
$vocab["rep_for_nweekly"]    = "(per n-settimanale)";
$vocab["ctrl_click"]         = "Use Control-Click to select more than one room";
$vocab["entryid"]            = "Entry ID ";
$vocab["repeat_id"]          = "Repeat ID "; 
$vocab["you_have_not_entered"] = "You have not entered a";
$vocab["you_have_not_selected"] = "You have not selected a";
$vocab["valid_room"]         = "room.";
$vocab["valid_time_of_day"]  = "valid time of day.";
$vocab["brief_description"]  = "Brief Description.";
$vocab["useful_n-weekly_value"] = "useful n-weekly value.";

# Used in view_entry.php
$vocab["description"]        = "Descrizione:";
$vocab["room"]               = "Sala";
$vocab["createdby"]          = "Creato da:";
$vocab["lastupdate"]         = "Ultima Modifica:";
$vocab["deleteentry"]        = "Cancella";
$vocab["deleteseries"]       = "Cancella Series";
$vocab["confirmdel"]         = "Sei sicuro\\nche vuoi\\ncancellare l\'elemento?\\n\\n";
$vocab["returnprev"]         = "Ritorna alla Pagina Precedente";
$vocab["invalid_entry_id"]   = "Invalid entry id.";
$vocab["invalid_series_id"]  = "Invalid series id.";

# Used in edit_entry_handler.php
$vocab["error"]              = "Errore";
$vocab["sched_conflict"]     = "Conflitto di Prenotazione";
$vocab["conflict"]           = "La nuova prenotazione sar� in conflitto con questa(e):";
$vocab["too_may_entrys"]     = "L'opzione selezionata crea troppe entit�.<BR>Per favore usa una opzione differente!";
$vocab["returncal"]          = "Ritorna al calendario";
$vocab["failed_to_acquire"]  = "Failed to acquire exclusive database access"; 
$vocab["mail_subject_entry"] = $mail["subject"];
$vocab["mail_body_new_entry"] = $mail["new_entry"];
$vocab["mail_body_del_entry"] = $mail["deleted_entry"];
$vocab["mail_body_changed_entry"] = $mail["changed_entry"];
$vocab["mail_subject_delete"] = $mail["subject_delete"];

# Authentication stuff
$vocab["accessdenied"]       = "Accesso Negato";
$vocab["norights"]           = "Non hai i diritti per modificare questo oggetto.";
$vocab["please_login"]       = "Please log in";
$vocab["user_name"]          = "Name";
$vocab["user_password"]      = "Password";
$vocab["unknown_user"]       = "Unknown user";
$vocab["you_are"]            = "You are";
$vocab["login"]              = "Log in";
$vocab["logoff"]             = "Log Off";

# Authentication database
$vocab["user_list"]          = "User list";
$vocab["edit_user"]          = "Edit user";
$vocab["delete_user"]        = "Delete this user";
#$vocab["user_name"]         = Use the same as above, for consistency.
#$vocab["user_password"]     = Use the same as above, for consistency.
$vocab["user_email"]         = "Email address";
$vocab["password_twice"]     = "If you wish to change the password, please type the new password twice";
$vocab["passwords_not_eq"]   = "Error: The passwords do not match.";
$vocab["add_new_user"]       = "Add a new user";
$vocab["rights"]             = "Rights";
$vocab["action"]             = "Action";
$vocab["user"]               = "User";
$vocab["administrator"]      = "Administrator";
$vocab["unknown"]            = "Unknown";
$vocab["ok"]                 = "OK";
$vocab["show_my_entries"]    = "Click to display all my upcoming entries";

# Used in search.php
$vocab["invalid_search"]     = "Valore di Ricerca vuoto o sbagliato.";
$vocab["search_results"]     = "Risultati ricerca per:";
$vocab["nothing_found"]      = "Nessun risultato trovato.";
$vocab["records"]            = "Trovati ";
$vocab["through"]            = " attraverso ";
$vocab["of"]                 = " di ";
$vocab["previous"]           = "Precedente";
$vocab["next"]               = "Successivo";
$vocab["entry"]              = "Valore";
$vocab["view"]               = "Vista";
$vocab["advanced_search"]    = "Advanced search";
$vocab["search_button"]      = "Ricerca";
$vocab["search_for"]         = "Search For";
$vocab["from"]               = "From";

# Used in report.php
$vocab["report_on"]          = "Report su Meetings:";
$vocab["report_start"]       = "Report su data inizio:";
$vocab["report_end"]         = "Report su data fine:";
$vocab["match_area"]         = "Trovata area:";
$vocab["match_room"]         = "Trovata stanza:";
$vocab["match_type"]         = "Match type:";
$vocab["ctrl_click_type"]    = "Use Control-Click to select more than one type";
$vocab["match_entry"]        = "Trovata descrizione breve:";
$vocab["match_descr"]        = "Trovata descrizione completa:";
$vocab["include"]            = "Includi:";
$vocab["report_only"]        = "Solo Report";
$vocab["summary_only"]       = "Solo Raggruppamento";
$vocab["report_and_summary"] = "Report e Raggruppamento";
$vocab["summarize_by"]       = "Raggruppa per:";
$vocab["sum_by_descrip"]     = "Breve descrizione";
$vocab["sum_by_creator"]     = "Creatore";
$vocab["entry_found"]        = "trovato valore";
$vocab["entries_found"]      = "trovati valori";
$vocab["summary_header"]     = "Gruppo di (Valori) Ore";
$vocab["summary_header_per"] = "Summary of (Entries) Periods";
$vocab["total"]              = "Totale";
$vocab["submitquery"]        = "Run Report";
$vocab["sort_rep"]           = "Sort Report by:";
$vocab["sort_rep_time"]      = "Start Date/Time";
$vocab["rep_dsp"]            = "Display in report:";
$vocab["rep_dsp_dur"]        = "Duration";
$vocab["rep_dsp_end"]        = "End Time";

# Used in week.php
$vocab["weekbefore"]         = "Vai alla Settimana Precedente";
$vocab["weekafter"]          = "Vai alla Settimana Successiva";
$vocab["gotothisweek"]       = "Vai alla Settimana Corrente";

# Used in month.php
$vocab["monthbefore"]        = "Vai al Mese Precedente";
$vocab["monthafter"]         = "Vai al Mese Successivo";
$vocab["gotothismonth"]      = "Vai al Mese Corrente";

# Used in {day week month}.php
$vocab["no_rooms_for_area"]  = "Non ci sono sale per questa Area";

# Used in admin.php
$vocab["edit"]               = "Edit";
$vocab["delete"]             = "Delete";
$vocab["rooms"]              = "Rooms";
$vocab["in"]                 = "in";
$vocab["noareas"]            = "No Areas";
$vocab["addarea"]            = "Add Area";
$vocab["name"]               = "Name";
$vocab["noarea"]             = "No area selected";
$vocab["browserlang"]        = "Your browser is set to use";
$vocab["postbrowserlang"]    = "language.";
$vocab["addroom"]            = "Add Room";
$vocab["capacity"]           = "Capacity";
$vocab["norooms"]            = "No rooms.";
$vocab["administration"]     = "Administration";

# Used in edit_area_room.php
$vocab["editarea"]           = "Edit Area";
$vocab["change"]             = "Change";
$vocab["backadmin"]          = "Back to Admin";
$vocab["editroomarea"]       = "Edit Area or Room Description";
$vocab["editroom"]           = "Edit Room";
$vocab["update_room_failed"] = "Update room failed: ";
$vocab["error_room"]         = "Error: room ";
$vocab["not_found"]          = " not found";
$vocab["update_area_failed"] = "Update area failed: ";
$vocab["error_area"]         = "Error: area ";
$vocab["room_admin_email"]   = "Room admin email:";
$vocab["area_admin_email"]   = "Area admin email:";
$vocab["invalid_email"]      = "Invalid email!";

# Used in del.php
$vocab["deletefollowing"]    = "This will delete the following bookings";
$vocab["sure"]               = "Are you sure?";
$vocab["YES"]                = "YES";
$vocab["NO"]                 = "NO";
$vocab["delarea"]            = "You must delete all rooms in this area before you can delete it<p>";

# Used in help.php
$vocab["about_mrbs"]         = "About MRBS";
$vocab["database"]           = "Database: ";
$vocab["system"]             = "System: ";
$vocab["please_contact"]     = "Please contact ";
$vocab["for_any_questions"]  = "for any questions that aren't answered here.";

# Used in mysql.inc AND pgsql.inc
$vocab["failed_connect_db"]  = "Fatal Error: Failed to connect to database";

?>
