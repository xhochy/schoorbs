<?php
# $Id: lang.pt,v 1.5 2004/07/28 10:01:13 jberanek Exp $

# This file contains PHP code that specifies language specific strings
# The default strings come from lang.en, and anything in a locale
# specific file will overwrite the default. This is a Portuguese file.
#
# Translated by: Lopo Pizarro
#
#
# This file is PHP code. Treat it as such.

# The charset to use in "Content-type" header
$vocab["charset"]            = "utf-8";

# Used in style.inc
$vocab["mrbs"]               = "Horários de salas";

# Used in functions.inc
$vocab["report"]             = "Relatório";
$vocab["admin"]              = "Administração";
$vocab["help"]               = "Ajuda";
$vocab["search"]             = "Pesquisa";
$vocab["not_php3"]             = "<h1>AVISO: Isto provavelmente não funciona com php3</H1>";

# Used in day.php
$vocab["bookingsfor"]        = "Marcações para";
$vocab["bookingsforpost"]    = ""; # Goes after the date
$vocab["areas"]              = "Áreas";
$vocab["daybefore"]          = "Ir para Dia Anterior";
$vocab["dayafter"]           = "Ir para Dia Seguinte";
$vocab["gototoday"]          = "Ir para hoje";
$vocab["goto"]               = "ir para";
$vocab["highlight_line"]     = "Highlight this line";
$vocab["click_to_reserve"]   = "Click on the cell to make a reservation.";

# Used in trailer.inc
$vocab["viewday"]            = "Ver Dia";
$vocab["viewweek"]           = "Ver Semana";
$vocab["viewmonth"]          = "Ver Mês";
$vocab["ppreview"]           = "Pré-visualizar Inpressão";

# Used in edit_entry.php
$vocab["addentry"]           = "Nova entrada";
$vocab["editentry"]          = "Editar entrada";
$vocab["editseries"]         = "Editar Serie";
$vocab["namebooker"]         = "Descição breve:";
$vocab["fulldescription"]    = "Descrição completa:<br />&nbsp;&nbsp;(Numero de Pessoas,<br />&nbsp;&nbsp;Internas/Externas etc)";
$vocab["date"]               = "Data:";
$vocab["start_date"]         = "Hora Início:";
$vocab["end_date"]           = "Hora Fim:";
$vocab["time"]               = "Hora:";
$vocab["period"]             = "Period:";
$vocab["duration"]           = "Duração:";
$vocab["seconds"]            = "segundos";
$vocab["minutes"]            = "minutos";
$vocab["hours"]              = "horas";
$vocab["days"]               = "dias";
$vocab["weeks"]              = "semanas";
$vocab["years"]              = "anos";
$vocab["periods"]            = "periods";
$vocab["all_day"]            = "Todos os dias";
$vocab["type"]               = "Tipo:";
$vocab["internal"]           = "Interno";
$vocab["external"]           = "Externo";
$vocab["save"]               = "Gravar";
$vocab["rep_type"]           = "Repetir Tipo:";
$vocab["rep_type_0"]         = "Nenhum";
$vocab["rep_type_1"]         = "Diariamente";
$vocab["rep_type_2"]         = "Semanalmente";
$vocab["rep_type_3"]         = "Mensalmente";
$vocab["rep_type_4"]         = "Anualmente";
$vocab["rep_type_5"]         = "Mensalmente, no dia correspoondente";
$vocab["rep_type_6"]         = "n-semanalmente";
$vocab["rep_end_date"]       = "Repetir final de data:";
$vocab["rep_rep_day"]        = "Repetir Dia:";
$vocab["rep_for_weekly"]     = "(durante (n-)semanalmente)";
$vocab["rep_freq"]           = "Frequência:";
$vocab["rep_num_weeks"]      = "Numero de semanas";
$vocab["rep_for_nweekly"]    = "(durante n-semanalmente)";
$vocab["ctrl_click"]         = "Carregue Control-Click para seleccionar mais de uma sala";
$vocab["entryid"]            = "ID de entrada";
$vocab["repeat_id"]          = "Repetir ID "; 
$vocab["you_have_not_entered"] = "Não introduziu uma";
$vocab["you_have_not_selected"] = "You have not selected a";
$vocab["valid_room"]         = "room.";
$vocab["valid_time_of_day"]  = "hora do dia válida.";
$vocab["brief_description"]  = "Descição breve.";
$vocab["useful_n-weekly_value"] = "valor n-semanal viável.";

# Used in view_entry.php
$vocab["description"]        = "Descrição:";
$vocab["room"]               = "Sala";
$vocab["createdby"]          = "Marcado por:";
$vocab["lastupdate"]         = "Última Actualização:";
$vocab["deleteentry"]        = "Apagar entrada";
$vocab["deleteseries"]       = "Apagar Series";
$vocab["confirmdel"]         = "Tem a certeza\\nque quer\\napagar esta entrada?\\n\\n";
$vocab["returnprev"]         = "Voltar à Página anterior";
$vocab["invalid_entry_id"]   = "Id inválido.";
$vocab["invalid_series_id"]  = "Invalid series id.";

# Used in edit_entry_handler.php
$vocab["error"]              = "Erro";
$vocab["sched_conflict"]     = "Conflito de marcações";
$vocab["conflict"]           = "A nova marcação entra em confito com as seguintes entrada(s):";
$vocab["too_may_entrys"]     = "A opção selecionada criará demasiadas entradas.<BR>Use outras opções por favor!";
$vocab["returncal"]          = "Voltar à vista de Calendário";
$vocab["failed_to_acquire"]  = "A tentativa de adquirir acesso exclusivo à base de dados falhou!"; 
$vocab["mail_subject_entry"] = $mail["subject"];
$vocab["mail_body_new_entry"] = $mail["new_entry"];
$vocab["mail_body_del_entry"] = $mail["deleted_entry"];
$vocab["mail_body_changed_entry"] = $mail["changed_entry"];
$vocab["mail_subject_delete"] = $mail["subject_delete"];

# Authentication stuff
$vocab["accessdenied"]       = "Acesso Negado";
$vocab["norights"]           = "Não tem permissões para alterar este item.";
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
$vocab["invalid_search"]     = "Dados para pesquisa vazios ou inválidos.";
$vocab["search_results"]     = "Resultados da pesquisa para:";
$vocab["nothing_found"]      = "Não foram encontrados registos.";
$vocab["records"]            = "Registos ";
$vocab["through"]            = " até ";
$vocab["of"]                 = " de ";
$vocab["previous"]           = "Anterior";
$vocab["next"]               = "Próximo";
$vocab["entry"]              = "Entrada";
$vocab["view"]               = "Ver";
$vocab["advanced_search"]    = "Pesquyisa Avançada";
$vocab["search_button"]      = "Perquisar";
$vocab["search_for"]         = "Pesquisar por";
$vocab["from"]               = "De";

# Used in report.php
$vocab["report_on"]          = "Relatório de Disciplinas:";
$vocab["report_start"]       = "Relatório de data inicial:";
$vocab["report_end"]         = "Relatório de data final:";
$vocab["match_area"]         = "Area correspondente:";
$vocab["match_room"]         = "Sala correspondente:";
$vocab["match_type"]         = "Match type:";
$vocab["ctrl_click_type"]    = "Use Control-Click to select more than one type";
$vocab["match_entry"]        = "Breve Descrição correspondente:";
$vocab["match_descr"]        = "Descrição completa correspondente:";
$vocab["include"]            = "Incluir:";
$vocab["report_only"]        = "Apenas relatório";
$vocab["summary_only"]       = "Apenas sumário";
$vocab["report_and_summary"] = "Relatório e sumário";
$vocab["summarize_by"]       = "Sumário por:";
$vocab["sum_by_descrip"]     = "Descrição por";
$vocab["sum_by_creator"]     = "Criador";
$vocab["entry_found"]        = "entrada encontrada";
$vocab["entries_found"]      = "entradas encontradas";
$vocab["summary_header"]     = "Sumário de (entradas) Horas";
$vocab["summary_header_per"] = "Summary of (Entries) Periods";
$vocab["total"]              = "Total";
$vocab["submitquery"]        = "Correr relatório";
$vocab["sort_rep"]           = "Sort Report by:";
$vocab["sort_rep_time"]      = "Start Date/Time";
$vocab["rep_dsp"]            = "Display in report:";
$vocab["rep_dsp_dur"]        = "Duration";
$vocab["rep_dsp_end"]        = "End Time";

# Used in week.php
$vocab["weekbefore"]         = "Ir para a semana Anterior";
$vocab["weekafter"]          = "Ir para a semana seguinte";
$vocab["gotothisweek"]       = "Ir para esta semana";

# Used in month.php
$vocab["monthbefore"]        = "Ir para o mês Anterior";
$vocab["monthafter"]         = "Ir para o mês seguinte";
$vocab["gotothismonth"]      = "Ir para este mês";

# Used in {day week month}.php
$vocab["no_rooms_for_area"]  = "Não há salas definidas para esta Área";

# Used in admin.php
$vocab["edit"]               = "Editar";
$vocab["delete"]             = "Apagar";
$vocab["rooms"]              = "Salas";
$vocab["in"]                 = "em";
$vocab["noareas"]            = "Não há Áreas";
$vocab["addarea"]            = "Acrescentar Área";
$vocab["name"]               = "Nome";
$vocab["noarea"]             = "Área não selecionada";
$vocab["browserlang"]        = "O seu browser está preparado para use";
$vocab["postbrowserlang"]    = "Idioma.";
$vocab["addroom"]            = "Acrescentar Sala";
$vocab["capacity"]           = "Capacidade";
$vocab["norooms"]            = "Não há salas.";
$vocab["administration"]     = "Administration";

# Used in edit_area_room.php
$vocab["editarea"]           = "Editar Área";
$vocab["change"]             = "Mudar";
$vocab["backadmin"]          = "Voltar à administração";
$vocab["editroomarea"]       = "Editar a descrição de Área ou Sala";
$vocab["editroom"]           = "Editar Sala";
$vocab["update_room_failed"] = "Actualizar a sala falhou: ";
$vocab["error_room"]         = "Erro: sala ";
$vocab["not_found"]          = " não encontrado";
$vocab["update_area_failed"] = "Actualização de área falhou: ";
$vocab["error_area"]         = "Erro: área ";
$vocab["room_admin_email"]   = "Room admin email:";
$vocab["area_admin_email"]   = "Area admin email:";
$vocab["invalid_email"]      = "Invalid email!";

# Used in del.php
$vocab["deletefollowing"]    = "Esta acção apagará as seguintes Marcações";
$vocab["sure"]               = "Tem a certeza?";
$vocab["YES"]                = "Sim";
$vocab["NO"]                 = "Não";
$vocab["delarea"]            = "Tem que apagar todas as salas nesta área antes de a poder apagar<p>";

# Used in help.php
$vocab["about_mrbs"]         = "Sobre o MRBS";
$vocab["database"]           = "Base de Dados: ";
$vocab["system"]             = "Sistema: ";
$vocab["please_contact"]     = "Contacte por favor ";
$vocab["for_any_questions"]  = "for any questions that aren't answered here.";

# Used in mysql.inc AND pgsql.inc
$vocab["failed_connect_db"]  = "Erro: Failha ao ligar à base de dados";

?>
