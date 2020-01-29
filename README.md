# ProcessJsonFile
JSON is used to store information in an organized, and easy-to-access manner. Its full form is JavaScript Object Notation. It offers a human-readable collection of data which can be accessed logically. This project reads a json file and processing it into an output csv


To run this program, please install xamp application to run mysql and php 7 on your local computer.

Execute the Main file : process.php

Database connection details can be found in connection.php

This program has three parts: 

1. The necessary database script.sql will be executed at the beginning and then the json file.
2. The program reads the json order objects one by one and stores all the that data into order_table, customer_table, items_table
3. This program also creates summary for each order in summary table and in the end export all the data into a out.csv file

