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

The database .csv is included in this repository.  You can use a .csv to SQL converter (found online)

New with Sprint 2:  added tables:

CREATE TABLE user_movie_lists (
    list_id INT AUTO_INCREMENT PRIMARY KEY,
    id INT NOT NULL,
    list_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

the second table is list_contents - contains all the movies in a list. 
 
CREATE TABLE list_contents (     content_id INT AUTO_INCREMENT PRIMARY KEY,     list_id INT NOT NULL,     movie_id INT NOT NULL,     added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,     FOREIGN KEY (list_id) REFERENCES user_movie_lists(list_id), FOREIGN KEY (movie_id) REFERENCES movies(movie_id) );) to create an import statement that will get the data into the database.  

Also - adding movie_id to movies table.  This is important for list functionality:

ALTER TABLE movies ADD COLUMN movie_id INT AUTO_INCREMENT PRIMARY KEY FIRST;






