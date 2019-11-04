CREATE DATABASE midterm;
USE midterm;
CREATE TABLE midtermData 
(id int not null auto_increment, 
email varchar(200) not null, 
phone varchar(20) not null, 
localfilename varchar(255) not null, 
s3rawurl varchar(255) not null, 
s3finishedurl varchar(255) not null, 
statuscode int not null, 
issubscribed int not null, 
constraint midterm_pk primary key (id));
