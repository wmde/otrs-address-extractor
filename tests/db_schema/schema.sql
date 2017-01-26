CREATE TABLE `ticket` (
  `id`                       INTEGER PRIMARY KEY,
  `tn`                       VARCHAR(50) NOT NULL,
  `title`                    VARCHAR(255)         DEFAULT NULL,
  `queue_id`                 INT(11)     NOT NULL,
  `ticket_lock_id`           SMALLINT(6) NOT NULL,
  `type_id`                  SMALLINT(6)          DEFAULT NULL,
  `service_id`               INT(11)              DEFAULT NULL,
  `sla_id`                   INT(11)              DEFAULT NULL,
  `user_id`                  INT(11)     NOT NULL,
  `responsible_user_id`      INT(11)     NOT NULL,
  `ticket_priority_id`       SMALLINT(6) NOT NULL,
  `ticket_state_id`          SMALLINT(6) NOT NULL,
  `customer_id`              VARCHAR(150)         DEFAULT NULL,
  `customer_user_id`         VARCHAR(250)         DEFAULT NULL,
  `timeout`                  INT(11)     NOT NULL,
  `until_time`               INT(11)     NOT NULL,
  `escalation_time`          INT(11)     NOT NULL,
  `escalation_update_time`   INT(11)     NOT NULL,
  `escalation_response_time` INT(11)     NOT NULL,
  `escalation_solution_time` INT(11)     NOT NULL,
  `archive_flag`             SMALLINT(6) NOT NULL DEFAULT '0',
  `create_time_unix`         BIGINT(20)  NOT NULL,
  `create_time`              TEXT    NOT NULL,
  `create_by`                INT(11)     NOT NULL,
  `change_time`              TEXT    NOT NULL,
  `change_by`                INT(11)     NOT NULL
);

CREATE TABLE `users` (
  `id`          INTEGER PRIMARY KEY,
  `login`       VARCHAR(200) NOT NULL,
  `pw`          VARCHAR(64)  NOT NULL,
  `title`       VARCHAR(50) DEFAULT NULL,
  `first_name`  VARCHAR(100) NOT NULL,
  `last_name`   VARCHAR(100) NOT NULL,
  `valid_id`    SMALLINT(6)  NOT NULL,
  `create_time` TEXT         NOT NULL,
  `create_by`   INT(11)      NOT NULL,
  `change_time` TEXT         NOT NULL,
  `change_by`   INT(11)      NOT NULL
);

CREATE TABLE `ticket_history` (
  `id`              INTEGER PRIMARY KEY,
  `name`            VARCHAR(200) NOT NULL,
  `history_type_id` SMALLINT(6)  NOT NULL,
  `ticket_id`       BIGINT(20)   NOT NULL,
  `article_id`      BIGINT(20) DEFAULT NULL,
  `type_id`         SMALLINT(6)  NOT NULL,
  `queue_id`        INT(11)      NOT NULL,
  `owner_id`        INT(11)      NOT NULL,
  `priority_id`     SMALLINT(6)  NOT NULL,
  `state_id`        SMALLINT(6)  NOT NULL,
  `create_time`     TEXT     NOT NULL,
  `create_by`       INT(11)      NOT NULL,
  `change_time`     TEXT     NOT NULL,
  `change_by`       INT(11)      NOT NULL
);