<?php

namespace Taf;

class TableQuery
{
    public $table_name = null;
    public $description = [];
    public function __construct($table_name)
    {
        $this->table_name = $table_name;
    }
    function dynamicCondition($data_condition, $operation)
    {
        if (empty($data_condition)) {
            return "";
        }
        $keyOperateurValue = array();
        foreach ($data_condition as $key => $value) {
            $keyOperateurValue[] = addslashes($key) . " " . $operation . " '" . addslashes($value) . "'";
        }
        return "where " . implode(" and ", $keyOperateurValue);
    }
    function dynamicInsert($assoc_array, $pdo)
    {
        $keys = [];
        $placeholders = [];
        $values = [];

         foreach ($assoc_array as $key => $value) {
            $keys[] = "$key"; // gérer les champs avec majuscules ou noms spéciaux
            $placeholders[] = ":val$key";
            $values[":val$key"] = $value !== '' ? $value : null;
        }

        $sql = "INSERT INTO $this->table_name (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $placeholders) . ")";

        $stmt = $pdo->prepare($sql);
        foreach ($values as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        return [$sql, $stmt];
    }


    function dynamicUpdate($assoc_array, $condition)
    {
        $keyEgalValue = array();
        foreach ($assoc_array as $key => $value) {
            if ($value == '') {
                $keyEgalValue[] = addslashes($key) . " = null";
            } else {
                $keyEgalValue[] = addslashes($key) . " = '" . addslashes($value) . "'";
            }
        }
        return "update $this->table_name set " . implode(",", $keyEgalValue) . " " . $condition;
    }
}
