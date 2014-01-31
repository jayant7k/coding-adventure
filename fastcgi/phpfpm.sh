#!/bin/bash

FASTCGI_USER=jayant
FASTCGI_GROUP=jayant
ADDRESS=127.0.0.1
PORT=8000
PIDFILE=/tmp/php-fastcgi.pid
CHILDREN=6
PHP5=/usr/bin/php5-cgi

/usr/bin/spawn-fcgi -a $ADDRESS -p $PORT -P $PIDFILE -C $CHILDREN -u $FASTCGI_USER -g $FASTCGI_GROUP -f $PHP5
