DROP DATABASE IF EXISTS posse;
CREATE DATABASE posse;
USE posse;

DROP TABLE IF EXISTS studies;
CREATE TABLE studies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  studied_date DATETIME null,
  studyhours INT null
) CHARSET=utf8;

DROP TABLE IF EXISTS contents;
CREATE TABLE contents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  studies_id INT null,
  content VARCHAR(255) null,
  studyhours FLOAT null
) CHARSET=utf8;

DROP TABLE IF EXISTS languages;
CREATE TABLE languages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  studies_id INT null,
  language VARCHAR(255) null,
  studyhours FLOAT null
) CHARSET=utf8;

insert into studies (studied_date, content, language, studyhours) values
("2022-02-24", "POSSE課題", "PHP", 4),
("2022-02-25", "ドットインストール", "Laravel", 8),
("2022-02-26", "POSSE課題, N予備校", "CSS", 2),
("2022-02-27", "ドットインストール", "Laravel", 3),
("2022-02-28", "POSSE課題, ドットインストール", "PHP, CSS", 7),
("2022-03-01", "N予備校", "CSS, SHELL", 6),
("2022-03-02", "POSSE課題", "HTML, CSS", 1),
("2022-03-03", "POSSE課題", "JavaScript", 2),
("2022-03-04", "N予備校, ドットインストール", "HTML, SQL", 3);