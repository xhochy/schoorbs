<?php
/**
 * Main file for the translation/language handling
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License
 * @package Schoorbs
 * @subpackage Internationalization
 */

/** Map HTTP-LangCode -> Windows-LangCode */
require_once dirname(__FILE__).'/lang_map_windows.php';
/** Map Windows-LangCode -> Windows-Codepage */
require_once dirname(__FILE__).'/winlocale_codepage_map.php';
/** Map special cases HTTP-LangCode -> Unix-LangCode */
require_once dirname(__FILE__).'/lang_map_unix.php';
/** Map HTTP-LangCode -> IBM AIX code set */
require_once dirname(__FILE__).'/aixlocale_codepage_map.php';
/** Map GNU-iconv -> IBM AIX-iconv */
require_once dirname(__FILE__).'/gnu_iconv_to_aix_iconv_codepage_map.php';
/** IBM AIX libiconv UTF-8 converters */
require_once dirname(__FILE__).'/aix_utf8_converters.php';

##############################################################################
# Language token handling

# Get a default set of language tokens, you can change this if you like
include dirname(__FILE__).'/lang.'.$default_language_tokens.'.php';

# Define the default locale here. For a list of supported
# locales on your system do "locale -a"
setlocale(LC_ALL, 'C');

# We attempt to make up a sensible locale from the HTTP_ACCEPT_LANGUAGE
# environment variable.

# First we enumerate the user's language preferences...
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) // Attempt to use $HTTP_ACCEPT_LANGUAGE only when defined.
{
  $lang_specifiers = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
  foreach ($lang_specifiers as $specifier)
  {
    if (preg_match('/([a-zA-Z\-]+);q=([0-9\.]+)/', $specifier, $matches))
    {
      $langs[$matches[1]] = $matches[2];
    }
    else if (preg_match("/([a-zA-Z\-]+)/", $specifier, $matches))
    {
      $langs[$matches[1]] = 1.0;
    }
  }
  arsort($langs,SORT_NUMERIC);
}
else // Else use the value from config.inc.php.
{
  $langs[$default_language_tokens] = 1.0;
}

# The following attempts to import a language based on what the client
# is using.

if (!$disable_automatic_language_changing)
{
  $doneit = 0;

	// Deafult german
	$locale = 'de';

  # First try for an exact match, so if the user specified en-gb, look
  # for lang.en-gb

  foreach ($langs as $lang => $qual)
  {
    $lang_file = dirname(__FILE__).'/lang.'.strtolower($lang).'.php';

    if (file_exists($lang_file))
    {
      include $lang_file;
      $doneit = 1;
      $locale = $lang;
      break;
    }
  }
  if ($doneit == 0)
  {
    # None of the user's preferred languages was available, so try to
    # find a lang file for one of the base languages, e.g. look for
    # lang.en if "en-ca" was specified.

    foreach ($langs as $lang)
    {
      $lang_file =  dirname(__FILE__).'/lang.'.strtolower(substr($lang,0,2)).'.php';

      if (file_exists($lang_file))
      {
        include $lang_file;
        $locale = $lang;
        break;
      }
    }
  }
}

##############################################################################
# Locale handling

$windows_locale = "eng";

# 2003/11/09 JF Larvoire: Help new admins understand what to do in case the iconv error occurs...
if (($unicode_encoding) && (!function_exists('iconv')))
{
  exit('
<P>
  <B>Error:</B> The iconv module, which provides PHP support for Unicode, is not
installed on your system.</P>
<P>Unicode gives MRBS the ability to easily support languages other than
English. Without Unicode, support for non-English-speaking users will be crippled.</P>
<P>To fix this error, do one of the following:</P>
<UL>
<LI><P>Install and enable the iconv module.<BR>
On a Windows server, enable php_iconv.dll in %windir%\php.ini, and make sure both
%phpdir%\dlls\iconv.dll and %phpdir%\extensions\php_iconv.dll are in the
path. One way to do this is to copy these two files to %windir%.<BR>
On a Unix server, recompile your PHP module with the appropriate
option for enabling the iconv extension. Consult your PHP server
documentation for more information about enabling iconv support.</P></LI>
<LI><P>Disable Unicode support by modifying config.inc.php and setting the
variable $unicode_encoding to 0. If your MRBS installation is on a shared
host, then this may be your only option.</P></LI>
</UL>
');
}

if ($override_locale != "")
{
  if (setlocale(LC_ALL,$override_locale) == FALSE)
  {
    $locale_warning = "Server failed to set locale to
 \"".$override_locale."\" (Override locale)";
  }
  $windows_locale = $override_locale;
}
else
{
  $server_os = get_server_os();

  if ($server_os == "windows")
  {
    if ($lang_map_windows[strtolower($locale)])
    {
      if (setlocale(LC_ALL, $lang_map_windows[strtolower($locale)]) == FALSE)
      {
        $locale_warning = "Server failed to set locale to
 \"".$lang_map_windows[strtolower($locale)]."\" (Windows)";
      }
      $windows_locale = $lang_map_windows[strtolower($locale)];
    }
    else
    {
      $locale_warning = "Server failed to map browser language
 \"".$locale."\" to a Windows locale specifier";
    }
  }
  else if ($server_os == "unix" 
  	|| $server_os == "linux" 
  	|| $server_os == "sunos"
	|| $server_os == "bsd"
	|| $server_os == "aix" 
	|| $server_os == "macosx")
  {
    if (strlen($locale) == 2)
    {
      # Convert locale=xx to xx_XX; this is not correct for some locales???
      $locale = strtolower($locale)."_".strtoupper($locale);
    }
    else
    {
      # Convert locale=xx-xX or xx_Xx or xx_XxXx (etc.) to xx_XX[XX]; this is highly
      # dependent on the machine's installed locales
      $locale = strtolower(substr($locale,0,2))."_".strtoupper(substr($locale,3));
    }
    if (isset($lang_map_unix[$locale]) && ($lang_map_unix[$locale]))
    {
      $locale = $lang_map_unix[$locale];
    }
    if ($unicode_encoding)
    {
      if($server_os == "sunos")
        $locale.= ".UTF-8";
      elseif ($server_os != "aix") 
      {
      	// On IBM AIX, do not add ".utf-8" as this yields an invalid
        // locale name
        $locale .= ".utf-8";
      }
    }
    if (setlocale(LC_ALL, $locale) == FALSE)
    {
      $locale_warning = "Server failed to set locale to \"".$locale."\"
(Unix)";
    }
  }
}

function get_server_os()
{
  if (stristr(PHP_OS,"Darwin"))
    return "macosx";
  else if (stristr(PHP_OS, "WIN"))
    return "windows";
  else if (stristr(PHP_OS, "Linux"))
    return "linux";
  else if(stristr(PHP_OS, 'BSD'))
    return 'bsd';
  else if(stristr(PHP_OS, 'SunOS'))
    return 'sunos';
  else if(stristr(PHP_OS, 'AIX'))
    return 'aix';
  else
    return "unsupported";
}

// Translates a GNU libiconv character encoding name to its corresponding IBM AIX libiconv character
// encoding name. Returns FALSE if character encoding name is unknown.
function get_aix_character_encoding($character_encoding)
{
  global $gnu_iconv_to_aix_iconv_codepage_map;

  // Check arguments
  if ($character_encoding == NULL ||
      !is_string($character_encoding) ||
      empty($character_encoding))
  {
    return FALSE;
  }

  // Convert character encoding name to lowercase
  $character_encoding = strtolower($character_encoding);

  // Check that we know of an IBM AIX libiconv character encoding name equivalent for this character encoding name
  if (!array_key_exists($character_encoding, $gnu_iconv_to_aix_iconv_codepage_map))
  {
    return FALSE;
  }

  return $gnu_iconv_to_aix_iconv_codepage_map[$character_encoding];
}

function get_vocab_utf8_aix($tag)
{
  global $aix_utf8_converters, $vocab;

  // Attempt to translate character encoding name
  $aix_character_set = get_aix_character_encoding($vocab['charset']);

  // Check if character encoding name translation was successful
  if ($aix_character_set === FALSE)
  {
    // Unsuccessful; just use the original character encoding name
    $aix_character_set = $vocab['charset'];

  }
  else
  {
    // Successful; check that a corresponding UTF-8 converter exists
    if (!in_array($aix_character_set, $aix_utf8_converters, TRUE))
    {
      // Corresponding UTF-8 converter does not exist; just use the original character encoding name
      $aix_character_set = $vocab['charset'];

    }
    else
    {
      // Success; the translated character encoding name is ready to use
    }
  }

  return iconv($aix_character_set, 'UTF-8', $vocab[$tag]);
}


# Get a vocab item, in UTF-8 or a local encoding, depending on
# the setting of $unicode_encoding
function get_vocab($tag)
{
  global $vocab, $unicode_encoding;

  if ($unicode_encoding && (strcasecmp($vocab["charset"], "utf-8") != 0))
  {
    if ((get_server_os() == 'aix') &&
        (strcasecmp(ICONV_IMPL, 'unknown') == 0) &&
        (strcasecmp(ICONV_VERSION, 'unknown') == 0))
    {
      $translated = get_vocab_utf8_aix($tag);
    }
    else
    {
      $translated = iconv($vocab["charset"],"utf-8",$vocab[$tag]);
    }
  }
  else
  {
    $translated = $vocab[$tag];
  }
  return $translated;
}

// AIX version of utf8_convert(); needed as locales won't give us UTF-8
// NOTE: Should ONLY be called with input encoded in the default code set of the current locale!
// NOTE: Uses the LC_TIME category for determining the current locale setting, so should preferrably be used on date/time input only!
function utf8_convert_aix($string)
{
  global $aixlocale_codepage_map, $aix_utf8_converters, $unicode_encoding;

  // Retrieve current locale setting
  $aix_locale = setlocale(LC_TIME, '0');

  if ($aix_locale === FALSE)
  {
    // Locale setting could not be retrieved; return string unchanged
    return $string;
  }

  if (!array_key_exists($aix_locale, $aixlocale_codepage_map))
  {
    // Default code page of locale could not be found; return string unchanged
    return $string;
  }

  $aix_codepage = $aixlocale_codepage_map[$aix_locale];

  if (!in_array($aix_codepage, $aix_utf8_converters, TRUE))
  {
    // No suitable UTF-8 converter was found for this code page; return string unchanged
    return $string;
  }

  // Convert string to UTF-8
  $aix_string = iconv($aix_codepage, 'UTF-8', $string);

  // Default to original string if conversion failed
  $string = ($aix_string === FALSE) ? $string : $aix_string;

  return $string;
}

function utf8_convert_from_locale($string)
{
  global $windows_locale, $unicode_encoding, $winlocale_codepage_map;

  if ($unicode_encoding)
  {
    if (get_server_os() == "windows")
    {
      if ($winlocale_codepage_map[$windows_locale])
      {
        $string = iconv($winlocale_codepage_map[$windows_locale],"utf-8",
                        $string);
      }
    }
    else if (get_server_os() == "aix")
    {
      $string = utf8_convert_aix($string);
    }
  }
  return $string;
}
  
function utf8_strftime($format, $time)
{
  # %p doesn't actually work in some locales, we have to patch it up ourselves
  if (preg_match('/%p/', $format))
  {
    $ampm = strftime('%p', $time);
    if ($ampm == '')
    {
      $ampm = date('a',$time);
    }

    $format = preg_replace('/%p/', $ampm, $format);
  }

  $result = strftime($format,$time);
  return utf8_convert_from_locale($result);
}
