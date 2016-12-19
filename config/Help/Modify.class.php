<?php

/**
 * Modify.class
 * Classe responável por alterar strings e dados no portal
 *
 * @copyright (c) 2016, Beto Noronha
 */
class Modify {

    private static $Data;
    private static $Format;

    /**
     * <b>Tranforma Names:</b> Transforma um nome com caracteres especiais, espaços, acentos ... em uma string simples(padrao URL)
     * @param STRING $Name = Uma string qualquer
     * @return STRING = $Data = String Convertida
     */
    public static function Name($Name) {
        self::$Format = array();
        self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        self::$Data = strtr(utf8_decode($Name), utf8_decode(self::$Format['a']), self::$Format['b']);
        self::$Data = strip_tags(trim(self::$Data));
        self::$Data = str_replace(' ', '_', self::$Data);
        self::$Data = str_replace(array('-----', '----', '---', '--'), '_', self::$Data);

        return strtolower(utf8_encode(self::$Data));
    }
}