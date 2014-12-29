<?
        // $DB = sqlite_open($sqlite_path);
    $DB = new PDO("sqlite:$sqlite_path");
    if(!$DB) {
        die('Datenbankfehler');
        return $DB;
    }

    if ( !empty($_GET['delete']) ) {
            $id = $_GET['delete'];
    }

    include_once('query.php');

    /*
     Get the List of Pads
     */

    // $query = 'SELECT value FROM '.$db_table.' WHERE key LIKE \'r%\'';
    
    try {
        if ( !empty($_GET['delete']) ) {
            $stmt = $DB->prepare($delete_query);
            $key = 'pad:'.$id.':revs:%';
            $stmt->bindParam(':key', $key);
            echo $stmt->queryString;
            $stmt->execute();
        }
        if ( isset($_GET['delete_empty']) ) {
            $stmt = $DB->prepare($delete_empty_query);
            $stmt->execute();
        }
        $stmt = $DB->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    

        /*
          Print the List of Pads
         */
        while($row = $stmt->fetch()) {
            $pad_name = $row['pad'];
            $pad_revs = $row['rev'];
            echo '<tr>'."\n\t";
            echo '<td><a href="'.$pad_url.$pad_name.'" target="_blank">'.$pad_name.'</a>&nbsp;'."</td>\n\t";
            echo '<td>'.$pad_revs.'&nbsp;'."</td>\n\t";
            if( $enable_delete_options ) {
                echo '<td><a href="?delete='.$pad_name.'" id="'.$pad_name.'" class="delete_link">delete pad</a></td>';
            }
            if ( $enable_abuse_report ) {
                echo '<td><a href="#" id="'.$pad_name.'" class="dialog_link">report abuse</a></td>';
            }
            echo '</tr>'."\n";
        }
        $DB = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
?>