#!/bin/sh

THESITE="jhm"
THEDATE=`date +%d%m%y%H%M`

tar czf /var/www/html/backups/files/sitebackup_${THESITE}_${THEDATE}.tar -C / var/www/html/$THESITE
gzip /var/www/html/backups/files/sitebackup_${THESITE}_${THEDATE}.tar

#find /var/www/html/backups/files/site* -mtime +5 -exec rm {} \;