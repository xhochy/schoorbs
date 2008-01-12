#!/usr/bin/env ruby
######################
## The Release task ##
######################

require 'fileutils'
require File.join(File.dirname(__FILE__), 'packr', '/packr.rb')
require File.join(File.dirname(__FILE__), 'rainpress', '/packer.rb')

task :release => [:doc, :test]

=begin
Software Requirements for making a release:

 -> zip commandline utility (ZIP software is spreaded on nearly every PC)
 -> 7z commandline utility (very good compression, a lot smaller than zip)
 ** We do not use tar since we don't need any file attributes
 -> subversion (this needs to be a working copy)
 -> xsltproc (for generating the changelog)

releases are saved in schoorbs-dist/schoorbs-<release-short-name>/*
=end

task :release do
  ## Load version number
  version = '1.0'
  File.open(File.join('schoorbs-includes', 'version.txt'), 'r') do |f|
    version = f.read()
  end
  
  puts '# Remove already existing releases with the same version'
  FileUtils.rm_rf File.join('schoorbs-dist', 'schoorbs-' + version)
  
  puts '# Create output directory'
  FileUtils.mkdir_p File.join('schoorbs-dist', 'tmp', 'schoorbs')
  FileUtils.mkdir_p File.join('schoorbs-dist', 'schoorbs-' + version)
  
  puts '# Make the source documentation archive'
  FileUtils.cp_r 'schoorbs-doc', File.join('schoorbs-dist', 'tmp')
  puts '## Remove SVN directories'
  Dir[File.join('schoorbs-dist', 'schoorbs-report', 'tmp', 'schoorbs-doc', '**','.svn')].each do |d|
    FileUtils.rm_rf d  
  end
  working_dir = getwd()
  puts '## Making the zip-archive'
  chdir File.join('schoorbs-dist', 'tmp')
  sh 'zip -9 -q -r ' + File.join('..', 'schoorbs-' + version, 'schoorbs-doc-' + version + '.zip') + ' schoorbs-doc'
  puts '## Making the 7z-archive'
  sh '7zr -t7z -bd -m0=lzma -mx=9 -mfb=64 -md=32m -ms=on a ' + File.join('..', 'schoorbs-' + version, 'schoorbs-doc-' + version + '.7z') + ' schoorbs-doc'
  chdir working_dir
  
  puts '# Building the Changelog'
  sh './schoorbs-contrib/svn2cl-0.8/svn2cl.sh'
  sh 'cat schoorbs-contrib/ChangeLog.old_mrbs >> ChangeLog'
  
  puts '# Copying the LICENSE file to the root directory'
  FileUtils.cp File.join('schoorbs-contrib', 'LICENSE_GPL'), 'LICENSE'
  
  puts '# Make the source archive'
  files = Dir['*'] - ['schoorbs-contrib', 'schoorbs-tests', 'schoorbs-dist', 'schoorbs-doc', 'Rakefile', 'config.inc.php']
  FileUtils.cp_r files.to_a, File.join('schoorbs-dist', 'tmp', 'schoorbs')
  puts '## Remove SVN directories'
  Dir[File.join('schoorbs-dist', 'tmp', 'schoorbs', '**','.svn')].each do |d|
    FileUtils.rm_rf d  
  end
  puts '## Compressing Javascript files'
  dir = File.join('schoorbs-dist', 'tmp', 'schoorbs', 'schoorbs-misc', 'js')
  files = FileList[File.join(dir, '*.js')] - FileList[File.join(dir, '*pack*.js')] 
  files.each do |js|
      data = File.read(js)
      File.open(js, 'w') do |f|
        f.write(Packr.pack(data, :base62 => true))
      end
  end
  working_dir = getwd()
  puts '## Compressing CSS files'
  packer = Rainpress::Packer.new
  FileList[File.join('schoorbs-dist', 'tmp', 'schoorbs', 'schoorbs-misc', 'style', '*.css')].each do |css|
    data = packer.compress(File.read(css))
    File.open(css, 'w') do |f|
      f.write(data)
    end
  end
  puts '## Making the zip-archive'
  chdir File.join('schoorbs-dist', 'tmp')
  sh 'zip -9 -q -r ' + File.join('..', 'schoorbs-' + version, 'schoorbs-' + version + '.zip') + ' schoorbs'
  puts '## Making the 7z-archive'
  sh '7zr -t7z -bd -m0=lzma -mx=9 -mfb=64 -md=32m -ms=on a ' + File.join('..', 'schoorbs-' + version, 'schoorbs-' + version + '.7z') + ' schoorbs'
  chdir working_dir
  
  puts '# Clean up'
  FileUtils.rm_r File.join('schoorbs-dist', 'tmp'), :force => true
  FileUtils.rm ['ChangeLog', 'LICENSE'], :force => true
end
