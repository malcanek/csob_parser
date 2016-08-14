<?php
/**
 * Description of imap
 *
 * @author Jan
 */
class imap {
    
    private $imap;
    private $server;
    private $login;
    private $password;
    
    public function __construct($server, $login, $password) {
        $this->server = $server;
        $this->login = $login;
        $this->password = $password;
    }
    
    private function imapOpen(){
        return imap_open($this->server, $this->login, $this->password);
    }
    
    private function imapClose($imap) {
        imap_close($imap);
    }


    public function readEmails(){
        $imap = $this->imapOpen();
        $numMessages = imap_num_msg($imap);
        for($i = 1; $i <= $numMessages; $i++){
            $uid = imap_uid($imap, $i);
            $body = $this->get_part($imap, $uid, "TEXT/PLAIN");
            $ret[] = iconv("windows-1250", "UTF-8", $body);
        }
        $this->imapClose($imap);
        return $ret;
    }
    
    public function emailsToParse(){
        $imap = $this->imapOpen();
        $numMessages = imap_num_msg($imap);
        $unreaded = imap_status($imap, $this->server, SA_UNSEEN);
        for($i = $numMessages; $i > ($numMessages - $unreaded->unseen); $i--){
            imap_setflag_full($imap, $i, "\\Seen", ST_UID);
            $uid = imap_uid($imap, $i);
            $header = imap_header($imap, $i);
            $ret[$i]['header'] = imap_utf8($header->subject);
            $body = $this->get_part($imap, $uid, "TEXT/PLAIN");
            $ret[$i]['message'] = iconv("windows-1250", "UTF-8", $body);
        }
        $this->imapClose($imap);
        return $ret;
    }
    
    private function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
        if (!$structure) {
               $structure = imap_fetchstructure($imap, $uid, FT_UID);
        }
        if ($structure) {
            if ($mimetype == $this->get_mime_type($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
                switch ($structure->encoding) {
                    case 3: return imap_base64($text);
                    case 4: return imap_qprint($text);
                    default: return $text;
               }
           }

            // multipart 
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    private function get_mime_type($structure) {
        $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

        if ($structure->subtype) {
           return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }
}
