CREATE TABLE articles (
  id          int(10) UNSIGNED AUTO_INCREMENT NOT NULL,
  createDate  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  price       decimal(7,2) UNSIGNED NOT NULL,
  content     text NOT NULL,
  authorId    mediumint(8) UNSIGNED NOT NULL,
  parentId    int(10) UNSIGNED,
  PRIMARY KEY (id)
) ENGINE = MyISAM;

CREATE TABLE author (
  id   mediumint(8) UNSIGNED AUTO_INCREMENT NOT NULL,
  fio  char(50) NOT NULL,
  PRIMARY KEY (id)
) ENGINE = MyISAM;

/* Indexes */
CREATE INDEX authorIndex
  ON author
  (id, fio);