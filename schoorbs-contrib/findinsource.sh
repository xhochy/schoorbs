#!/bin/sh
find -name "*" -type f -exec grep -Hn $1 "{}" ";" | grep -v .svn | grep -v ChangeLog | grep -v schoorbs-doc | grep -v debian | grep -v schoorbs-dist | grep -v schoorbs-report | grep -v schoorbs-includes/Smarty/templates_c | grep -v "~" | grep -v schoorbs-includes/database/creole | grep -v schoorbs-includes/Smarty/libs | grep -v schoorbs-misc/themes/contented6/yui
