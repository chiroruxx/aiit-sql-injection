create database test default character set utf8 collate utf8_general_ci;

use test;
create table products (name varchar(191), price int);
insert into products values ('product01', 100), ('product02', 200), ('product03', 300);
create table users (name varchar(191), department varchar(191));
insert into users values ('maeda', 'department_a'), ('tanaka', 'department_a'), ('yasuda', 'department_b');
