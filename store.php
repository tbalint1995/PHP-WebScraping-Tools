<?php

class store
{
    private $baseUrl = "https://ogyei.gov.hu/gyogyszeradatbazis&action=show_details&item=",
        $dateFormat = 'Y-m-d H:i:s',
        $patterns, $content, $connection;
    public $id;

    /**
     * A konstruktor inicializálja a példányt.
     *
     * @param string $id
     * @param mysqli $connection
     */
    function __construct($id, $connection)
    {
        $this->connection = $connection;

        $this->content =  preg_replace('/\s+/', ' ',  file_get_contents($this->baseUrl . $id));

        $listitem = '/<span class="line__title">%s<\/span> <span class="line__desc">(.*?)<\/span>/';

        $this->patterns = (object)[
            "name" => '/<h3 class="gy-content__title">(.*?)<\/h3>/',
            "itemNumber" => sprintf($listitem, 'Nyilvántartási szám'),
            'ingredient' => sprintf($listitem, 'Hatóanyag'),
            'atc' => sprintf($listitem, 'ATC-kód'),
            'approvalDate' => sprintf($listitem, 'Készítmény engedélyezésének dátuma'),
        ];
    }

    /**
     * Egy részt olvas ki a weboldal tartalmából a megadott minta alapján.
     *
     * @param string $pattern
     * @return string
     */
    function readPart($pattern): string
    {
        if (preg_match($pattern, $this->content, $matches)) {
            return ($matches[1]);
        } else {
            $this->failed_data(...[...func_get_args(), $this->id]);
            return '';
        }
    }

    /**
     * Visszaadja a megadott mező értékét a weboldal tartalmából.
     *
     * @param string $field
     * @return string
     */
    function get($field): string
    {
        return $this->readPart($this->patterns->$field);
    }

    /**
     * Mentési függvény az adatbázisba.
     */
    function save(): void
    {
        $approval_date = strtotime($this->get('approvalDate')) > 0 ?
            "'" . date($this->dateFormat, strtotime($this->get('approvalDate'))) . "'" : 'null';

        mysqli_query($this->connection, "insert into list (name,	item_number,	ingredient,	atc,	approval_date) values ('{$this->get('name')}', '{$this->get('itemNumber')}', '{$this->get('ingredient')}','{$this->get('atc')}', " . $approval_date . ")");

        if ($err = mysqli_error($this->connection)) {
            $this->db_error_occured("while saving {$this->id}, description: $err");
        } else {
            print $this->get('name') . " created!\n";
        }
    }

    /**
     * A nem létező metódusok hívásait kezeli és naplózza őket.
     *
     * @param string $name
     * @param array $arguments
     */
    function __call($name, $arguments): void
    {
        @file_put_contents("error.log", date($this->dateFormat) . " | action: $name, args: " . print_r($arguments, true) . "\n", FILE_APPEND);
    }
}
