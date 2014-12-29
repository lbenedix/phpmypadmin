<?
    $get_query = 'SELECT 
            --*,
                cast(
                    substr(
                        key,
                        revpos
                    )
                    AS integer
                )
                AS rev,
                replace(
                    rtrim(
                        replace(
                            key, "pad:", ""
                        ),"0123456789"
                    ), ":revs:", ""
                ) as pad
            FROM (
                SELECT
                    key,
                    instr(key, ":revs:")+6 AS revpos
                FROM
                    store
                WHERE
                    key LIKE "pad:%:revs:%"
            )';


    $list = '>=';
    if (isset($_GET['list']) && $_GET['list']=='empty') { $list = '='; }

    $order = 'ASC';
    if (isset($_GET['order']) && $_GET['order']=='desc') { $order = 'DESC'; }

    $by = 'pad';
    if (isset($_GET['by']) && $_GET['by']=='rev') { $by = 'rev'; }

    $query = $get_query.'
        GROUP BY pad HAVING max(rev) '.$list.' 0 ORDER BY '.$by.' '.$order.';
    ';

    // $delete_query = "DELETE FROM store WHERE key LIKE ".$DB->quote('pad:'.$id.':revs:0');
    $delete_query = "DELETE FROM store WHERE key LIKE :key";

    $delete_empty_query = 'DELETE FROM store WHERE key IN 
        (SELECT key FROM (
            SELECT 
                key,
                cast(substr(key,revpos)AS integer) AS rev,
                replace(rtrim(replace(key, "pad:", ""),"0123456789"), ":revs:", "") as pad
            FROM (
                SELECT
                    key,
                    instr(key, ":revs:")+6 AS revpos
                FROM
                    store
                WHERE
                    key LIKE "pad:%:revs:%"
            ) 
        GROUP BY pad having max(rev) = 0
        ))';
?>