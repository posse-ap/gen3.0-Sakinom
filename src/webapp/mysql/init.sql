DROP DATABASE IF EXISTS posse;
CREATE DATABASE posse;
USE posse;

DROP TABLE IF EXISTS studies;
CREATE TABLE studies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  studied_date DATETIME null,
  content VARCHAR(255) null,
  language VARCHAR(255) null,
  studyhours INT null
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


SELECT DATE_FORMAT(`studied_date`, '%Y-%m-%d') as `studied_day`, sum(studyhours) as studyhours from studies group by studied_date;

SELECT DATE_FORMAT(`studied_date`, '%Y-%m') as `studied_month`, sum(studyhours) as studyhours from studies group by studied_month;

SELECT sum(studyhours) as `total` from studies;


SELECT DATE_FORMAT(calendar.ymd, '%d') as day, case when sum(studyhours) is not null then sum(studyhours) else 0 end as time from studies
right outer join (
select
d.ymd as ymd
from(
select
date_format(date_add(date_add(last_day( now()), interval - day(last_day(now())) DAY ) , interval td.add_day DAY ), '%Y-%m-%d' ) as ymd
from(
select
0 as add_day
from
dual
where
( @num := 1 - 1 ) * 0
union all
select
@num := @num + 1 as add_day
from
`information_schema`.columns limit 31
) as td
) as d
where month(d.ymd) = month(now())
order by d.ymd ) as calendar
on calendar.ymd = studies.studied_date
group by calendar.ymd having DATE_FORMAT(calendar.ymd, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') order by calendar.ymd



SELECT DATE_FORMAT(`studied_date`, '%Y-%m-%d') as `studied_day`, sum(studyhours) as studyhours from studies group by studied_date having studied_day = DATE_FORMAT(CURDATE(), '%Y-%m-%d')

SELECT DATE_FORMAT(calendar.ymd, '%d') as studied_day, case when sum(studyhours) is not null then sum(studyhours) else 0 end as studyhours from studies
right outer join (
select
d.ymd as ymd
from(
select
date_format(date_add(date_add(last_day( now()), interval - day(last_day(now())) DAY ) , interval td.add_day DAY ), '%Y-%m-%d' ) as ymd
from(
select
0 as add_day
from
dual
where
( @num := 1 - 1 ) * 0
union all
select
@num := @num + 1 as add_day
from
`information_schema`.columns limit 31
) as td
) as d
where month(d.ymd) = month(now())
order by d.ymd ) as calendar
on calendar.ymd = studies.studied_date
group by calendar.ymd 
having studied_day = DATE_FORMAT(CURDATE(), '%e')


select studied_date, count(language), sum(studyhours)/count(language) from studies group by studied_date;

select case when language is not null then language else 'その他' end as language, sum(studyhours)/count(studied_date) from studies group by language;

SET @language = CONCAT_WS(',', 'HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'SQL', 'SHELL', 'その他');
SELECT @language;

select studied_date, language from studies where FIND_IN_SET(language, @language) order by studied_date;

select languages.language, sum(languages.studyhours) as studyhours from studies join languages on studies.id = languages.studies_id group by language;