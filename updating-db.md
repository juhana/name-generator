# Updating the games list

Quick notes on how to update the games list.


## Full list

Download the latest IFDB dump from http://ifarchive.org/indexes/if-archive/info/ifdb/ and unzip to get the SQL file (ifdb-archive.sql).

```
docker run --name mysql-container -e MYSQL_ROOT_PASSWORD=root -d mysql
docker pull phpmyadmin/phpmyadmin
docker run --name phpmyadmin-container -d --link mysql-container:db -p 8081:80 phpmyadmin/phpmyadmin

docker exec mysql-container /usr/bin/mysql -u root --password=root -e 'CREATE DATABASE ifdb;'
docker exec -i mysql-container /usr/bin/mysql -u root --password=root ifdb < ifdb-archive.sql
```

Open the database in phpMyAdmin (http://localhost:8081) and run a query `SELECT title FROM games;` Export the result as a PHP array to a file called games.php.

In the PHP array file, remove all `array('title' => ` and replace `\)(,?)$` (regex) with `$1` (first capture group). Replace the old data/games.php with this file.


## IFComp games

During comp:

* Log in to the IFComp web site and go to https://ifcomp.org/ballot/vote/
* Command `$("a[href^='/ballot#']").map((_, elem) => $(elem).text().replace("'", "\\'")).get().join("',\n\t\t'")` in the browser console
* Copy-paste the result to data/comps.php, add `20xx => array(` (where 20xx is the current year) to the start and `),` to the end and fix the quotes at the start and end

After comp:

* Copy-paste compact lists from previous results pages (e.g. https://ifcomp.org/comp/2019?compact=1)
* Replace `'` and `\` with `\'` and `\\`
* Replace regex `.+\t(.+) by .*` with `\t\t'$1',`
* Remove the comma from the last entry
* Add `20xx => array(` (where 20xx is the year) to the start and `),` to the end
* Put the list to the start of data/comps.php.
