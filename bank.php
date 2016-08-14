<?php
/**
 * Description of bank
 *
 * @author Jan
 * @param text $message Message from bank system
 * @return array Return parsed array with values from message
 */
class bank {
    static function csob($message){
        preg_match("'dne (.*?) byla'si", $message, $match);
        $ret['date'] = $match[1];
        preg_match("'částka (.*?)[\n\t]'si", $message, $match);
        $ret['sum'] = $match[1];
        preg_match("'účtu (.*?) zaúčtována'si", $message, $match);
        $ret['toAccount'] = $match[1];
        preg_match("'z účtu (.*?)[\n\t]'si", $message, $match);
        $ret['fromAccount'] = $match[1];
        preg_match("'KS (.*?)[\n\t]'si", $message, $match);
        $ret['ks'] = $match[1];
        preg_match("'VS (.*?)[\n\t]'si", $message, $match);
        $ret['vs'] = $match[1];
        preg_match("'detaily platby:[\n\t](.*?)[\n\t]'si", $message, $match);
        $ret['detail'] = $match[1];
        if(empty($ret['detail'])){
            preg_match("'detaily transakce:[\n\t](.*?)[\n\t]'si", $message, $match);
            $ret['detail'] = $match[1];
        }
        preg_match("'zaúčtování transakce:(.*?)[\n\t]'si", $message, $match);
        $ret['rest'] = $match[1];
        preg_match("'Místo:(.*?)[\n\t]'si", $message, $match);
        $ret['place'] = $match[1];
        preg_match("'zpráva:(.*?)[\n\t]'si", $message, $match);
        $ret['message'] = $match[1];
        if(empty($ret['message'])){
            preg_match("'zpráva pro příjemce:(.*?)[\n\t]'si", $message, $match);
            $ret['message'] = $match[1];
        }
        return $ret;
    }
}
