-- chatchops

-- ----------------------------------------
CREATE TABLE admins (
    admin_id int AUTO_INCREMENT,
    fname VARCHAR(255) NOT NULL,
    lname VARCHAR(255) NOT NULL,
    email VARCHAR(320) NOT NULL,
    pwd VARCHAR(512) NOT NULL,
    lastSeenDT DATETIME NOT NULL,
    username VARCHAR(50) NOT NULL,
    actSTatus BOOLEAN NOT NULL,
    PRIMARY KEY(admin_id)
);

-- userUserActiveIDMap
CREATE TABLE user_User_Act_ID_Map (
	user_id int NOT NULL,
    active_id int NOT NULL,
    CONSTRAINT Pk_userActIdMap PRIMARY KEY(user_id, active_id)
);

CREATE TABLE acc_Status_User_Map (
	status_id int NOT NULL,
    user_id int NOT NULL,
    CONSTRAINT Pk_accStatus PRIMARY KEY(status_id, user_id)
);

-- privateGroupLeave_MemberMap
CREATE TABLE p_Group_Leave_Mem_Map (
	leave_id int NOT NULL,
    member_id int NOT NULL,
    CONSTRAINT Pk_pGrpLveMemMap PRIMARY KEY(leave_id, member_id)
);

-- privateGroupUserRemove
CREATE TABLE p_Group_User_Remove (
	pgu_id int AUTO_INCREMENT,
    member_id int NOT NULL,
    admin_id int NOT NULL,
    DateAndTime DATETIME NOT NULL,
    PRIMARY KEY(pgu_id)
);

-- public group member status
CREATE TABLE pub_Grp_Mem_Status (
	status_id int AUTO_INCREMENT,
    DateAndTime DATETIME NOT NULL,
    active BOOLEAN NOT NULL,
    PRIMARY KEY(status_id)
);

-- public group member
CREATE TABLE pub_Grp_Member (
	member_id int AUTO_INCREMENT,
    group_id int NOT NULL,
    user_id int NOT NULL,
    CONSTRAINT Pk_pubGrpMember PRIMARY KEY(member_id, group_id, user_id)
);

-- publicGroupAdmin
CREATE TABLE pub_Grp_Admin (
	grpAdmin_id int AUTO_INCREMENT,
    member_id int NOT NULL,
    PRIMARY KEY(grpAdmin_id)
);

-- ---------------------------------------------------
-- privateMessage
CREATE TABLE private_Message(
    p_id int AUTO_INCREMENT,
    message text NOT null,
    send_time datetime not null,
    msg_status boolean not null,
    receive_time datetime not null,
    PRIMARY KEY(p_id)
    );
	
CREATE TABLE friends(
    friend_id int AUTO_INCREMENT,
    from_user_id int not null,
    to_user_id int not null,
    PRIMARY KEY(friend_id));

CREATE TABLE users(
    user_id int AUTO_INCREMENT,
    first_name varchar(30) not null,
    last_name varchar(30) not null,
    email varchar(320) not null,
    pwd varchar(512) not null,
    last_seen datetime not null,
    username VARCHAR(50) NOT NULL,
    profile_pic blob NOT NULL,
    active_status boolean not null,
    created_time datetime not null,
    PRIMARY KEY(user_id));

-- privateGroupChat_MemberMap	
CREATE TABLE pGroup_Mem_Map(
    msg_id int not null,
    member_id int not null,
    CONSTRAINT Pk_pGroupMemMap PRIMARY KEY(msg_id, member_id));

-- userPrivateGroupMap
CREATE TABLE user_PGroup_Map(
    created_user_id int not null,
    group_id int not null,
    CONSTRAINT Pk_userPGrpMap PRIMARY KEY(created_user_id, group_id));

-- publicGroupMember_statusMap
CREATE TABLE pub_group_mem_status_map(
    status_id int not null,
    member_id int not null,
    CONSTRAINT Pk_pgrpmemstatusMap PRIMARY KEY(status_id, member_id));

-- publicGroupLeave_MemberMap
CREATE TABLE pub_group_Leave_mem_map(
    leave_id int not null,
    member_id int not null,
    CONSTRAINT Pk_pGrpLeaveMemMap PRIMARY KEY(leave_id, member_id));

-- publicGroupUserRemove
CREATE TABLE pub_Group_User_Remove(
    remove_id int not null AUTO_INCREMENT,
    member_id int not null,
    admin_id int not null,
    removeDate datetime not null,
    PRIMARY KEY(remove_id));

-- ---------------------------------------------------------------------------
CREATE  TABLE user_active_time(
	active_id int AUTO_INCREMENT NOT NULL,
	online_date_and_time DATETIME NOT NULL,
	offline_date_and_time DATETIME NOT NULL,
	PRIMARY KEY(active_id)
);

-- private_group_member_status_map
	CREATE  TABLE p_grp_mem_status_map(
	status_id int NOT NULL,
	member_id int NOT NULL,
	CONSTRAINT PK_pub_grp_mem_status_map PRIMARY KEY(status_id,member_id)
 );

-- public_group_chat_member_map
CREATE  TABLE pub_grp_chat_mem_map(
	msg_id int NOT NULL,
	member_id int NOT NULL,
	CONSTRAINT PK_pub_grp_chat_mem_map PRIMARY KEY(msg_id,member_id)
 );

CREATE TABLE private_group_leave(
 	leave_id int AUTO_INCREMENT NOT NULL,
 	date_and_time DATETIME NOT NULL,
	reason varchar(255) NOT NULL,
	PRIMARY KEY(leave_id)
);

-- puplic_group_leave
CREATE TABLE pub_group_leave(
 	leave_id int AUTO_INCREMENT NOT NULL,
 	date_and_time DATETIME NOT NULL,
	reason varchar(255) NOT NULL,
	PRIMARY KEY(leave_id)
);

CREATE TABLE private_group(
	group_id int AUTO_INCREMENT NOT NULL,
	group_name VARCHAR(30) NOT NULL,
	created_date_time DATETIME NOT NULL,
	group_icon BLOB NOT NULL,
	bio VARCHAR(100) NOT NULL,
	pgrp_status boolean NOT NULL,
	PRIMARY KEY(group_id)
);

CREATE TABLE public_group(
	group_id int AUTO_INCREMENT NOT NULL,
	group_name VARCHAR(30) NOT NULL,
	created_date_and_time DATETIME NOT NULL,
    group_icon BLOB NOT NULL,
	bio VARCHAR(100) NOT NULL,
	pubgrp_status BOOLEAN NOT NULL,
	PRIMARY KEY(group_id)
);

CREATE TABLE account_status(
	status_id int AUTO_INCREMENT NOT NULL,
	account_status BOOLEAN NOT NULL,
	date_and_time DATETIME NOT NULL,
	PRIMARY KEY(status_id)
);

CREATE TABLE friend_req_friend_map(
	req_id int NOT NULL,
	friend_id int NOT NULL,
	CONSTRAINT PK_friend_req_friend_map PRIMARY KEY(req_id,friend_id)
);

-- ---------------------------------------------

-- public group chat
CREATE TABLE pub_grp_chat(
	msg_id int AUTO_INCREMENT,
	message TEXT NOT NULL,
	date_time DATETIME NOT NULL,
	PRIMARY KEY(msg_id)
);

-- publicGroupUserMap
CREATE TABLE pub_grp_user_map(
	created_user_id int NOT NULL,
	group_id int NOT NULL,
	CONSTRAINT PK_pub_grp_user_map PRIMARY KEY(created_user_id,group_id)
);

-- public group member status
CREATE TABLE pgrp_mem_status(
	statusId int AUTO_INCREMENT,
	addDate DATETIME NOT NULL,
    actStatus BOOLEAN NOT NULL,
	PRIMARY KEY(statusId)
);

-- privateGroupAdmin
CREATE TABLE pgrp_admin(
	adminId int AUTO_INCREMENT,
	memberId int NOT NULL,
	PRIMARY KEY(adminId)
);

-- privateMsgFriendsMap
CREATE TABLE p_msg_friend_map(
    p_id int not null,
	friend_id int not null,
    CONSTRAINT Pk_p_msg_friend_map PRIMARY KEY(p_id, friend_id));

-- friend_request
CREATE TABLE friend_request(
    req_id int not null AUTO_INCREMENT,
    req_status boolean not null,
	block_status boolean not null,
    req_time datetime not null,
	accept_time datetime not null,
    PRIMARY KEY(req_id));

-- private group chat
CREATE TABLE p_group_chat(
    msg_id int not null AUTO_INCREMENT,
    msg text not null,
	block_status boolean not null,
    send_time datetime not null,
    PRIMARY KEY(msg_id));
	
-- private group member	
CREATE TABLE p_group_member(
    mem_id int not null AUTO_INCREMENT,
    user_id int not null,
	group_id int not null,
    PRIMARY KEY(mem_id));

-- delete private table
CREATE TABLE p_grp_delete(
    delete_id int AUTO_INCREMENT,
    group_id int NOT NULL,
    date_time DATETIME NOT NULL,
    PRIMARY KEY (delete_id)
);

-- delete public table
CREATE TABLE pub_grp_delete(
    delete_id int AUTO_INCREMENT,
    group_id int NOT NULL,
    date_time DATETIME NOT NULL,
    PRIMARY KEY (delete_id)
);


-- ----------------------------------------------

ALTER TABLE friends
ADD FOREIGN KEY (from_user_id) REFERENCES users(user_id);

ALTER TABLE p_msg_friend_map
ADD FOREIGN KEY (p_id) REFERENCES private_message(p_id);

ALTER TABLE p_msg_friend_map
ADD FOREIGN KEY (friend_id) REFERENCES friends(friend_id);

ALTER TABLE friends
ADD FOREIGN KEY (from_user_id) REFERENCES users(user_id);

ALTER TABLE friend_req_friend_map
ADD FOREIGN KEY (req_id) REFERENCES friend_request(req_id);

ALTER TABLE friend_req_friend_map
ADD FOREIGN KEY (friend_id) REFERENCES friends(friend_id);

ALTER TABLE user_user_act_id_map
ADD FOREIGN KEY (active_id) REFERENCES user_active_time(active_id);

ALTER TABLE user_user_act_id_map
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);

ALTER TABLE acc_status_user_map
ADD FOREIGN KEY (status_id) REFERENCES account_status(status_id);

ALTER TABLE acc_status_user_map
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);

ALTER TABLE pgroup_mem_map
ADD FOREIGN KEY (msg_id) REFERENCES p_group_chat(msg_id);

ALTER TABLE pgroup_mem_map
ADD FOREIGN KEY (member_id) REFERENCES p_group_member(mem_id);

ALTER TABLE p_grp_mem_status_map
ADD FOREIGN KEY (member_id) REFERENCES p_group_member(mem_id);

ALTER TABLE p_grp_mem_status_map
ADD FOREIGN KEY (status_id) REFERENCES pgrp_mem_status(statusId);

ALTER TABLE pub_Group_User_Remove
ADD FOREIGN KEY (member_id) REFERENCES pub_Grp_Member(member_id);

ALTER TABLE pub_Group_User_Remove
ADD FOREIGN KEY (admin_id) REFERENCES pub_Grp_Admin(grpAdmin_id);

ALTER TABLE pub_Grp_Admin
ADD FOREIGN KEY (member_id) REFERENCES pub_Grp_Member(member_id);

ALTER TABLE pub_grp_chat_mem_map
ADD FOREIGN KEY (msg_id) REFERENCES pub_grp_chat(msg_id);

ALTER TABLE pub_grp_chat_mem_map
ADD FOREIGN KEY (member_id) REFERENCES pub_Grp_Member(member_id);

ALTER TABLE pub_group_Leave_mem_map
ADD FOREIGN KEY (leave_id) REFERENCES pub_group_leave(leave_id);

ALTER TABLE pub_group_Leave_mem_map
ADD FOREIGN KEY (member_id) REFERENCES pub_Grp_Member(member_id);

ALTER TABLE pub_Grp_Member
ADD FOREIGN KEY (group_id) REFERENCES public_group(group_id);

ALTER TABLE pub_Grp_Member
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);

ALTER TABLE pub_group_mem_status_map
ADD FOREIGN KEY (member_id) REFERENCES pub_Grp_Member(member_id);

ALTER TABLE pub_group_mem_status_map
ADD FOREIGN KEY (status_id) REFERENCES pub_Grp_Mem_Status(status_id);

ALTER TABLE pub_grp_user_map
ADD FOREIGN KEY (group_id) REFERENCES public_group(group_id);

ALTER TABLE pub_grp_user_map
ADD FOREIGN KEY (created_user_id) REFERENCES users(user_id);

ALTER TABLE user_PGroup_Map
ADD FOREIGN KEY (group_id) REFERENCES private_group(group_id);

ALTER TABLE user_PGroup_Map
ADD FOREIGN KEY (created_user_id) REFERENCES users(user_id);

ALTER TABLE p_Group_User_Remove
ADD FOREIGN KEY (admin_id) REFERENCES pgrp_admin(adminId);

ALTER TABLE pgrp_admin
ADD FOREIGN KEY (memberId) REFERENCES p_group_member(mem_id);

ALTER TABLE p_Group_Leave_Mem_Map
ADD FOREIGN KEY (leave_id) REFERENCES private_group_leave(leave_id);

ALTER TABLE p_Group_Leave_Mem_Map
ADD FOREIGN KEY (member_id) REFERENCES p_group_member(mem_id);

ALTER TABLE p_group_member
ADD FOREIGN KEY (group_id) REFERENCES private_group(group_id);

ALTER TABLE p_Group_User_Remove
ADD FOREIGN KEY (member_id) REFERENCES p_group_member(mem_id);

ALTER TABLE p_group_member
ADD FOREIGN KEY (user_id) REFERENCES users(user_id);

ALTER TABLE p_grp_delete
ADD FOREIGN KEY (group_id) REFERENCES private_group(group_id);

ALTER TABLE pub_grp_delete
ADD FOREIGN KEY (group_id) REFERENCES private_group(group_id);

-- ------------------------------------------------------------------------------------------

ALTER TABLE users
ALTER active_status SET DEFAULT 0;

ALTER TABLE users DROP COLUMN profile_pic;

ALTER TABLE users
ADD profilePicLink VARCHAR(60) NOT NULL;

ALTER TABLE users
ADD otpCode INT NOT NULL;

ALTER TABLE users
ADD deleteStatus BOOLEAN NOT NULL;

ALTER TABLE users
ALTER deleteStatus SET DEFAULT 0;

CREATE TABLE user_session(
    users_id int NOT NULL,
    session_id varchar(100) NOT NULL,
    session_expire DATETIME NOT NULL,
    PRIMARY KEY (users_id)
);

ALTER TABLE user_session
ADD FOREIGN KEY (users_id) REFERENCES users(user_id);

ALTER TABLE admins
ADD online_status BOOLEAN NOT NULL DEFAULT 0;

CREATE TABLE admin_session(
    admin_id int NOT NULL,
    session_id varchar(100) NOT NULL,
    session_expire DATETIME NOT NULL,
    PRIMARY KEY (admin_id)
);

ALTER TABLE admin_session
ADD FOREIGN KEY (admin_id) REFERENCES admins(admin_id);

ALTER TABLE users
ADD onlineStatus BOOLEAN NOT NULL DEFAULT 0;

-- last added query for friend status

ALTER TABLE friend_request
ADD friendStatus BOOLEAN NOT NULL DEFAULT 0;

-- public group column changed
ALTER TABLE public_group DROP COLUMN group_icon;

ALTER TABLE public_group
ADD icon_link VARCHAR(60) NOT NULL;

-- added column to find private message reserver and add forieng key with users table

ALTER TABLE private_message
ADD reserveId INT NOT NULL;

ALTER TABLE private_message
ADD FOREIGN KEY (reserveId) REFERENCES users(user_id);

-- added table to store analize TIME

CREATE TABLE analizeReords (
	dataId INT AUTO_INCREMENT,
    lastData DATETIME NOT NULL,
    PRIMARY KEY (dataId)
);

-- create table for store user online analize data accourding to DATE

CREATE TABLE analizeOnlineEachDateH(
	recId INT AUTO_INCREMENT,
	recDate DATE NOT NULL,
	h1 INT NOT NULL DEFAULT 0,
	h2 INT NOT NULL DEFAULT 0,
	h3 INT NOT NULL DEFAULT 0,
	h4 INT NOT NULL DEFAULT 0,
	h5 INT NOT NULL DEFAULT 0,
	h6 INT NOT NULL DEFAULT 0,
	h7 INT NOT NULL DEFAULT 0,
	h8 INT NOT NULL DEFAULT 0,
	h9 INT NOT NULL DEFAULT 0,
	h10 INT NOT NULL DEFAULT 0,
	h11 INT NOT NULL DEFAULT 0,
	h12 INT NOT NULL DEFAULT 0,
	h13 INT NOT NULL DEFAULT 0,
	h14 INT NOT NULL DEFAULT 0,
	h15 INT NOT NULL DEFAULT 0,
	h16 INT NOT NULL DEFAULT 0,
	h17 INT NOT NULL DEFAULT 0,
	h18 INT NOT NULL DEFAULT 0,
	h19 INT NOT NULL DEFAULT 0,
	h20 INT NOT NULL DEFAULT 0,
	h21 INT NOT NULL DEFAULT 0,
	h22 INT NOT NULL DEFAULT 0,
	h23 INT NOT NULL DEFAULT 0,
	h24 INT NOT NULL DEFAULT 0,
	PRIMARY KEY(recId),
	UNIQUE (recDate)
);

-- create table for store user online data accouding to MONTH

CREATE TABLE analizeOnlineEachMonthD(
	recId INT AUTO_INCREMENT,
	recYear YEAR NOT NULL,
	recMonth INT NOT NULL,
	d1 INT NOT NULL DEFAULT 0,
	d2 INT NOT NULL DEFAULT 0,
	d3 INT NOT NULL DEFAULT 0,
	d4 INT NOT NULL DEFAULT 0,
	d5 INT NOT NULL DEFAULT 0,
	d6 INT NOT NULL DEFAULT 0,
	d7 INT NOT NULL DEFAULT 0,
	d8 INT NOT NULL DEFAULT 0,
	d9 INT NOT NULL DEFAULT 0,
	d10 INT NOT NULL DEFAULT 0,
	d11 INT NOT NULL DEFAULT 0,
	d12 INT NOT NULL DEFAULT 0,
	d13 INT NOT NULL DEFAULT 0,
	d14 INT NOT NULL DEFAULT 0,
	d15 INT NOT NULL DEFAULT 0,
	d16 INT NOT NULL DEFAULT 0,
	d17 INT NOT NULL DEFAULT 0,
	d18 INT NOT NULL DEFAULT 0,
	d19 INT NOT NULL DEFAULT 0,
	d20 INT NOT NULL DEFAULT 0,
	d21 INT NOT NULL DEFAULT 0,
	d22 INT NOT NULL DEFAULT 0,
	d23 INT NOT NULL DEFAULT 0,
	d24 INT NOT NULL DEFAULT 0,
	d25 INT NOT NULL DEFAULT 0,
	d26 INT NOT NULL DEFAULT 0,
	d27 INT NOT NULL DEFAULT 0,
	d28 INT NOT NULL DEFAULT 0,
	d29 INT NOT NULL DEFAULT 0,
	d30 INT NOT NULL DEFAULT 0,
	d31 INT NOT NULL DEFAULT 0,
	PRIMARY KEY(recId),
	CONSTRAINT yearMonth UNIQUE (recYear,recMonth)
);

-- create table for store private messages analize data accourding to DATE

CREATE TABLE analizePriMsgEachDateH(
	recId INT AUTO_INCREMENT,
	recDate DATE NOT NULL,
	h1 INT NOT NULL DEFAULT 0,
	h2 INT NOT NULL DEFAULT 0,
	h3 INT NOT NULL DEFAULT 0,
	h4 INT NOT NULL DEFAULT 0,
	h5 INT NOT NULL DEFAULT 0,
	h6 INT NOT NULL DEFAULT 0,
	h7 INT NOT NULL DEFAULT 0,
	h8 INT NOT NULL DEFAULT 0,
	h9 INT NOT NULL DEFAULT 0,
	h10 INT NOT NULL DEFAULT 0,
	h11 INT NOT NULL DEFAULT 0,
	h12 INT NOT NULL DEFAULT 0,
	h13 INT NOT NULL DEFAULT 0,
	h14 INT NOT NULL DEFAULT 0,
	h15 INT NOT NULL DEFAULT 0,
	h16 INT NOT NULL DEFAULT 0,
	h17 INT NOT NULL DEFAULT 0,
	h18 INT NOT NULL DEFAULT 0,
	h19 INT NOT NULL DEFAULT 0,
	h20 INT NOT NULL DEFAULT 0,
	h21 INT NOT NULL DEFAULT 0,
	h22 INT NOT NULL DEFAULT 0,
	h23 INT NOT NULL DEFAULT 0,
	h24 INT NOT NULL DEFAULT 0,
	PRIMARY KEY(recId),
	UNIQUE (recDate)
);

-- create table for store private message analized data accouding to MONTH

CREATE TABLE analizePriMsgEachMonthD(
	recId INT AUTO_INCREMENT,
	recYear YEAR NOT NULL,
	recMonth INT NOT NULL,
	d1 INT NOT NULL DEFAULT 0,
	d2 INT NOT NULL DEFAULT 0,
	d3 INT NOT NULL DEFAULT 0,
	d4 INT NOT NULL DEFAULT 0,
	d5 INT NOT NULL DEFAULT 0,
	d6 INT NOT NULL DEFAULT 0,
	d7 INT NOT NULL DEFAULT 0,
	d8 INT NOT NULL DEFAULT 0,
	d9 INT NOT NULL DEFAULT 0,
	d10 INT NOT NULL DEFAULT 0,
	d11 INT NOT NULL DEFAULT 0,
	d12 INT NOT NULL DEFAULT 0,
	d13 INT NOT NULL DEFAULT 0,
	d14 INT NOT NULL DEFAULT 0,
	d15 INT NOT NULL DEFAULT 0,
	d16 INT NOT NULL DEFAULT 0,
	d17 INT NOT NULL DEFAULT 0,
	d18 INT NOT NULL DEFAULT 0,
	d19 INT NOT NULL DEFAULT 0,
	d20 INT NOT NULL DEFAULT 0,
	d21 INT NOT NULL DEFAULT 0,
	d22 INT NOT NULL DEFAULT 0,
	d23 INT NOT NULL DEFAULT 0,
	d24 INT NOT NULL DEFAULT 0,
	d25 INT NOT NULL DEFAULT 0,
	d26 INT NOT NULL DEFAULT 0,
	d27 INT NOT NULL DEFAULT 0,
	d28 INT NOT NULL DEFAULT 0,
	d29 INT NOT NULL DEFAULT 0,
	d30 INT NOT NULL DEFAULT 0,
	d31 INT NOT NULL DEFAULT 0,
	PRIMARY KEY(recId),
	CONSTRAINT yearMonth UNIQUE (recYear,recMonth)
);

-- create table for store private group messages analize data accourding to DATE

CREATE TABLE analizePriGrpMsgEachDateH(
	recId INT AUTO_INCREMENT,
	recDate DATE NOT NULL,
	h1 INT NOT NULL DEFAULT 0,
	h2 INT NOT NULL DEFAULT 0,
	h3 INT NOT NULL DEFAULT 0,
	h4 INT NOT NULL DEFAULT 0,
	h5 INT NOT NULL DEFAULT 0,
	h6 INT NOT NULL DEFAULT 0,
	h7 INT NOT NULL DEFAULT 0,
	h8 INT NOT NULL DEFAULT 0,
	h9 INT NOT NULL DEFAULT 0,
	h10 INT NOT NULL DEFAULT 0,
	h11 INT NOT NULL DEFAULT 0,
	h12 INT NOT NULL DEFAULT 0,
	h13 INT NOT NULL DEFAULT 0,
	h14 INT NOT NULL DEFAULT 0,
	h15 INT NOT NULL DEFAULT 0,
	h16 INT NOT NULL DEFAULT 0,
	h17 INT NOT NULL DEFAULT 0,
	h18 INT NOT NULL DEFAULT 0,
	h19 INT NOT NULL DEFAULT 0,
	h20 INT NOT NULL DEFAULT 0,
	h21 INT NOT NULL DEFAULT 0,
	h22 INT NOT NULL DEFAULT 0,
	h23 INT NOT NULL DEFAULT 0,
	h24 INT NOT NULL DEFAULT 0,
	PRIMARY KEY(recId),
	UNIQUE (recDate)
);

-- create table for store private group message analized data accouding to MONTH

CREATE TABLE analizePriGrpMsgEachMonthD(
	recId INT AUTO_INCREMENT,
	recYear YEAR NOT NULL,
	recMonth INT NOT NULL,
	d1 INT NOT NULL DEFAULT 0,
	d2 INT NOT NULL DEFAULT 0,
	d3 INT NOT NULL DEFAULT 0,
	d4 INT NOT NULL DEFAULT 0,
	d5 INT NOT NULL DEFAULT 0,
	d6 INT NOT NULL DEFAULT 0,
	d7 INT NOT NULL DEFAULT 0,
	d8 INT NOT NULL DEFAULT 0,
	d9 INT NOT NULL DEFAULT 0,
	d10 INT NOT NULL DEFAULT 0,
	d11 INT NOT NULL DEFAULT 0,
	d12 INT NOT NULL DEFAULT 0,
	d13 INT NOT NULL DEFAULT 0,
	d14 INT NOT NULL DEFAULT 0,
	d15 INT NOT NULL DEFAULT 0,
	d16 INT NOT NULL DEFAULT 0,
	d17 INT NOT NULL DEFAULT 0,
	d18 INT NOT NULL DEFAULT 0,
	d19 INT NOT NULL DEFAULT 0,
	d20 INT NOT NULL DEFAULT 0,
	d21 INT NOT NULL DEFAULT 0,
	d22 INT NOT NULL DEFAULT 0,
	d23 INT NOT NULL DEFAULT 0,
	d24 INT NOT NULL DEFAULT 0,
	d25 INT NOT NULL DEFAULT 0,
	d26 INT NOT NULL DEFAULT 0,
	d27 INT NOT NULL DEFAULT 0,
	d28 INT NOT NULL DEFAULT 0,
	d29 INT NOT NULL DEFAULT 0,
	d30 INT NOT NULL DEFAULT 0,
	d31 INT NOT NULL DEFAULT 0,
	PRIMARY KEY(recId),
	CONSTRAINT yearMonth UNIQUE (recYear,recMonth)
);

-- create table for store private group messages analize data accourding to DATE

CREATE TABLE analizePubGrpMsgEachDateH(
	recId INT AUTO_INCREMENT,
	recDate DATE NOT NULL,
	h1 INT NOT NULL DEFAULT 0,
	h2 INT NOT NULL DEFAULT 0,
	h3 INT NOT NULL DEFAULT 0,
	h4 INT NOT NULL DEFAULT 0,
	h5 INT NOT NULL DEFAULT 0,
	h6 INT NOT NULL DEFAULT 0,
	h7 INT NOT NULL DEFAULT 0,
	h8 INT NOT NULL DEFAULT 0,
	h9 INT NOT NULL DEFAULT 0,
	h10 INT NOT NULL DEFAULT 0,
	h11 INT NOT NULL DEFAULT 0,
	h12 INT NOT NULL DEFAULT 0,
	h13 INT NOT NULL DEFAULT 0,
	h14 INT NOT NULL DEFAULT 0,
	h15 INT NOT NULL DEFAULT 0,
	h16 INT NOT NULL DEFAULT 0,
	h17 INT NOT NULL DEFAULT 0,
	h18 INT NOT NULL DEFAULT 0,
	h19 INT NOT NULL DEFAULT 0,
	h20 INT NOT NULL DEFAULT 0,
	h21 INT NOT NULL DEFAULT 0,
	h22 INT NOT NULL DEFAULT 0,
	h23 INT NOT NULL DEFAULT 0,
	h24 INT NOT NULL DEFAULT 0,
	PRIMARY KEY(recId),
	UNIQUE (recDate)
);

-- create table for store private group message analized data accouding to MONTH

CREATE TABLE analizePubGrpMsgEachMonthD(
	recId INT AUTO_INCREMENT,
	recYear YEAR NOT NULL,
	recMonth INT NOT NULL,
	d1 INT NOT NULL DEFAULT 0,
	d2 INT NOT NULL DEFAULT 0,
	d3 INT NOT NULL DEFAULT 0,
	d4 INT NOT NULL DEFAULT 0,
	d5 INT NOT NULL DEFAULT 0,
	d6 INT NOT NULL DEFAULT 0,
	d7 INT NOT NULL DEFAULT 0,
	d8 INT NOT NULL DEFAULT 0,
	d9 INT NOT NULL DEFAULT 0,
	d10 INT NOT NULL DEFAULT 0,
	d11 INT NOT NULL DEFAULT 0,
	d12 INT NOT NULL DEFAULT 0,
	d13 INT NOT NULL DEFAULT 0,
	d14 INT NOT NULL DEFAULT 0,
	d15 INT NOT NULL DEFAULT 0,
	d16 INT NOT NULL DEFAULT 0,
	d17 INT NOT NULL DEFAULT 0,
	d18 INT NOT NULL DEFAULT 0,
	d19 INT NOT NULL DEFAULT 0,
	d20 INT NOT NULL DEFAULT 0,
	d21 INT NOT NULL DEFAULT 0,
	d22 INT NOT NULL DEFAULT 0,
	d23 INT NOT NULL DEFAULT 0,
	d24 INT NOT NULL DEFAULT 0,
	d25 INT NOT NULL DEFAULT 0,
	d26 INT NOT NULL DEFAULT 0,
	d27 INT NOT NULL DEFAULT 0,
	d28 INT NOT NULL DEFAULT 0,
	d29 INT NOT NULL DEFAULT 0,
	d30 INT NOT NULL DEFAULT 0,
	d31 INT NOT NULL DEFAULT 0,
	PRIMARY KEY(recId),
	CONSTRAINT yearMonth UNIQUE (recYear,recMonth)
);

-- public chat room column changed
ALTER TABLE pub_grp_chat DROP COLUMN message;

ALTER TABLE pub_grp_chat
ADD msg TEXT NOT NULL;

-- reason column deleted in pubRoom leave 
ALTER TABLE pub_group_leave DROP COLUMN reason;