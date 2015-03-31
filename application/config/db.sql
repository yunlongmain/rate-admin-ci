-- 配置表

-- ----------------------------
--  Table structure for contest
-- ----------------------------
DROP TABLE IF EXISTS contest;
CREATE TABLE contest (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  description varchar(255) NOT NULL DEFAULT '',
  rateRule varchar(100) NOT NULL, -- 评分规则
  online tinyint(1) not null default 0, -- 0：不上线；1：上线；
  PRIMARY KEY (id),
  key(online)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for team
-- ----------------------------
DROP TABLE IF EXISTS team;
CREATE TABLE team (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  contestId int(10) unsigned NOT NULL,
  name varchar(50) NOT NULL,
  teamDisplayId int(10) unsigned NOT NULL, -- 团队展示用的id
  description varchar(255) NOT NULL DEFAULT '',
  appName varchar(255) NOT NULL DEFAULT '',
  appDesc varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  key(contestId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for rater
-- ----------------------------
DROP TABLE IF EXISTS rater;
CREATE TABLE rater (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL DEFAULT '',
  username varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  role tinyint(1) not null default 0, -- 0：普通用户；1：管理员 2：超级管理员；
  contestAuth int unsigned NOT NULL , -- 普通用户 可以评分的比赛id，管理员不受限
  description varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  key(contestAuth),
  unique(username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for rate
-- ----------------------------
DROP TABLE IF EXISTS rate;
CREATE TABLE rate(
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  detail varchar(255) NOT NULL DEFAULT '',
  subId int(10) unsigned NOT NULL default 0,
  weight tinyint not null default 20, -- 0-100之间 例如 20为权重20%
  score tinyint not null default 100, -- 0-100之间 例如 20为权重20%
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ---------------------------- --
-- 用户表

-- ----------------------------
--  Table structure for rate of team
-- ----------------------------
DROP TABLE IF EXISTS user_rate;
CREATE TABLE user_rate(
  teamId int(10) unsigned NOT NULL,
  contestId int(10) unsigned NOT NULL,
  raterId int(10) unsigned NOT NULL,
  rateDetail varchar(255) NOT NULL default '', -- id1:score1,id2:score2...
  score tinyint unsigned NOT NULL,
  valid tinyint(1) not null default 1, -- 0：无效；1：有效
  ctime timestamp NOT NULL default CURRENT_TIMESTAMP,	-- 创建时间
  utime timestamp NOT NULL default 0,	-- 最后修改时间
  PRIMARY KEY (raterId,teamId),
  key(contestId),
  key(teamId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;