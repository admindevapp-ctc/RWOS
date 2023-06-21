<?php

class CTC_CSV
{
    var $file_name;
    var $use_csv_header = true;
    var $field_separate_char = ',';
    var $field_enclose_char = '"';
    var $field_escape_char = '\\"';
    var $line_enclose_char = "'\\r\\n'";

    function import()
    {
        include "../db/conn.inc";
        $ctcdb = new ctcdb();

    //echo "Header" .$this->use_csv_header;

        $sql = "LOAD DATA LOCAL INFILE ".$ctcdb->db->quote($this->file_name).
               " INTO TABLE `".$this->table_name.
               "` FIELDS TERMINATED BY '" . $this->field_separate_char.
               "' OPTIONALLY ENCLOSED BY '" . $this->field_enclose_char .
               "' ESCAPED BY '".$this->field_escape_char .
               "' LINES TERMINATED BY " . $this->line_enclose_char .
               ($this->use_csv_header ? " IGNORE 1 LINES " : "");

       //echo $sql ;
        $ctcdb->db->query($sql);  

    }

}
