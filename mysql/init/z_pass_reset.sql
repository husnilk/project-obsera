USE portal_24012008;
UPDATE t_user SET tusrPassword=md5('password') WHERE 1=1;
