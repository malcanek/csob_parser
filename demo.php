<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>DEMO ČSOB</title>
    </head>
    <body>
        <?php
        include 'imap.php';
        include 'bank.php';
        $imap = new imap('{imap.mujserver.cz}', 'muj@email.cz', 'mojeHesloKEmailu');
        $messages = $imap->emailsToParse();
        foreach(array_reverse($messages) as $message){
            if($message['header'] == 'ČSOB Info 24 - Avízo'){
                $message = bank::csob($message['message']);
                //zde by pak bylo na miste ulozit data do db
            }
        }
        ?>
    </body>
</html>
