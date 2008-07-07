#!/usr/bin/env ruby
############################
## The localization tasks ##
############################

require 'gettext/rgettext'
require 'gettext/poparser'

CLEAN.include 'schoorbs-includes/lang/*.po-xml'

$langfiles_output = []

FileList['schoorbs-includes/lang/*.po'].each do |in_file|
  dest_file = in_file + '-xml'
  $langfiles_output << dest_file
  
  file dest_file => in_file
end

task :langfiles => $langfiles_output

# Rule how to build po-xml's from po's
rule '.po-xml' => '.po' do |t|
  # Parse the input po-file
  data = MOFile.new
  str = File.open(t.source).read
  parser = GetText::PoParser.new
  parser.parse(str, data)
  
  # Print the file header
  file = File.open(t.name, 'w')
  file.puts '<?xml version="1.0" encoding="UTF-8"?>'
  file.puts '<translation>'
  
  data.each do |string, trans|
    next if string == ''
    file.puts '  <string id="' + string + '" translated="' + trans + '" />'
  end
  
  # Print the file footer
  file.puts '</translation>'
  file.close
end
