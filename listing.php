<?php

class listing
{

    private $connection;

    function __construct()
    {
        // Adatbázis kapcsolat inicializálása a connection.php fájlból.
        $this->connection = include(__DIR__ . '/connection.php');
    }

    function searchView()
    {
        // Kereső nézet betöltése.
        require(__DIR__ . "/views/search.php");
    }

    function search(): void
    {
        // Keresési művelet végrehajtása.
        $_SERVER["REQUEST_METHOD"] === 'POST' or call_user_func(function () {
            http_response_code(405);
            exit;
        });

        header("Content-Type: application/json;");

        $inputs = json_decode(file_get_contents("php://input"));

        $kw = mysqli_real_escape_string($this->connection, trim($inputs->kw));

        $searchparams = "";

        if (mb_strlen($kw, 'UTF-8') > 2) {
            $searchparams = "where";
            $searchables = ['name', 'ingredient', 'atc'];

            foreach ($searchables as $field) {
                $searchparams .= " " . "`$field` like '%$kw%' or";
            }
            $searchparams = rtrim($searchparams, "or");
        }

        $results = [];

        $sql = mysqli_query($this->connection, "select * from list $searchparams");
        while ($data = mysqli_fetch_object($sql)) {
            $results[] = $data;
        }

        print json_encode($results);
    }
}
