<?php
/**
 * Created by PhpStorm.
 * User: INPDavidYepes
 * Date: 18/01/2018
 * Time: 9:30 AM
 */
class Jobeet
{
    static public function slugify($text)
    {
        // replace all non letters or digits by -
        $text = @preg_replace('/\W+/', '-', $text);

        // trim and lowercase
        $text = strtolower(trim($text, '-'));

        return $text;
    }
}