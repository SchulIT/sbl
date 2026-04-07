---
sidebar_position: 4
---

# Anwendung im Single Sign-On registrieren

Damit sich Benutzer anmelden können, muss die Anwendung im Single Sign-On registriert werden. 

## SAML-Zertifikat erstellen

Es wird ein selbst-signiertes Zertifikat mittels OpenSSL erzeugt. Dazu das folgende Kommando ausführen:

```bash
$ php bin/console app:create-certificate --type saml
```

Anschließend werden einige Daten abgefragt. Diese können abgesehen vom `commonName` frei gewählt werden:

* `countryName`, `stateOrProvinceName`, `localityName` geben den Standort der Schule an
* `organizationName` entspricht dem Namen der Schule
* `organizationalUnitName` entspricht der Fachabteilung der Schule, welche für die Administration zuständig ist
* `commonName` Domainname des ICC, bspw. `icc.schulit.de`
* `emailAddress` entspricht der E-Mail-Adresse des Administrators

:::info
Das Zertifikat ist standardmäßig 10 Jahre gültig.
:::

## Single Sign-On hinterlegen

Damit die Software den Single Sign-On kennt, muss noch eine XML-Datei hinterlegt werden.

Unter *Verwaltung ➜ IdP Details* den XML-Teil in die Zwischenablage kopieren und den Inhalt in der Datei `saml/idp.xml`
im Library-Ordner hinterlegen.

## Dienst beim Single Sign-On registrieren

:::warning[Hinweis]
Der folgende Schritt muss im Single Sign-On erledigt werden.
:::

Unter *Verwaltung ➜ Dienste* einen *neuen Dienst (SchulIT)* erstellen.

Als URL gibt man `https://library.your-domain.com/autoconfig` an. Anschließend ist der Dienst im Single Sign-On registriert.


