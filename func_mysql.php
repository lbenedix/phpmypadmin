<?
function listAllMysql() {
    $DB = mysql_connect($db_host, $db_user, $db_password, $db_database);
    if(!$DB) {
        die('can\'t connect to database: '.mysql_error());
    }

    include_once('query.php');

    /*
     Get the List of Pads
     */
    
    // $query = 'SELECT `value` FROM '.$db_database.'.'.$db_table.' WHERE `key` LIKE \'r%\'';
    
    $result = mysql_query($query);
    if (!$result) {
        die('error in query: ' . mysql_error());
    }
    
    /*
      Print the List of Pads
     */     
    while($row = mysql_fetch_assoc($result)){
        $pad_name = $row['value'];
        $pad_name = substr($pad_name, 1,strlen($pad_name)-2);
        echo '<tr>'."\n\t";
        echo '<td><a href="'.$pad_url.$pad_name.'">'.$pad_name.'</a>&nbsp;'."</td>\n\t";
        echo '<td>'.$pad_revs.'&nbsp;'."</td>\n\t";
        if( $enable_delete_options ) {
            echo '<td><a href="?delete='.$pad_name.'" id="'.$pad_name.'" class="delete_link">delete pad</a></td>';
        }
        if ( $enable_abuse_report ) {
            echo '<td><a href="#" id="'.$pad_name.'" class="dialog_link">report abuse</a></td>';
        }
        echo '</tr>'."\n";
    }
    echo '</table>';

    //close DB_connection
    mysql_close($DB);
}
?>