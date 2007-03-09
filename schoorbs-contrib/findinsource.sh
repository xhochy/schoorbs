#!/bin/sh
find -name "*" -type f -exec grep -H $1 "{}" ";" | grep -v .svn | grep -v ChangeLog | grep -v schoorbs-doc
