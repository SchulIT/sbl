---
sidebar_position: 3
---

# Konfigurationsdatei

Die Vorlage für die Konfigurationsdatei befindet sich in der Datei `.env`. Von dieser Datei muss eine Kopie `.env.local` erzeugt werden:

```bash
$ cp .env .env.local
```

## Konfigurationsdatei anpassen

Anschließend muss die Konfigurationsdatei angepasst werden.

Die folgenden zwei Einstellungen werden für die Konfiguration des Symfony Frameworks verwendet.

#### APP_ENV

Dieser Wert muss immer `prod` enthalten, sodass das System in der Produktionsumgebung ist.

:::caution[Achtung]
In einer Produktionsumgebung niemals `dev` verwenden.
:::

#### APP_SECRET

Dieser Wert muss eine zufällige Zeichenfolge beinhalten. Mit dem folgenden Kommando kann eine entsprechende Zeichenfolge
erstellt werden:

```bash
$ openssl rand -base64 32
```

### Allgemeine Einstellungen

#### APP_URL

Dieser Wert beinhaltet die URL zur Instanz, bspw. `https://library.schulit.de/`.

#### APP_NAME

Dieser Wert beinhaltet den Namen der Anwendung. Standardmäßig ist dies `Library`, kann aber beliebig
geändert werden, um z.B. den Schulnamen zu integrieren.

#### APP_LOGO

Dieses Logo wird im Footer der Website angezeigt. Es werden alle Formate unterstützt, die auch von Browsern unterstützt
werden. Die Maße des Logos können frei gewählt werden. Das Logo wird automatisch auf eine Höhe von 80px vergrößert oder
verkleinert (vom Browser).

Für das Logo sollte im Ordner `public/images/` liegen. Der Pfad wird relativ zum `public`-Ordner angegeben.

Beispiel: Ist das Logo unter `public/images/logo.svg` gespeichert, muss `images/logo.svg` für den Parameter eingetragen werden.

#### APP_SMALLLOGO

Dieses Logo wird oben in der Navigationsleiste angezeigt. Es werden alle Formate unterstützt, die auch von Browsern unterstützt
werden. Das Logo sollte quadratisch sein und wird automatisch auf eine Breite bzw. Höhe von 32px vergrößert.

Für das Logo sollte im Ordner `public/images/` liegen. Der Pfad wird relativ zum `public`-Ordner angegeben.

Beispiel: Ist das Logo unter `public/images/smalllogo.svg` gespeichert, muss `images/smalllogo.svg` für den Parameter eingetragen werden.

#### SAML_ENTITY_ID

Die sogenannte Entity ID dieses Identity Providers. Hier trägt man in der Regel die URL des Dienstes ein, also denselben
Wert wie in `APP_URL`.

### Datenbank

Verbindungszeichenfolge für die Datenbankverbindung, welche sich wie folgt zusammensetzt:

```
mysql://USERNAME:PASSWORD@HOST:3306/NAME
```

* `USERNAME`: Benutzername der Datenbank
* `PASSWORD`: zugehöriges Passwort des Datenbankbenutzers
* `HOST`: Hostname des Datenbankservers
* `NAME`: Name der Datenbank

Weitere Informationen zur Verbindungszeichenfolge gibt es [hier](https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url).

### MESSENGER_TRANSPORT_DSN

Diesen Parameter **NICHT** verändern. Standardmäßig ist er auf `doctrine://default` konfiguriert.