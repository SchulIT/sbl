---
sidebar_position: 1
---

# Voraussetzungen

Grundsätzlich muss SSH-Zugriff auf den Server vorhanden sein. Da die Software mit Hintergrundaufgaben arbeitet,
muss der Server die Ausführung von Hintergrundprozessen (bspw. mittels `systemd`) unterstützen.

## Software

* Webserver
  * Apache 2.4+ oder
  * nginx
* PHP 8.4+ mit folgenden Erweiterungen
  * bcmath
  * ctype
  * curl
  * date
  * dom
  * filter
  * gd
  * iconv
  * imagick
  * json
  * libxml
  * mbstring
  * openssl
  * pcre
  * pdo_mysql
  * simplexml
  * tokenizer
  * xml
  * xmlwriter
  * zlib
  * zstd
* MariaDB 10.4+ (ein kompatibles MySQL kann funktionieren, ist jedoch nicht getestet)
* Composer 2+
* Git (zum Einspielen des Quelltextes)

Die Software muss auf einer Subdomain betrieben werden. Das Betreiben in einem Unterverzeichnis wird nicht unterstützt.

## Installation auf Webspaces

Aktuell ist die Software mit ziemlich großer Wahrscheinlichkeit nicht auf Webspaces nutzbar, da dort Hintergrundaufgaben
(mittels Supervisor) nicht unterstützt werden.