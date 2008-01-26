#!/usr/bin/env ruby
####################################
## The main Rakefile for Schoorbs ##
####################################

## Includes ##

require 'rake/clean' 
require 'schoorbs-contrib/release.rb'

## Tasks ##

task :default => [:clean, :doc]

## CLI Tasks ##

task :test do
  sh 'phpunit --coverage-html ./schoorbs-report AllTests schoorbs-tests/all.tests.php'
end

task :doc => 'schoorbs-doc/elementindex.html'

## clean Task ##

CLEAN.exclude 'core'
CLEAN.include 'schoorbs-dist/*'
CLEAN.include 'schoorbs-doc/*'
CLEAN.include 'schoorbs-report/'
CLEAN.include 'schoorbs-includes/Smarty/templates_c/*'
CLEAN.exclude '.svn'

## Deb(ian) Package Tasks ##  

task :source_deb do 
  sh '/usr/bin/debuild -S -I.svn -us -uc'
end
task :source_deb => [:clean]

task :binary_deb_fakeroot do
  sh 'dpkg-buildpackage -rfakeroot -I.svn -us -uc'
end
task :binary_deb_fakeroot => [:source_deb]

## File Tasks ##

src_doc = {
  'Files' => FileList[
    '*.php',
    'includes/*.php',
    'schoorbs-includes/*.php',
    'schoorbs-includes/input/*.php',
    'schoorbs-includes/rest-plugins/*.php',
    'schoorbs-includes/session-plugins/*.php',
    'schoorbs-includes/authentication/*.php', 
    'schoorbs-includes/database/*.php',
    'schoorbs-tests/*.php',
    'schoorbs-tests/input-tests/*.php'
    ] - FileList['config.inc.php'],
  'Title' => '"Schoorbs Sourcecode Documentation"'
}

file 'schoorbs-doc/elementindex.html' => src_doc['Files'] do
  cmd = '/usr/bin/env phpdoc -pp on -s on -dc Schoorbs'
  cmd+= ' --title ' + src_doc['Title'] + ' -t schoorbs-doc/ -f ' + src_doc['Files'].join(',') 
  sh cmd   
end