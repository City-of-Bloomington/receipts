create table people (
	id        int unsigned not null primary key auto_increment,
	firstname varchar(128) not null,
	lastname  varchar(128) not null,
	email     varchar(128) unique,
	pin       int,
	username  varchar(40)  unique,
	password  varchar(40),
	role      varchar(30),
	authentication_method varchar(40)
);

create table accounts (
	id      int unsigned not null primary key auto_increment,
	number  varchar(22)  not null,
	name    varchar(30)  not null,
	unique (number)
);

create table fees (
	id         int unsigned not null primary key auto_increment,
	name       varchar(128) not null,
	amount     decimal(7,2),
	account_id int unsigned not null,
	foreign key (account_id) references accounts(id)
);

create table receipts (
	id              int unsigned not null primary key auto_increment,
	date            date         not null,
	entered_by      int unsigned,
	firstname       varchar(128) not null,
	lastname        varchar(128) not null,
	paymentMethod   varchar(30)  not null default 'cash',
	void_date       date,
	void_reason     varchar(255),
	void_by         int unsigned,
	notes           text,
	foreign key (entered_by) references people(id),
	foreign key (   void_by) references people(id)
);

create table lineItems (
	id         int     unsigned not null primary key auto_increment,
	receipt_id int     unsigned not null,
	fee_id     int     unsigned not null,
	quantity   tinyint unsigned not null default 1,
	amount     decimal(7, 2) not null,
	notes      varchar(128),
	foreign key (receipt_id) references receipts(id),
	foreign key (    fee_id) references fees    (id)
);

create table depositSlips (
    id   int  unsigned not null primary key auto_increment,
    date date          not null
);

create table depositSlip_receipts (
    depositSlip_id int unsigned not null,
        receipt_id int unsigned not null,
    primary key (depositSlip_id, receipt_id)
);
