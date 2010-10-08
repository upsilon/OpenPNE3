#
# Regular cron jobs for the openpne3 package
#
0 6 * * * root sh /usr/bin/send_daily_news.cron /usr/lib/openpne3 /usr/bin/php5
0 6 * * * root sh /usr/bin/birthday_mail.cron /usr/lib/openpne3 /usr/bin/php5

