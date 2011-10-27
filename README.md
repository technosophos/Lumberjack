# Lumberjack Log Parser and Analyzer

A utility for parsing log files, loading them into MongoDB, and
performing advanced analysis. A web interface makes viewing
easy.

## Parsers Supported

* Apache
* Varnish
* Drupal syslog
* UNIX-style syslog (regex parsing)

The BaseLogger command provides a base class that can easily be
extended to parse file-based logs.

## Credits

This was initially developed for ConsumerSearch.com.
