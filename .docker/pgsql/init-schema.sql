ALTER ROLE web IN DATABASE m SET search_path TO magazine;
SET search_path TO magazine;
CREATE SCHEMA magazine AUTHORIZATION web;
