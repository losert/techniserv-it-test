<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;


class HomePresenter extends Nette\Application\UI\Presenter
{
    /**
     * array with consonant
     */
    public const CONSONANT = [
        'b',
        'c',
        'd',
        'f',
        'g',
        'h',
        'j',
        'k',
        'l',
        'm',
        'n',
        'p',
        'q',
        'r',
        's',
        't',
        'v',
        'w',
        'x',
        'y',
        'z'
    ];

    /**
     * array with vowels
     */
    public const VOWELS = [
        'a',
        'e',
        'i',
        'o',
        'u'
    ];

    /**
     * character to split words for translation to pig latin
     */
    public const SEPARATOR = '-';

    /**
     * character to split words for translation to english
     */
    public const SPACE = ' ';

    /**
     * additional characters for consonant
     */
    public const CONSONANT_ADD_CHARS = 'ay';

    /**
     * additional characters for vowels
     */
    public const VOWELS_ADD_CHARS = 'way';

    /**
     * render page with simply form and translate result
     *
     * @return void
     */
    public function renderDefault(): void
    {
        $orginalWord = '';
        $translateWord = '';
        $error = '';

        if (isset($_GET["word"])) {
            $orginalWord = $_GET["word"];
            if (!str_contains($orginalWord, self::SPACE)) {

                $pigLatinWords = [];
                if (!str_contains($orginalWord, self::SEPARATOR)) {
                    $pigLatinWords[] = $this->translateToPigLatin(strtolower($orginalWord));
                    $translateWord = implode('-', $pigLatinWords);
                } else {
                    $translateWord = $this->translateFromPigLatin(strtolower($orginalWord));
                }
            } else {
                $error = 'Zadejte pouze jedno slovo';
            }
        }

        $this->template->error = $error;
        $this->template->orginalWord = $orginalWord;
        $this->template->translateWord = $translateWord;
    }

    /**
     * function for translate from english to pig latin
     *
     * @param string $translateWord
     * @return string
     */
    public function translateToPigLatin(string $translateWord): string
    {
        $result = '';

        if (in_array(substr($translateWord, 0, 1), self::VOWELS)) {
            $result = $translateWord . self::SEPARATOR . self::VOWELS_ADD_CHARS;
        }
        if (in_array(substr($translateWord, 0, 1), self::CONSONANT)) {
            preg_match('/^[^aeiou]+/i', $translateWord, $matches); // Get the consonant cluster
            $beginning = $matches[0];
            $result = substr($translateWord, strlen($beginning)) . self::SEPARATOR . $beginning . self::CONSONANT_ADD_CHARS;
        }

        return $result;
    }

    /**
     * function for translate from pig latin to english
     *
     * @param string $translateWord
     * @return string
     */
    public function translateFromPigLatin(string $translateWord): string
    {
        $result = '';
        $words = explode(self::SEPARATOR, $translateWord);

        if ($words[1] == self::VOWELS_ADD_CHARS) {
            $result = $words[0];
        } else {
            $result = substr($words[1], 0, -2) . $words[0];
        }

        return $result;
    }
}
