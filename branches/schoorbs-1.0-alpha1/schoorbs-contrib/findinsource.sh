#!/bin/sh
find -name "*" -type f -exec grep -Hn $1 "{}" ";" | grep -v .svn | grep -v ChangeLog | grep -v schoorbs-doc
