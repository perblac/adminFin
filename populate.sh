#!/bin/bash
/bin/mariadb --user=root --password=root adminfindb < /user.sql
/bin/mariadb --user=root --password=root adminfindb < /notification.sql
/bin/mariadb --user=root --password=root adminfindb < /conversation.sql
/bin/mariadb --user=root --password=root adminfindb < /notification_conversation.sql
