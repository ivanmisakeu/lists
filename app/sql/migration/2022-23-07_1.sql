
-- remove all old delted items bcs from now app will track deleted time

DELETE FROM `items` WHERE deleted = 1;