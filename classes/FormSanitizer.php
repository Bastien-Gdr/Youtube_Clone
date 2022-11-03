<?php

class FormSanitizer{

    public static function sanitizeFormString($inputText){

        $inputText = strip_tags($inputText); // supprime les balises HTML
        $inputText = str_replace(" ","",$inputText); // supprime les espaces
        $inputText = strtolower($inputText); // met en minuscule
        $inputText = ucfirst($inputText); // première lettre en majuscule
        return $inputText;
    }

    public static function sanitizeFormUsername($inputText){

        $inputText = strip_tags($inputText); // supprime les balises HTML
        $inputText = str_replace(" ","",$inputText); // supprime les espaces

        return $inputText;
    }

    public static function sanitizeFormPassword($inputText){

        $inputText = strip_tags($inputText); // supprime les balises HTML

        return $inputText;
    }

    public static function sanitizeFormEmail($inputText){

        $inputText = strip_tags($inputText); // supprime les balises HTML
        $inputText = str_replace(" ","",$inputText); // supprime les espaces

        return $inputText;
    }

}