# csob_parser
Parser pro emaily z ČSOB IB o nové transakci
Slouží k rozparsování e-mailů z Internet Bankingu ČSOB, pro automatizované zpracování přijatých plateb

# Návod k použití
Založte si email, na který budou chodit Avíza z banky. Doporučuji založit separátní e-mail pro strojové zpracování. Poté v bance nastavte zasílání e-mailů na tuto adresu.

Poté stačí přidat třídu na čtení e-mailu a parsování dat a inicializovat je.
```php
  include 'imap.php';
  include 'bank.php';
  $imap = new imap('{imap.mujserver.cz}', 'muj@email.cz', 'mojeHesloKEmailu');
  $messages = $imap->emailsToParse();
  foreach(array_reverse($messages) as $message){
      $message = bank::csob($message['message']);
      //zde by pak bylo na miste ulozit data do db
  }
```

Funkce emailsToParse() projde všechny nepřečtené emaily, načte jejich obsah do proměnné a označí je za přečtené. Je tedy dobré při zpracování přidat podmínku:
```php
if($message['header'] == 'ČSOB Info 24 - Avízo'){
}
```
Ta kontroluje, zda má e-mail správnou hlavičku. Správný formát podmínky je v demo.php.

Neručím za funkcionalitu knihoven ani za perfektní kód. Bylo to poskládáno na rychlo, ale funguje to (ke dni 14.8.2016). Pokud budou jakékoliv připomínky, či nápady na zlepšení, rád je do kódu přidám.

Za použití této knihovny a případně způsobené chyby nenesu zodpovědnost.
