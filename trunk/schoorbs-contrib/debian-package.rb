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
  task :source => [:clean, :test, 'ChangeLog']
  
  namespace :binary do

    desc 'Build debian-package using fakeroot'
    task :fakeroot do
      sh 'dpkg-buildpackage -rfakeroot -I.svn -us -uc'
    end
    task :fakeroot => [:source, :pre_release]
 
    task :copyfiles do
      if ENV['DISTDIR'] == nil
        DISTDIR = File.join(Dir.pwd, 'debian', 'schoorbs')
      else
        DISTDIR = ENV['DISTDIR']
      end
    
      puts "Copying Schoorbs files to #{DISTDIR}"
      FileList[File.join(RELEASE_SRC_DIR, '*')].each do |file|
        FileUtils.cp_r file, DISTDIR
      end
    end #^ :copyfiles
    
  end
  
end
