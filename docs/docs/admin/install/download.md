---
sidebar_position: 2
---

# Download & Vorbereitung

## Quellcode herunterladen

```bash
$ git clone https://github.com/schulit/library.git
$ cd library
$ git checkout -b <LATEST_VERSION>
```

Dabei `<LATEST_VERSION>` durch die gewünschte Version ersetzen.

## Abhängigkeiten installieren

```bash
$ composer install --no-dev --classmap-authoritative --no-scripts
```