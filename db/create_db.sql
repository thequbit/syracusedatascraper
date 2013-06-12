drop database cusedata;
create database cusedata;

grant usage on cusedata.* to cuseuser identified by 'password123%%%';

grant all privileges on cusedata.* to cuseuser;

use cusedata;

create table crimes(
crimeid int not null auto_increment primary key,
crime varchar(127) not null,
rawaddress varchar(255) not null,
fulladdress varchar(255),
lat float,
lng float,
zipcode varchar(8),
city varchar(127) not null,
crimedate date not null,
crimetime time not null
);

create index crimess_crime on crimes(crime);
create index crimess_crimedate on crimes(crimedate);

