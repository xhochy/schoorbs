#!/usr/bin/env ruby
####################################
## The main Rakefile for Schoorbs ##
####################################

## Includes ##

require 'rake/clean'

## Tasks ##

task :default => :clean

## CLI Tasks ##

task :test do
  sh '/usr/bin/env phpunit AllTests schoorbs-tests/all.tests.php'
end

## clean Task ##

CLEAN.exclude 'core'
CLEAN.include 'schoorbs-dist/*'
CLEAN.include 'schoorbs-doc/*'
CLEAN.include 'schoorbs-includes/Smarty/templates_c/*'
CLEAN.exclude '.svn'