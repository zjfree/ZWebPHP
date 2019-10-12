/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     2019-10-12 14:59:25                          */
/*==============================================================*/


drop table if exists client;

drop table if exists sys_config;

drop table if exists sys_log;

drop table if exists sys_no;

drop table if exists user;

drop table if exists user_login;

drop table if exists user_type;

/*==============================================================*/
/* Table: client                                                */
/*==============================================================*/
create table client
(
   id                   int not null auto_increment,
   code                 varchar(255) not null default '',
   name                 varchar(255) not null default '',
   client_type          int not null default 0,
   status               int not null default 0,
   config_json          longtext,
   img_url              varchar(255) not null default '',
   add_time             datetime not null default '2000-01-01',
   update_time          datetime not null default '2000-01-01',
   memo                 varchar(2048) not null default '',
   primary key (id)
);

/*==============================================================*/
/* Table: sys_config                                            */
/*==============================================================*/
create table sys_config
(
   id                   int not null auto_increment,
   type                 varchar(255) not null default '',
   code                 varchar(255) not null default '',
   value                varchar(2048) not null default '',
   add_time             datetime not null default '2000-01-01',
   update_time          datetime not null default '2000-01-01',
   primary key (id)
);

/*==============================================================*/
/* Table: sys_log                                               */
/*==============================================================*/
create table sys_log
(
   id                   int not null auto_increment,
   user_id              int not null default 0,
   user_name            varchar(255) not null default '',
   user_type            varchar(255) not null default '',
   type                 varchar(255) not null default '',
   content              varchar(2048) not null default '',
   add_time             datetime not null default '2000-01-01',
   primary key (id)
);

/*==============================================================*/
/* Table: sys_no                                                */
/*==============================================================*/
create table sys_no
(
   id                   int not null auto_increment,
   code                 varchar(255) not null default '',
   value                int not null default 0,
   primary key (id)
);

/*==============================================================*/
/* Table: user                                                  */
/*==============================================================*/
create table user
(
   id                   int not null auto_increment,
   guid                 varchar(255) not null default '',
   user_type_id         int not null default 0,
   name                 varchar(255) not null default '',
   account              varchar(255) not null default '',
   password             varchar(255) not null default '',
   status               int not null default 0,
   config_json          longtext,
   notebook             varchar(2048) not null default '',
   phone                varchar(255) not null default '',
   email                varchar(255) not null default '',
   weixin               varchar(255) not null default '',
   add_time             datetime not null default '2000-01-01',
   update_time          datetime not null default '2000-01-01',
   memo                 varchar(2048) not null default '',
   primary key (id)
);

/*==============================================================*/
/* Table: user_login                                            */
/*==============================================================*/
create table user_login
(
   id                   int not null auto_increment,
   user_id              int not null default 0,
   guid                 varchar(255) not null default '',
   IP                   varchar(255) not null default '',
   user_agent           varchar(512) not null default '',
   online_status        int not null default 0 comment '0 - 未知; 1 - 在线; 2 - 离线; 3 - 作废;',
   last_time            datetime not null default '2000-01-01',
   view_count           int not null default 0,
   reload               int not null default 0,
   add_time             datetime not null default '2000-01-01',
   primary key (id)
);

/*==============================================================*/
/* Table: user_type                                             */
/*==============================================================*/
create table user_type
(
   id                   int not null auto_increment,
   code                 varchar(255) not null default '',
   name                 varchar(255) not null default '',
   power                longtext,
   menu                 longtext,
   is_hide              int not null default 0,
   add_time             datetime not null default '2000-01-01',
   update_time          datetime not null default '2000-01-01',
   memo                 varchar(2048) not null default '',
   primary key (id)
);

