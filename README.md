# mahara
Run    
sudo apt-get install php-mysql   
before running the program.   
Repository created to showcase my solution to the Junior Developer role challenge.   
Assumptions and new implementations:   
1- I'm assuming the create table option deletes the old table (when it exists) and creates a new one;   
2- I added the -d option to get input regarding the name of the database. The PDF doesn't have this option, but it's needed to properly connect to MySQL and send queries;   
3- I avoided writing overly complicated functions to normalize names and surnames. I've used regex coupled with chained IFs to correct names in the past, so I know that it's never pretty, easy, or perfect.
