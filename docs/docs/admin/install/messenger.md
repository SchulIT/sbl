---
sidebar_position: 6
---

# Hintergrundaufgaben

Einige Aufgaben wie bspw. der E-Mail-Versand werden asynchron im Hintergrund ausgeführt. Dazu wird der [Symfony Messenger](https://symfony.com/components/messenger)
versendet. Dieser wird standardmäßig als Hintergrunddienst über einen Supervisor (bspw. systemd unter Linux) ausgeführt.
Das setzt jedoch voraus, dass man Zugriff auf diesen hat. Bei Webhostern ist dies klassischerweise nicht der Fall.

## Konfiguration als systemd-Dienst

Zunächst müssen zwei entsprechende systemd-Dienste installiert werden (siehe [offizielle Dokumentation](https://symfony.com/doc/current/messenger.html#systemd-configuration)).

Zunächst der Scheduler für die Hintergrundaufgaben `library-scheduler.service`:

```
[Unit]
Description=Library Scheduler

[Service]
ExecStart=/usr/bin/php /path/to/library/bin/console messenger:consume scheduler_default --time-limit=3600
Restart=always
RestartSec=0

[Install]
WantedBy=default.target
```

Anschließend noch der Dienst zum Ausführen der eigentlichen Hintergrundaufgaben `library-background.service`:

```
[Unit]
Description=Library Hintergrundaufgaben

[Service]
ExecStart=/usr/bin/php /path/to/library/bin/console messenger:consume async --time-limit=3600
Restart=always
RestartSec=0

[Install]
WantedBy=default.target
```

:::tip[Tipp]
Es wird empfohlen, den Dienst als sogenannten *user service* laufen zu lassen.
:::