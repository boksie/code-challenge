# Transactionele Key Value Store
Maak een eenvoudige key value store [^1]. Implementeer hiervoor `StorageInterface`.
Je mag alleen gebruik maken van PHP. Het inzetten van al bestaande applicaties zoals Redis is niet toegestaan.
Naast de basis functionaliteit voor het bewaren en ophalen van waarden, moet ook ondersteuning komen voor transacties Zie hieronder voor uitleg. 

## Randvoorwaarden
* De applicatie werkt met alleen PHP, er hoeven geen andere systemen te worden geïnstalleerd.
* Voorzien van unit -of behaviour tests
* De code is uit te voeren via de command line, of via de ingebouwde PHP webserver.

## Wijze van inleveren
Maak een aparte (prive) repositoy en stuur ons per mail de gegevens om de code in te zien.

## Achtergrond informatie over een key value store

### Basis commando's

| actie     | uitleg                                                                        |
|-----------|-------------------------------------------------------------------------------|
| GET       | Haal een reeds opgeslagen waarde op                                           |
| SET       | Bewaar een waarde met een gegeven naam                                        |
| DEL       | Verwijder de waarde                                                           |
| EXISTS    | Controleer of een waarde bestaat                                              |
| START     | Start een nieuwe transactie                                                   |
| COMMIT    | Bewaar alle gedane acties binnen de laatst gestarte transactie en sluit deze  |
| ROLLBACK  | Vergeet alle gedane acties binnen de laatst gestarte transactie en sluit deze |

### Transacties
Een transactie begint met een 'start' commando. Alle acties die daarna worden uitgevoerd wordt 'onthouden'. Een transactie wordt afgesloten met een COMMIT of ROLLBACK.
Bij een COMMIT worden alle acties die zijn uitgevoerd daadwerkelijk opgeslagen, en de huidige transactie wordt gesloten.
Bij een ROLLBACK worden alle acties die zijn uitgevoerd verwijderd, en de huidige transactie wordt gesloten.
Het is mogelijk om transacties te nesten. Wanneer je een COMMIT of ROLLBACK uitvoert, dan wordt dit altijd gedaan op de 'laatste' transactie die je hebt gestart.

### Voorbeelden
In de voorbeelden is een `storage.php` gemaakt die via de command line wordt aangesproken.

Het eerste commando set de waarde van x op 1.
Het tweede commando toont de waarde van x.
Vervolgens wordt x weer verwijderd. Het ophalen van een waarde die niet (meer) bestaat moet een foutmelding geven.
```shell script
$ storage.php SET x 1
$ storage.php GET x
1
$ storage.php DELETE x
$ storage.php GET x
ERR: Cannot find a value by the name of "x" 
```

Transactie voorbeeld waarbij deze wordt teruggedraaid. Let erop dat de waarde van x _binnen_ de transactie de 2 heeft, en na de rollback weer terug gaat naar 1.
```shell script
$ storage.php SET x 1
$ storage.php SET y 10
$ storage.php START
$ storage.php SET x 2
$ storage.php GET x
2
$ storage.php DEL y
$ storage.php GET y
ERR: Cannot find a value by the name of "y" 
$ storage.php ROLLBACK
$ storage.php GET x
1
$ storage.php GET y
10
```

Uitgebreid voorbeeld waarbij meerdere transacties worden gebruikt:
```shell script
$ storage.php SET x 1
$ storage.php GET x
$ storage.php 1
$ storage.php START           (start transactie 1)
$ storage.php SET x 2
$ storage.php GET x
$ storage.php 2
$ storage.php START           (start transactie 2)
$ storage.php SET x 3
$ storage.php GET x
$ storage.php 3
$ storage.php ROLLBACK        (sluit transactie 2)
$ storage.php GET x
$ storage.php 2
$ storage.php COMMIT          (sluit transactie 1)
$ storage.php GET x
2
```

[^1]: https://en.wikipedia.org/wiki/Key%E2%80%93value_database

