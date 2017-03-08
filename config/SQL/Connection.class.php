<?php

/**
 * Connection.class
 * Classe de Conexão com o banco de dados Postgres
 * Retorna um valor boleano pelo método getConn
 *
 * @author Beto Noronha
 */
class Connection {

    private static $Host = P_HOST;
    private static $Port = P_PORT;
    private static $User = P_USER;
    private static $Pass = P_PASS;
    private static $Dbsa = P_DBSA;

    private static $Connect = null;

    /** Conecta com o banco de dados */
    private static function Conectar() {
        $con_string = "host=".self::$Host." port=".self::$Port." dbname=".self::$Dbsa." user=".self::$User." password=".self::$Pass;
        self::$Connect = pg_connect($con_string);

        return self::$Connect;
    }

    /** Retorna um valor boleano. */
    public static function getConn() {
        return self::Conectar();
    }

}