#!/usr/bin/env ruby
######################
## The Release task ##
######################

## Load version number ##

File.open(File.join('schoorbs-includes', 'version.txt'), 'r') do |f|
  SCHOORBS_VERSION = f.read()
end

## Configuration ##

RELEASE_TMP_DIR = File.join(Dir.pwd, 'schoorbs-dist', 'tmp')
RELEASE_SRC_DIR = File.join(RELEASE_TMP_DIR, 'schoorbs')
RELEASE_DIST_DIR = File.join(Dir.pwd, 'schoorbs-dist', 'schoorbs-' + SCHOORBS_VERSION)
RELEASE_SRC_FILES = Dir['*'] - ['schoorbs-contrib', 'schoorbs-tests', 'schoorbs-report', 'schoorbs-dist', 'schoorbs-doc', 'Rakefile', 'config.inc.php', 'debian', 'build-stamp', 'configure-stamp']

## Includes ##

require 'fileutils'
require File.join(File.dirname(__FILE__), 'packr', '/packr.rb')
require File.join(File.dirname(__FILE__), 'rainpress', '/packer.rb')

## CLEAN targets ##

CLEAN.include RELEASE_TMP_DIR
CLEAN.include RELEASE_DIST_DIR

## Task dependencies ##

task :release => [:clean, :doc, :test, directory(RELEASE_DIST_DIR), :pre_release]
task :pre_release => [directory(RELEASE_TMP_DIR), directory(File.join(RELEASE_TMP_DIR, 'schoorbs')), 'LICENSE', 'ChangeLog']

## Tasks ##

task :pre_release do 
  puts '# Pepraring Source'
  
  puts '## Copying files'
  FileUtils.cp_r RELEASE_SRC_FILES, RELEASE_SRC_DIR
  FileUtils.cp_r 'schoorbs-doc', RELEASE_TMP_DIR
  
  puts '## Removing SVN directories'
  Dir[File.join(RELEASE_SRC_DIR, '**','.svn')].each do |d|
    FileUtils.rm_r d, :force => true
  end
  Dir[File.join(RELEASE_TMP_DIR, 'schoorbs-doc', '**','.svn')].each do |d|
    FileUtils.rm_rf d  
  end
  
  puts '## Compressing Javascript files'
  dir = File.join(RELEASE_SRC_DIR, 'schoorbs-misc', 'js')
  files = FileList[File.join(dir, '*.js')] - FileList[File.join(dir, '*pack*.js')] 
  files.each do |js|
      data = File.read(js)
      File.open(js, 'w') do |f|
        f.write(Packr.pack(data, :base62 => true))
      end
  end
  
  #puts '## Compressing CSS files'
  #packer = Rainpress::Packer.new
  #FileList[File.join(RELEASE_SRC_DIR, 'schoorbs-misc', 'style', '*.css')].each do |css|
  #  data = packer.compress(File.read(css))
  #  File.open(css, 'w') do |f|
  #    f.write(data)
  #  end
  #end
  
end

=begin
Software Requirements for making a release:

 -> zip commandline utility (ZIP software is spreaded on nearly every PC)
 -> 7z commandline utility (very good compression, a lot smaller than zip)
 ** We do not use tar since we don't need any file attributes
 -> subversion (this needs to be a working copy)
 -> xsltproc (for generating the changelog)

releases are saved in schoorbs-dist/schoorbs-<release-short-name>/*
=end

desc 'Build release packages (not including deb\'s)'
task :release do
  working_dir = getwd()

  puts '# Make the source documentation archive'
  puts '## Making the zip-archive'
  chdir RELEASE_TMP_DIR
  sh 'zip -9 -q -r ' + File.join(RELEASE_DIST_DIR, 'schoorbs-doc-' + SCHOORBS_VERSION + '.zip') + ' schoorbs-doc'
  puts '## Making the 7z-archive'
  sh '7zr -t7z -bd -m0=lzma -mx=9 -mfb=64 -md=32m -ms=on a ' + File.join(RELEASE_DIST_DIR, 'schoorbs-doc-' + SCHOORBS_VERSION + '.7z') + ' schoorbs-doc | grep -v Compressing'
  chdir working_dir
  
  puts '# Make the source archive'
  puts '## Making the zip-archive'
  chdir RELEASE_TMP_DIR
  sh 'zip -9 -q -r ' + File.join(RELEASE_DIST_DIR, 'schoorbs-' + SCHOORBS_VERSION + '.zip') + ' schoorbs'
  puts '## Making the 7z-archive'
  sh '7zr -t7z -bd -m0=lzma -mx=9 -mfb=64 -md=32m -ms=on a ' + File.join(RELEASE_DIST_DIR, 'schoorbs-' + SCHOORBS_VERSION + '.7z') + ' schoorbs | grep -v Compressing'
  chdir working_dir
end

## File Tasks ##

CLEAN.include 'LICENSE'
file 'LICENSE' => File.join('schoorbs-contrib', 'LICENSE_GPL') do
  FileUtils.cp File.join('schoorbs-contrib', 'LICENSE_GPL'), 'LICENSE'
end
