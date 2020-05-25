# Fakertoolbox


## Beschreibung

Bei dieser Software handelt es sich um eine Erweiterung für das Open Source CMS Contao, die es erlaubt direkt im DCA
die Definition für Testdaten zu hinterlegen und dann mit einfachen Aufrufen per Faker Testdaten für einzelne Felder
oder ganze Tabellenzeilen erstellen zu lassen.

## Lizenz

Distributed under the [LGPLv3](https://spdx.org/licenses/GPL-3.0-or-later.html#licenseText) license.
See `LICENSE` for more information.


## Autor

__e@sy Solutions IT:__ Patrick Froch <info@easySolutionsIT.de>


## Support

info@easySolutionsIT.de


## Voraussetzungen

- php: >=7.2
- contao/core-bundle: >=4.4


## Installation

Die Installation ist einfach über den Contao Manager möglich, dort nach `esit/fakertoolbox` suchen und installieren.


## Einrichtung

Im DCA gibt es drei Einträge, die in das `eval`-Array eingefügt werden können:

### `fakerMethod`

`fakerMethod` gibt die Methode an, die Faker für die Erstellung der Testdaten für dieses Feld verwenden soll. Es stehen
alle Methoden zur Verfügung die Faker bietet. Eine Übersicht findet man unter:
[https://github.com/fzaninotto/Faker](https://github.com/fzaninotto/Faker)

__Beispiel:__

```php
$GLOBALS['TL_DCA']['tl_member']['fields']['firstname']['eval']['fakerMethod'] = 'firstName';
```

Erstellt einen Vornamen, wenn ein Testwert für das Feld `tl_member.firstname` erstellt werden soll.

### `fakerParameter`

Mit `fakerParameter` kann man Parameter für die Erstellungsmethode definieren, so kann man z.B. einen Zahlenbereich
eingrenzen.

__Beispiel:__

```php
$GLOBALS['TL_DCA']['tl_member']['fields']['id']['eval']['fakerMethod']      = 'numberBetween';
$GLOBALS['TL_DCA']['tl_member']['fields']['id']['eval']['fakerParameter']   = [1, 9999];
```

Gibt eine Zahl zwischen 1 und 9999 zurück, wenn ein Testwert für das Feld `tl_member.id` erstellt werden soll.

### `fakerOptional`

Mit `fakerOptional` kann die Wahrscheinlichkeit angegeben werden, mit der ein Wert erzeugt wird und was sonst als
Vorgabewert zurückgegeben wird.

__Beispiel:__

```php
$GLOBALS['TL_DCA']['tl_member']['fields']['id']['eval']['fakerMethod']      = 'firstName';
$GLOBALS['TL_DCA']['tl_member']['fields']['id']['eval']['fakerOptional']    = [0.6, ''];
```

Gibt mit einer Wahrscheinlichkeit von 60% einen Vornamen und sonst einen Leerstring zurück.

## Verwendung

### Fabrik für die Erstellung

Hat man die Einstellungen im DCA hinterlegt, kann man einfach die Testwerte erzeugen. Zunächst holt man sich die Fabrik.

```php
$factory = \Contao\System::getContainer()->get('esit_fakertoolbox.services.factories.fakerfactory');
```

Alternativ kann man den Service auch einfach per Dependency Injection beziehn.

### ContaoFaker

Nun kann man sich den eigentlichen `ContaoFaker` geben lassen, in dem man die gewünschte Tabelle übergibt. Hierbei
handelt es sich um eine Fassade, die das Sammeln der nötigen Informationen, sowie die Logik für die eigentliche
Erstellung kapselt und der einfacheren Benutzung dient.

```php
$faker = $factory->getFaker('tl_member');
```

### Testwerte

Für den Zugriff auf die Testwerte gibt es drei Wege. Man kann sich einzelne Werte geben lassen, in dem man einfach auf
die Eigenschaft zugreift. Wichtig ist, dass nur auf Felder zugegriffen werden kann, für die eine `fakerMethod`
definiert ist. Wird auf ein Feld zugegriffen, bei dem dies nicht der Fall ist, wird ein Fehler erzeugt.

__Beispiel:__

```php
$id = $faker->id;
```

Man kann sich eine ganze Tabellenzeile geben lassen. Wichtig ist, dass nur für die Felder Werte erzeugt werden, bei
denen eine `fakerMethod` definiert ist, die anderen Felder werden ignoriert.

__Beispiel:__

```php
$row = $faker->getRow();
```

Zu guter Letzt, kann man sich eine beliebige Anzahl an Tabellenzeilen geben lassen, z.B um eine Testdatenbank zu
befüllen. Es wird einfach die gewünschte Anzahl übergeben. Auch hier werden nur Testdaten für Felder erstellt, bei
denen eine `fakerMethod` definiert ist.

__Beispiel:__

```php
$rows = $faker->getRows(5);
```

## Komplettbeispiel

### Erweiterung des DCAs

In diesem Beispiel wird das DCA der Tabelle `tl_member` um ein paar Testdaten erweitert.

```php
$GLOBALS['TL_DCA']['tl_member']['fields']['id']['eval']['fakerMethod']               = 'numberBetween';
$GLOBALS['TL_DCA']['tl_member']['fields']['id']['eval']['fakerParameter']            = [1, 9999];
$GLOBALS['TL_DCA']['tl_member']['fields']['tstamp']['eval']['fakerMethod']           = 'unixTime';
$GLOBALS['TL_DCA']['tl_member']['fields']['firstname']['eval']['fakerMethod']        = 'firstName';
$GLOBALS['TL_DCA']['tl_member']['fields']['lastname']['eval']['fakerMethod']         = 'lastName';
$GLOBALS['TL_DCA']['tl_member']['fields']['dateOfBirth']['eval']['fakerMethod']      = 'unixTime';
$GLOBALS['TL_DCA']['tl_member']['fields']['company']['eval']['fakerMethod']          = 'company';
$GLOBALS['TL_DCA']['tl_member']['fields']['street']['eval']['fakerMethod']           = 'streetAddress';
$GLOBALS['TL_DCA']['tl_member']['fields']['postal']['eval']['fakerMethod']           = 'postcode';
$GLOBALS['TL_DCA']['tl_member']['fields']['city']['eval']['fakerMethod']             = 'city';
$GLOBALS['TL_DCA']['tl_member']['fields']['state']['eval']['fakerMethod']            = 'state';
$GLOBALS['TL_DCA']['tl_member']['fields']['country']['eval']['fakerMethod']          = 'country';
$GLOBALS['TL_DCA']['tl_member']['fields']['phone']['eval']['fakerMethod']            = 'phoneNumber';
$GLOBALS['TL_DCA']['tl_member']['fields']['mobile']['eval']['fakerMethod']           = 'phoneNumber';
$GLOBALS['TL_DCA']['tl_member']['fields']['mobile']['eval']['fakerOptional']         = [0.4, ''];
$GLOBALS['TL_DCA']['tl_member']['fields']['fax']['eval']['fakerMethod']              = 'phoneNumber';
$GLOBALS['TL_DCA']['tl_member']['fields']['fax']['eval']['fakerOptional']            = [0.2, ''];
$GLOBALS['TL_DCA']['tl_member']['fields']['email']['eval']['fakerMethod']            = 'email';
$GLOBALS['TL_DCA']['tl_member']['fields']['language']['eval']['fakerMethod']         = 'languageCode';
$GLOBALS['TL_DCA']['tl_member']['fields']['login']['eval']['fakerMethod']            = 'unixTime';
$GLOBALS['TL_DCA']['tl_member']['fields']['username']['eval']['fakerMethod']         = 'userName';
$GLOBALS['TL_DCA']['tl_member']['fields']['disable']['eval']['fakerMethod']          = 'boolean';
$GLOBALS['TL_DCA']['tl_member']['fields']['disable']['eval']['fakerParameter']       = [80]; // chance of getting true
$GLOBALS['TL_DCA']['tl_member']['fields']['start']['eval']['fakerMethod']            = 'unixTime';
$GLOBALS['TL_DCA']['tl_member']['fields']['stop']['eval']['fakerMethod']             = 'unixTime';
$GLOBALS['TL_DCA']['tl_member']['fields']['dateAdded']['eval']['fakerMethod']        = 'unixTime';
$GLOBALS['TL_DCA']['tl_member']['fields']['lastLogin']['eval']['fakerMethod']        = 'unixTime';
$GLOBALS['TL_DCA']['tl_member']['fields']['currentLogin']['eval']['fakerMethod']     = 'unixTime';
$GLOBALS['TL_DCA']['tl_member']['fields']['loginAttempts']['eval']['fakerMethod']    = 'numberBetween';
$GLOBALS['TL_DCA']['tl_member']['fields']['loginAttempts']['eval']['fakerParameter'] = [0, 3];
$GLOBALS['TL_DCA']['tl_member']['fields']['loginAttempts']['eval']['fakerOptional']  = [0.9, 0]; // 10% chance of 0
$GLOBALS['TL_DCA']['tl_member']['fields']['locked']['eval']['fakerMethod']           = 'unixTime';
```

### Erstellen von 50 Datensätzen

```php
$factory    = \Contao\System::getContainer()->get('esit_fakertoolbox.services.factories.fakerfactory');
$faker      = $factory->getFaker('tl_member');
$testData   = $faker->getRows(50);
```

## Running the ests

Im Verzeichnis der Erweiterung folgenden Befehl aufrufen:

```bash
build/runtests.sh
```