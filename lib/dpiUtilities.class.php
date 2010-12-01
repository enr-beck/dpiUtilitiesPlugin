<?php

class dpiUtilities {

  /**
   *
   * @param integer $length
   * @param boolean $complex
   * @return string 
   */
  static public function generateRandomString($length = 32, $special_chars = false) {
    $key = null;

    $pool = 'qwertzuiopasdfghjklyxcvbnm0123456789QWERTZUIOPASDFGHJKLYXCVBNM';

    if ($special_chars) {
      $pool .='+#,.-*;_!"$%&/()=?';
    }

    for ($i = 0; $i < $length; $i++) {
      $key .= substr($pool, rand(0, (strlen($pool) - 1)), 1);
    }

    return $key;
  }

  /**
   * Based on the django framework slugify function
   *
   * @param string $string
   * @param boolean $keep_letter_case
   * @return string
   */
  static public function slugify($string, $keep_letter_case = false) {
    $characters = array(
        'ь' => ' ',
        'ъ' => ' ',
        'Ъ' => ' ',
        'Ь' => ' ',
        'ξ' => '3',
        'Ξ' => '3',
        'Θ' => '8',
        'θ' => '8',
        'À' => 'A',
        'А' => 'A',
        'Ą' => 'A',
        'Ā' => 'A',
        'Ά' => 'A',
        'Ã' => 'A',
        'Å' => 'A',
        'Â' => 'A',
        'Á' => 'A',
        'Α' => 'A',
        'Æ' => 'AE',
        'Ä' => 'Ae',
        'Β' => 'B',
        'Б' => 'B',
        'Ć' => 'C',
        'Ç' => 'C',
        'Ц' => 'C',
        'Č' => 'C',
        'Ч' => 'Ch',
        'Д' => 'D',
        'Ď' => 'D',
        'Δ' => 'D',
        'Ð' => 'D',
        'Ê' => 'E',
        'Ë' => 'E',
        'Е' => 'E',
        'Έ' => 'E',
        'É' => 'E',
        'Ě' => 'E',
        'È' => 'E',
        'Э' => 'E',
        'Ē' => 'E',
        'Ε' => 'E',
        'Ф' => 'F',
        'Φ' => 'F',
        'Ґ' => 'G',
        'Ģ' => 'G',
        'Г' => 'G',
        'Ğ' => 'G',
        'Η' => 'H',
        'Х' => 'H',
        'Ή' => 'H',
        'Ï' => 'I',
        'И' => 'I',
        'Í' => 'I',
        'Ì' => 'I',
        'Ι' => 'I',
        'Ί' => 'I',
        'Ϊ' => 'I',
        'İ' => 'I',
        'І' => 'I',
        'Î' => 'I',
        'Й' => 'J',
        'К' => 'K',
        'Κ' => 'K',
        'Λ' => 'L',
        'Ł' => 'L',
        'Ļ' => 'L',
        'Л' => 'L',
        'Μ' => 'M',
        'М' => 'M',
        'Ň' => 'N',
        'Ñ' => 'N',
        'Ņ' => 'N',
        'Ν' => 'N',
        'Н' => 'N',
        'Ń' => 'N',
        'Ő' => 'O',
        'О' => 'O',
        'Ø' => 'O',
        'Ô' => 'O',
        'Ό' => 'O',
        'Õ' => 'O',
        'Ò' => 'O',
        'Ο' => 'O',
        'Ö' => 'Oe',
        'Π' => 'P',
        'П' => 'P',
        'Ψ' => 'PS',
        'Ρ' => 'R',
        'Ř' => 'R',
        'Р' => 'R',
        'Ś' => 'S',
        'С' => 'S',
        'Σ' => 'S',
        'Ş' => 'S',
        'Š' => 'S',
        'Ш' => 'Sh',
        'Т' => 'T',
        'Ť' => 'T',
        'Τ' => 'T',
        'Þ' => 'TH',
        'Û' => 'U',
        'Ú' => 'U',
        'Ù' => 'U',
        'Ű' => 'U',
        'Ů' => 'U',
        'Ü' => 'Ue',
        'У' => 'U',
        'Ü' => 'Ue',
        'В' => 'V',
        'Ω' => 'W',
        'Ώ' => 'W',
        'Χ' => 'X',
        'Υ' => 'Y',
        'Ϋ' => 'Y',
        'Ý' => 'Y',
        'Ы' => 'Y',
        'Ύ' => 'Y',
        'Я' => 'Ya',
        'Є' => 'Ye',
        'Ї' => 'Yi',
        'Ё' => 'Yo',
        'Ю' => 'Yu',
        'З' => 'Z',
        'Ž' => 'Z',
        'Ż' => 'Z',
        'Ζ' => 'Z',
        'Ź' => 'Z',
        'Ж' => 'Zh',
        'ą' => 'a',
        'α' => 'a',
        'ã' => 'a',
        'а' => 'a',
        'à' => 'a',
        'ά' => 'a',
        'ā' => 'a',
        'á' => 'a',
        'â' => 'a',
        'å' => 'a',
        'ä' => 'ae',
        'æ' => 'ae',
        'б' => 'b',
        'β' => 'b',
        'ц' => 'c',
        'č' => 'c',
        'ç' => 'c',
        'ć' => 'c',
        'ч' => 'ch',
        'ð' => 'd',
        'д' => 'd',
        'δ' => 'd',
        'ď' => 'd',
        'е' => 'e',
        'ē' => 'e',
        'έ' => 'e',
        'Ę' => 'e',
        'э' => 'e',
        'ę' => 'e',
        'ě' => 'e',
        'é' => 'e',
        'ε' => 'e',
        'ë' => 'e',
        'è' => 'e',
        'ê' => 'e',
        'φ' => 'f',
        'ф' => 'f',
        'γ' => 'g',
        'г' => 'g',
        'ğ' => 'g',
        'ģ' => 'g',
        'ґ' => 'g',
        'η' => 'h',
        'ή' => 'h',
        'х' => 'h',
        'ι' => 'i',
        'и' => 'i',
        'ı' => 'i',
        'ί' => 'i',
        'ϊ' => 'i',
        'ΐ' => 'i',
        'Ī' => 'i',
        'ì' => 'i',
        'ī' => 'i',
        'ï' => 'i',
        'і' => 'i',
        'î' => 'i',
        'í' => 'i',
        'й' => 'j',
        'ķ' => 'k',
        'к' => 'k',
        'κ' => 'k',
        'Ķ' => 'k',
        'ł' => 'l',
        'ļ' => 'l',
        'λ' => 'l',
        'л' => 'l',
        'м' => 'm',
        'μ' => 'm',
        'ņ' => 'n',
        'ν' => 'n',
        'ň' => 'n',
        'н' => 'n',
        'ñ' => 'n',
        'ń' => 'n',
        'ò' => 'o',
        'ο' => 'o',
        'õ' => 'o',
        'ô' => 'o',
        'ø' => 'o',
        'Ó' => 'o',
        'ó' => 'o',
        'ό' => 'o',
        'ő' => 'o',
        'о' => 'o',
        'ö' => 'oe',
        'π' => 'p',
        'п' => 'p',
        'ψ' => 'ps',
        'ř' => 'r',
        'р' => 'r',
        'ρ' => 'r',
        'ş' => 's',
        'ś' => 's',
        'ς' => 's',
        'σ' => 's',
        'š' => 's',
        'с' => 's',
        'ш' => 'sh',
        'щ' => 'sh',
        'ß' => 'ss',
        'т' => 't',
        'τ' => 't',
        'ť' => 't',
        'þ' => 'th',
        'ú' => 'u',
        'ű' => 'u',
        'ù' => 'u',
        'û' => 'u',
        'Ū' => 'u',
        'у' => 'u',
        'ů' => 'u',
        'ū' => 'u',
        'ü' => 'ue',
        'в' => 'v',
        'ω' => 'w',
        'ώ' => 'w',
        'χ' => 'x',
        'ÿ' => 'y',
        'ý' => 'y',
        'ύ' => 'y',
        'υ' => 'y',
        'ы' => 'y',
        'ϋ' => 'y',
        'ΰ' => 'y',
        'я' => 'ya',
        'є' => 'ye',
        'ї' => 'yi',
        'ё' => 'yo',
        'ю' => 'yu',
        'ž' => 'z',
        'ź' => 'z',
        'ζ' => 'z',
        'ż' => 'z',
        'з' => 'z',
        'ж' => 'zh'
    );

    $string = strtr($string, $characters);

    $string = preg_replace('/\W/', ' ', $string);
    $string = preg_replace('/\ +/', '-', $string);
    $string = preg_replace('/\-$/', '', $string);
    $string = preg_replace('/^\-/', '', $string);

    if (!$keep_letter_case) {
      $string = strtolower($string);
    }

    return $string;
  }

  /**
   *
   * @param string $subject
   * @return string
   */
  static public function HtmlToBBCodeParser($subject) {
    $flags = PREG_SET_ORDER;

    // transform html string from multi-line to single-line
    $subject_array = explode(PHP_EOL, $subject);
    $subject = '';
    foreach ($subject_array as $line) {
      $subject .= trim($line);
    }

    /**
     * find all <a> tags
     */
    $pattern = '/<a(.*?)>(.*?)<\/(.*?)a>/';
    preg_match_all($pattern, $subject, $matchesarray, $flags);
    foreach ($matchesarray as $match) {
      $attributes = trim($match[1]);
      preg_match_all('/(\w+)=(?:["]?([^">=]*)["]?)/', $attributes, $results, $flags);
      $values = array();
      foreach ($results as $attr) {
        $values[$attr[1]] = $attr[2];
      }
      $subject = str_replace($match[0], '[url ' . $values['href'] . (isset($values['title']) ? ';' . $values['title'] : '') . ']' . $match[2] . '[/url]', $subject);
    }
    unset($matchesarray);

    /**
     * find all <img> tags
     */
    $pattern = '/<img(.*?)>/';
    preg_match_all($pattern, $subject, $matchesarray, $flags);
    foreach ($matchesarray as $match) {
      $attributes = trim($match[1]);
      $values = array();
      preg_match_all('/(\w+)=(?:["]?([^">=]*)["]?)/', $attributes, $results, $flags);
      foreach ($results as $attr) {
        $values[$attr[1]] = $attr[2];
      }
      $subject = str_replace($match[0], '[img ' . $values['src'] . ']' . (strlen(trim($values['alt'])) ? $values['alt'] : $values['title']) . '[/img]', $subject);
    }
    unset($matchesarray);

    /**
     * find script elements
     */
    $pattern = '/<script(.*?)>(.*?)<\/script>/';
    $subject = preg_replace($pattern, '[script$1]$2[/script]', $subject);

    /**
     * find all list elements
     */
    $pattern = '/<ul(.*?)>(.*?)<\/ul>/';
    $subject = preg_replace($pattern, '[list]' . PHP_EOL . '$2[/list]' . PHP_EOL, $subject);
    $pattern = '/<ol(.*?)>(.*?)<\/ol>/';
    $subject = preg_replace($pattern, '[list=1]' . PHP_EOL . '$2[/list]' . PHP_EOL, $subject);
    $pattern = '/<li(.*?)>(.*?)<\/li>/';
    $subject = preg_replace($pattern, '[*]$2[/*]' . PHP_EOL, $subject);

    /**
     * find all <i> <em> <u> <strong> <b> <del> tags
     */
    $pattern = '/<i(.*?)>(.*?)<\/i>/';
    $subject = preg_replace($pattern, '[i]$2[/i]', $subject);
    $pattern = '/<em(.*?)>(.*?)<\/em>/';
    $subject = preg_replace($pattern, '[i]$2[/i]', $subject);
    $pattern = '/<u(.*?)>(.*?)<\/u>/';
    $subject = preg_replace($pattern, '[u]$2[/u]', $subject);
    $pattern = '/<strong(.*?)>(.*?)<\/strong>/';
    $subject = preg_replace($pattern, '[b]$2[/b]', $subject);
    $pattern = '/<b(.*?)>(.*?)<\/b>/';
    $subject = preg_replace($pattern, '[b]$2[/b]', $subject);
    $pattern = '/<center(.*?)>(.*?)<\/center>/';
    $subject = preg_replace($pattern, '[center]$2[/center]', $subject);

    /**
     * find all <p> <br> tags
     */
    $pattern = '/<p(.*?)>(.*?)<\/(.*?)p>/';
    $subject = preg_replace($pattern, '$2' . PHP_EOL . PHP_EOL, $subject);
    $pattern = '/<br(.*?)>/';
    $subject = preg_replace($pattern, PHP_EOL, $subject);

    return $subject;
  }

  /**
   *
   * @param string $subject
   * @return string
   */
  static public function BBCodeToHtmlParser($subject) {
    return $subject;
  }

}
