# EECS581_Project3
Repository for term project for EECS 581.

In order for this to work, for testing purposes you will need to install the following:

mySQL:  https://dev.mysql.com/downloads/installer/
Apache: https://www.apachelounge.com/
PHP:  https://www.php.net/

Once mySQL is installed, run the following command: CREATE database imdb;

Then to create the tables:

CREATE TABLE Movies (myRank int, myTitle varchar(255), Genre varchar(255), Description varchar(255), Director varchar(255), Actors varchar(255),
Year int, Runtime int, Rating float(4,1), Votes int, Revenue float(8,2), Metascore int);

The database .csv is included in this repository.  You can use a .csv to SQL converter (found online) to create an import statement that will get the data into the database.  


