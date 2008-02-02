#!/usr/bin/env ruby
##############################
## Fix up some bugs in Rake ##
##############################

# Fix directory Task
alias :original_directory :directory
def directory(dir)
  original_directory dir
  Rake::Task[dir]
end

