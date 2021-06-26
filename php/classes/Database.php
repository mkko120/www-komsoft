<?php //die("403 Forbidden");
//require_once '../config.php';
require_once 'DB.php';

var_dump(class_exists('Database'));
var_dump(class_exists('DB'));

$db = new Database('../../database/users.db.sqlite');
//$res = $db->execute('CREATE TABLE `Users`(
//    id INTEGER PRIMARY KEY NOT NULL,
//    username varchar(64) NOT NULL,
//    email varchar(255) NOT NULL,
//    password varchar(64) NOT NULL,
//    role TEXT);');

$res = $db->insert('Users','username, email, password, role', '"user1", "user1@example.com", "password", "user"');
var_dump($res);
$res = $db->select('*', 'Users', ' ');
var_dump($res);


var_dump($res);

while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    var_dump($row);
}


class Database {
    private $db;

    function __construct($dbname) {
        $this->db = new DB($dbname);
    }

    function execute($query) {
        return $this->db->exec($query);
    }

    function select($range, $table, $cond) {
        if (isset($cond) && !is_null($cond)) {
            $cond_sanitized = htmlentities($cond, ENT_QUOTES, 'UTF-8');
            $sql = 'SELECT ' . $range . ' FROM `' . $table . '` WHERE :cond';
            str_replace(':cond',$cond_sanitized ,$sql);
            return $this->db->query($sql);
        } else {
            return $this->db->query('SELECT ' . $range . ' FROM ' . $table);
        }
    }

    function insert($table, $fields, $values) {
        // Value must be array or string separated with ", ".
        if (gettype($fields) === 'array') {
            if (sizeof($fields) > 1) {
                $fields = join(", ", $fields);
            }else {
                $fields = join("", $fields);
            }
        }

        // Value must be array or string separated with ", ".
        if (gettype($values) === 'array') {
            if (sizeof($values) > 1) {
                $values = join(", ", $values);
            }else {
                $values = join("", $values);
            }
        }

//        $fields_sanitized = htmlentities($fields, ENT_QUOTES, 'UTF-8');
//        var_dump($fields_sanitized);
//        $values_sanitized = htmlentities($values, ENT_QUOTES, 'UTF-8');
//        var_dump($values_sanitized);
//
//        $sql = 'INSERT INTO `:table`(:fields) VALUES (:values)';
//
//        $sql = str_replace(':table', $table, $sql);
//        $sql = str_replace(':fields', $fields_sanitized, $sql);
//        $sql = str_replace(':values', $values_sanitized, $sql);
//        console_log($sql);
//        return $this->db->exec($sql);
        return $this->db->exec('INSERT INTO '.$table.'('.$fields.') VALUES ('.$values.')');
    }

    /**
     * @param $table string table you want to update
     * @param $fields mixed fields you want to update
     * @param $values mixed values you want to put in $fields
     * @param $cond string condition to update (WARNING! IF YOU DO NOT SPECIFY CORRECT CONDITION YOU COULD DAMAGE YOUR DATABASE!)
     */
    function update($table, $fields, $values, $cond) {

        // Value must be array or string separated with ", ".
        if (gettype($fields) == 'string') {
            if (strpos($fields, ' ') !== false || strpos($fields, ', ') !== false) {
                $fields = explode(", ", $fields);
            } else {
                $fields = array($fields);
            }
        }

        // Value must be array or string separated with ", ".
        if (gettype($values) == 'string') {
            if (strpos($values, ' ') !== false || strpos($values, ', ') !== false) {
                $values = explode(", ", $values);
            } else {
                $fields = array($values);
            }
        }

        if (sizeof($values) > sizeof($fields) || sizeof($values) < sizeof($fields)) {
            echo('Please specify correct arguments');
            return false;
        }


        for ($i = 0; $i < sizeof($fields); $i++) {
            $this->execute('UPDATE '.$table.' SET '.$fields[$i].' = "'.$values[$i].'" WHERE '.$cond);
        }

        return true;

    }

    function delete($table, $cond) {
        return $this->execute('DELETE FROM '.$table.' WHERE '.$cond);
    }
}

