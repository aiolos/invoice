CREATE TABLE `owners` (
`id` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 100 ) NOT NULL ,
`street` VARCHAR( 100 ) NOT NULL ,
`housenumber` VARCHAR( 10 ) NOT NULL ,
`postalcode` VARCHAR( 8 ) NOT NULL ,
`city` VARCHAR( 100 ) NOT NULL ,
`country` VARCHAR( 2 ) NOT NULL ,
`gender` VARCHAR( 2 ) NOT NULL,
PRIMARY KEY (  `id` )
);

--INSERT INTO album (`name`, `street`, `housenumber`,`postalcode`, `city`, `country`, `gender`)
--    VALUES  ('name',  'Somestreet', '123', '1234AB', 'Amsterdam', 'NL', 'M');