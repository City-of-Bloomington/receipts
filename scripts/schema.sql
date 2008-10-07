create table users (
	userID int unsigned not null primary key auto_increment,
	username varchar(30) not null,
	password varchar(32),
	authenticationMethod varchar(30) not null default 'LDAP',
	pin int(4) unsigned zerofill
);

create table roles (
	role varchar(30) not null primary key
);
insert roles values('Administrator');
insert roles values('Supervisor');
insert roles values('Clerk');

create table userRoles (
	userID int unsigned not null,
	role varchar(30) not null,
	primary key (userID,role),
	foreign key (userID) references users(userID),
	foreign key (role) references roles(role)
);


create table accounts (
	accountID int unsigned not null primary key auto_increment,
	accountNumber varchar(22) not null,
	name varchar(30) not null,
	unique (accountNumber)
);

create table fees (
	feeID int unsigned not null primary key auto_increment,
	name varchar(128) not null,
	amount decimal(7,2) not null,
	accountID int unsigned not null,
	foreign key (accountID) references accounts(accountID)
);

create table receipts (
	receiptID int unsigned not null primary key auto_increment,
	date date not null,
	enteredBy int unsigned not null,
	firstname varchar(128) not null,
	lastname varchar(128) not null,
	paymentMethod varchar(30) not null default 'cash',
	depositSlipDate date,
	status enum('valid','void') not null default 'valid',
	notes text,
	foreign key (paymentMethod) references paymentMethods(paymentMethod),
	foreign key (enteredBy) references users(userID),
	fulltext (notes)
) engine=MyISAM;

create table paymentMethods (
	paymentMethod varchar(30) not null primary key
);
insert paymentMethods values('cash');
insert paymentMethods values('check');
insert paymentMethods values('money order');

create table lineItems (
	lineItemID int unsigned not null primary key auto_increment,
	receiptID int unsigned not null,
	feeID int unsigned not null,
	quantity tinyint unsigned not null default 1,
	amount decimal(7,2) not null,
	notes varchar(128),
	foreign key (receiptID) references receipts(receiptID),
	foreign key (feeID) references fees(feeID),
	fulltext (notes)
) engine=MyISAM;

create table voidedReceipts (
receiptID int unsigned not null primary key,
voidedDate date not null,
voidedReason varchar(255) not null,
voidedBy int unsigned not null,
foreign key (receiptID) references receipts(receiptID),
foreign key (voidedBy) references users(userID)
) engine=MyISAM;
