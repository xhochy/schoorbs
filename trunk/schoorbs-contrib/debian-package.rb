#!/usr/bin/env ruby
##############################
## The Debian-Package tasks ##
##############################

CLEAN.include 'build-stamp'
CLEAN.include 'configure-stamp'
CLEAN.include 'debian/schoorbs'

namespace :debian do

  desc 'Build debian-source-package'
  task :source do 
    sh '/usr/bin/debuild -S -I.svn -us -uc'
  end
  task :source => [:clean, 'ChangeLog']
  
  namespace :binary do

    desc 'Build debian-package using fakeroot'
    task :fakeroot do
      sh 'dpkg-buildpackage -rfakeroot -I.svn -us -uc'
    end
    task :fakeroot => [:source]
 
    task :copyfiles do
      if ENV['DISTDIR'] == nil
        DISTDIR = File.join(Dir.pwd, 'debian', 'schoorbs')
      else
        DISTDIR = ENV['DISTDIR']
      end
    
      FileList[File.join(RELEASE_SRC_DIR, '*')].each do |file|
        FileUtils.cp_r file, DISTDIR
      end
      
      FileUtils.cp File.join('debian/config.inc.php'), DISTDIR
    end #^ :copyfiles
    
    task :placeSampeConfig do
      if ENV['DISTDIR'] == nil
        DISTDIR = File.join(Dir.pwd, 'debian', 'schoorbs')
      else
        DISTDIR = ENV['DISTDIR']
      end
      
      FileUtils.cp 'config.inc.php-dist', File.join(DISTDIR, 'config-schoorbs.example.com.php')
    end
    
    
    task :installDependencies do
      if not File.exists?('/usr/bin/phpdoc')
        sh 'pear update-all'
        sh 'pear install --alldeps PhpDocumentor'
      end
    end    
  end
  
end
