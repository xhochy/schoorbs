-- Schoorbs table creation script - for PostgreSQL
--
-- Notes:
-- MySQL inserts the current date/time into any timestamp field which is not
-- specified on insert. To get the same effect, use PostgreSQL default
-- value current_timestamp.
--
-- If you have decided to change the prefix of your tables from 'schoorbs_'
-- to something else then you must edit each 'CREATE TABLE' and 'create index'
-- line below.

CREATE TABLE schoorbs_area
(
  id                serial primary key,
  area_name         varchar(30),
  area_admin_email  text
);

CREATE TABLE schoorbs_room
(
  id                serial primary key,
  area_id           int DEFAULT 0 NOT NULL,
  room_name         varchar(25) DEFAULT '' NOT NULL,
  description       varchar(60),
  capacity          int DEFAULT 0 NOT NULL,
  room_admin_email  text
);

CREATE TABLE schoorbs_entry
(
  id          serial primary key,
  start_time  int DEFAULT 0 NOT NULL,
  end_time    int DEFAULT 0 NOT NULL,
  entry_type  int DEFAULT 0 NOT NULL,
  repeat_id   int DEFAULT 0 NOT NULL,
  room_id     int DEFAULT 1 NOT NULL,
  timestamp   timestamp DEFAULT current_timestamp,
  create_by   varchar(80) DEFAULT '' NOT NULL,
  name        varchar(80) DEFAULT '' NOT NULL,
  type        char DEFAULT 'E' NOT NULL,
  description text
);
create index idxStartTime on schoorbs_entry(start_time);
create index idxEndTime on schoorbs_entry(end_time);

CREATE TABLE schoorbs_repeat
(
  id          serial primary key,
  start_time  int DEFAULT 0 NOT NULL,
  end_time    int DEFAULT 0 NOT NULL,
  rep_type    int DEFAULT 0 NOT NULL,
  end_date    int DEFAULT 0 NOT NULL,
  rep_opt     varchar(32) DEFAULT '' NOT NULL,
  room_id     int DEFAULT 1 NOT NULL,
  timestamp   timestamp DEFAULT current_timestamp,
  create_by   varchar(80) DEFAULT '' NOT NULL,
  name        varchar(80) DEFAULT '' NOT NULL,
  type        char DEFAULT 'E' NOT NULL,
  description text,
  rep_num_weeks smallint DEFAULT NULL NULL
);
