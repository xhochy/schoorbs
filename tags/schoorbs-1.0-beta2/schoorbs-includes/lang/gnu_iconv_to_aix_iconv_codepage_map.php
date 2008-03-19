<?php
/**
 * GNU iconv code set to IBM AIX libiconv code set table
 * Keys of this table should be in lowercase, and searches should be performed 
 * using lowercase!
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @package Schoorbs
 * @subpackage Internationalization
 */

$gnu_iconv_to_aix_iconv_codepage_map = array(
  // "iso-8859-[1-9]" --> "ISO8859-[1-9]" according to 
  // http://publibn.boulder.ibm.com/doc_link/en_US/a_doc_lib/libs/basetrf2/setlocale.htm
  'iso-8859-1' => 'ISO8859-1',
  'iso-8859-2' => 'ISO8859-2',
  'iso-8859-3' => 'ISO8859-3',
  'iso-8859-4' => 'ISO8859-4',
  'iso-8859-5' => 'ISO8859-5',
  'iso-8859-6' => 'ISO8859-6',
  'iso-8859-7' => 'ISO8859-7',
  'iso-8859-8' => 'ISO8859-8',
  'iso-8859-9' => 'ISO8859-9',

  // "big5" --> "IBM-eucTW" according to 
  // http://kadesh.cepba.upc.es/mancpp/classref/ref/ITranscoder_DSC.htm
  'big5' => 'IBM-eucTW',

  // "big-5" --> "IBM-eucTW" (see above)
  'big-5' => 'IBM-eucTW'
);
