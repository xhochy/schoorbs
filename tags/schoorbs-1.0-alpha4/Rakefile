#!/usr/bin/env ruby
####################################
## The main Rakefile for Schoorbs ##
####################################

## Includes ##

require 'rake/clean' 
require 'schoorbs-contrib/rake-fixes.rb'
require 'schoorbs-contrib/release.rb'
require 'schoorbs-contrib/debian-package.rb'

## Tasks ##

task :default => [:clean, :doc]

## CLI Tasks ##

desc 'Execute all unit tests for Schoorbs'
task :test do
  sh 'phpunit --coverage-html ./schoorbs-report AllTests schoorbs-tests/all.tests.php'
end

desc 'Generate the sourcecode documentation'
task :doc => 'schoorbs-doc/elementindex.html'

## clean Task ##

CLEAN.exclude 'core'
CLEAN.include 'schoorbs-dist/*'
CLEAN.include 'schoorbs-doc/*'
CLEAN.include 'schoorbs-report/'
CLEAN.include 'schoorbs-includes/Smarty/templates_c/*'
CLEAN.include 'ChangeLog'
CLEAN.exclude '.svn'

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

file 'ChangeLog' => src_doc['Files'] do
  sh './schoorbs-contrib/svn2cl-0.8/svn2cl.sh'
  sh 'cat schoorbs-contrib/ChangeLog.old_mrbs >> ChangeLog'
end

file 'schoorbs-doc/elementindex.html' => src_doc['Files'] do
  cmd = '/usr/bin/env phpdoc -q on -pp on -s on -dc Schoorbs'
  cmd+= ' --title ' + src_doc['Title'] + ' -t schoorbs-doc/ -f ' + src_doc['Files'].join(',') 
  sh cmd   
end
