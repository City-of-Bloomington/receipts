create table users (
	userID int unsigned not null primary key auto_increment,
	username varchar(30) not null,
	password varchar(32),
	authenticationMethod varchar(30) not null default 'LDAP'
);

create table userRoles (
	userID int unsigned not null,
	role varchar(30) not null,
	primary key (userID,role),
	foreign key (userID) references users(userID),
	foreign key (role) references roles(role)
);

create table roles (
	role varchar(30) not null primary key
);
insert roles values('Administrator');
insert roles values('Supervisor');
insert roles values('Clerk');


create table accounts (
	accountID int unsigned not null primary key auto_increment,
	accountNumber varchar(22) not null,
	name varchar(30) not null
);

create table fees (
	feeID int unsigned not null primary key auto_increment,
	name varchar(128) not null,
	amount decimal(7,2) not null
);

create table feeAccounts (
	feeID int unsigned not null,
	accountID int unsigned not null,
	primary key (feeID,accountID),
	foreign key (feeID) references fees(feeID),
	foreign key (accountID) references accounts(accountID)
);
