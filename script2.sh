##!/usr/bin/env bash
# My first script
#write out current crontab
#crontab -e
HOME=/root
LOGNAME=root
PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin
LANG=en_US.UTF-8
SHELL=/bin/sh
PWD=/INfoc!ty1
crontab -l > mycron
#chmod -x /var/www/html/blazedeskreplica/script2.sh
#echo new cron into cron file
#echo chmod +x /var/www/html/blazedeskreplica/script2.sh
echo "*/1 * * * * root /usr/bin/php http://103.254.245.142:81/cmcrm/ecurl.php" >> mycron
#echo "*/5 * * * * root /usr/bin/curl -o temp.txt  http://103.254.245.142:81/cmcrm/index/CRMCron/fetchEmails" >> mycron
#echo "*/5 * * * * root /usr/bin/curl -o temp.txt http://103.254.245.142:81/cmcrm/index.php CRMCron fetchEmails" >> mycron
#echo "*/5 * * * * root /usr/bin/php  http://103.254.245.142:81/blazedeskreplica/mail.php" >> script2
#wget -O - -q -t 1 http://103.254.245.142:81/blazedeskreplica/mail.php
#install new cron file
crontab mycron
rm mycron

