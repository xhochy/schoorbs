#!/usr/bin/php
<?php
/**
 * Builds some Packages for distribution
 * 
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @author Uwe L. Korn <uwelk@xhochy.org>
 */
 
## Includes ## 

require_once dirname(__FILE__).'/../schoorbs-includes/version.php';

## Main ##

// TODO: Integrate ChangeLog into the packages

system('rm -rf schoorbs-dist/tmp');

/** Sourcecode */

echo "==== BUILDING SRC PACKAGES ====\n";

system('mkdir -p schoorbs-dist/tmp/schoorbs-'.get_schoorbs_version_number());
system('cp -r *.php config.inc.php-dist robots.txt style/ schoorbs-includes/ REST/ js/ gfx/ faq schoorbs-dist/tmp/schoorbs-'
	.get_schoorbs_version_number());
// delete .svn directories
system('cd schoorbs-dist/tmp/schoorbs-'
	.get_schoorbs_version_number()
	.' && find -name ".svn" -type d | xargs rm -rf && cd -');
system('rm -f schoorbs-dist/tmp/schoorbs-'.get_schoorbs_version_number().'/config.inc.php'); 
system('cd schoorbs-dist/tmp/ && zip -r ../schoorbs-'
	.get_schoorbs_version_number()
	.'.zip schoorbs-'.get_schoorbs_version_number()
	.'/ > /dev/null && cd -');
system('cd schoorbs-dist/tmp/ && 7za a ../schoorbs-'
	.get_schoorbs_version_number()
	.'.7z schoorbs-'.get_schoorbs_version_number()
	.'/ > /dev/null && cd -');
system('cd schoorbs-dist/tmp/ && tar -zcf ../schoorbs-'
	.get_schoorbs_version_number()
	.'.tar.gz schoorbs-'.get_schoorbs_version_number()
	.'/ && cd -');
system('cd schoorbs-dist/tmp/ && tar -jcf ../schoorbs-'
	.get_schoorbs_version_number()
	.'.tar.bz2 schoorbs-'.get_schoorbs_version_number()
	.'/ && cd -');
	
/** Sourcecode Docu */

echo "==== BUILDING SRC-DOC PACKAGES ====\n";

system('rm -rf schoorbs-dist/tmp');
system('mkdir -p schoorbs-dist/tmp/');

system('./schoorbs-contrib/makedoc.php > /dev/null');
system('cp -r schoorbs-doc schoorbs-dist/tmp/schoorbs-src-docs-'.get_schoorbs_version_number());
system('cd schoorbs-dist/tmp/ && zip -r ../schoorbs-src-docs-'
	.get_schoorbs_version_number()
	.'.zip schoorbs-src-docs-'.get_schoorbs_version_number()
	.'/ > /dev/null && cd -');
system('cd schoorbs-dist/tmp/ && 7za a ../schoorbs-src-docs-'
	.get_schoorbs_version_number()
	.'.7z schoorbs-src-docs-'.get_schoorbs_version_number()
	.'/ > /dev/null && cd -');
system('cd schoorbs-dist/tmp/ && tar -zcf ../schoorbs-src-docs-'
	.get_schoorbs_version_number()
	.'.tar.gz schoorbs-src-docs-'.get_schoorbs_version_number()
	.'/ && cd -');
system('cd schoorbs-dist/tmp/ && tar -jcf ../schoorbs-src-docs-'
	.get_schoorbs_version_number()
	.'.tar.bz2 schoorbs-src-docs-'.get_schoorbs_version_number()
	.'/ && cd -');

system('rm -rf schoorbs-dist/tmp');