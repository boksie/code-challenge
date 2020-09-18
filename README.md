# Transactionele Key Value Store
Maak een eenvoudige key value store [^1]. Implementeer hiervoor `StorageInterface`.
Je mag alleen gebruik maken van PHP. Het inzetten van al bestaande applicaties zoals Redis is niet toegestaan.
Naast de basis functionaliteit voor het bewaren en ophalen van waarden, moet ook ondersteuning komen voor transacties Zie hieronder voor uitleg. 

## Randvoorwaarden
* De applicatie werkt met alleen PHP, er hoeven geen andere systemen te worden geïnstalleerd.
* Voorzien van unit -of behaviour tests.
* Gebruik van opensource libraries is toegestaan.
* De code is uit te voeren via de command line.

## Wijze van inleveren
Maak een aparte (prive) repositoy en stuur ons per mail de gegevens om de code in te zien.

## Achtergrond informatie over een key value store

### Basis commando's

| actie     | uitleg                                                                        |
|-----------|-------------------------------------------------------------------------------|
| GET       | Haal een reeds opgeslagen waarde op                                           |
| SET       | Bewaar een waarde met een gegeven naam                                        |
| DEL       | Verwijder de waarde                                                           |
| START     | Start een nieuwe transactie                                                   |
| COMMIT    | Bewaar alle gedane acties binnen de laatst gestarte transactie en sluit deze  |
| ROLLBACK  | Vergeet alle gedane acties binnen de laatst gestarte transactie en sluit deze |

### Transacties
Een transactie begint met een 'start' commando. Alle acties die daarna worden uitgevoerd wordt 'onthouden'. Een transactie wordt afgesloten met een COMMIT of ROLLBACK.
Bij een COMMIT worden alle acties die zijn uitgevoerd daadwerkelijk opgeslagen, en de _huidige_ transactie wordt gesloten.
Bij een ROLLBACK worden alle acties die zijn uitgevoerd verwijderd, en de _huidige_ transactie wordt gesloten.
Het is mogelijk om transacties te nesten. Wanneer je een COMMIT of ROLLBACK uitvoert, dan wordt dit altijd gedaan op de 'laatste' transactie die je hebt gestart.


### Voorbeelden
In de voorbeelden is een `bin/console` gemaakt die via de command line wordt aangesproken. Uiteraard zijn er meer scenario's mogelijk. Onderstaande voorbeelden dienen als illustratie.

Het eerste commando set de waarde van x op 1.
Het tweede commando toont de waarde van x.
Vervolgens wordt x weer verwijderd. Het ophalen van een waarde die niet (meer) bestaat moet een foutmelding geven.
```shell script
$ bin/console SET x 1
$ bin/console GET x
1
$ bin/console DELETE x
$ bin/console GET x
ERR: Cannot find a value by the name of "x" 
```

Transactie voorbeeld waarbij deze wordt teruggedraaid. Let erop dat de waarde van x _binnen_ de transactie de 2 heeft, en na de rollback weer terug gaat naar 1.
```shell script
$ bin/console SET x 1
$ bin/console SET y 10
$ bin/console START
$ bin/console SET x 2
$ bin/console GET x
2
$ bin/console DEL y
$ bin/console GET y
ERR: Cannot find a value by the name of "y" 
$ bin/console ROLLBACK
$ bin/console GET x
1
$ bin/console GET y
10
```

Nog een transactie voorbeeld:
```shell script
$ bin/console SET x 5
$ bin/console START
$ bin/console GET x
5
$ bin/console SET x 3
$ bin/console START
$ bin/console SET x 2
$ bin/console COMMIT
$ bin/console ROLLBACK
$ bin/console GET x
5
```

Uitgebreid voorbeeld waarbij meerdere transacties worden gebruikt:
```shell script
$ bin/console SET x 1
$ bin/console GET x
$ bin/console 1
$ bin/console START           (start transactie 1)
$ bin/console SET x 2
$ bin/console GET x
$ bin/console 2
$ bin/console START           (start transactie 2)
$ bin/console SET x 3
$ bin/console GET x
$ bin/console 3
$ bin/console ROLLBACK        (sluit transactie 2)
$ bin/console GET x
$ bin/console 2
$ bin/console COMMIT          (sluit transactie 1)
$ bin/console GET x
2
```

[^1]: https://en.wikipedia.org/wiki/Key%E2%80%93value_database

