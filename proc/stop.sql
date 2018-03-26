SELECT 
/*
max(st_distance(`t`.`ushape`,ST_GeomFromText('POINT(59.5160608 36.3185627)'))) mds,
id
*/
*
,
max(TIME_TO_SEC(TIMEDIFF(regtime,'2017-08-28 18:31:13'))/60) tt
,
max(st_distance(ushape,ST_GeomFromText('POINT(59.5160608 36.3185627)')))*1000 mds
FROM 
track
WHERE 
user_id=222 and 
USER_SPEED<=9 and 
`id`>2818 and 
/*
TIME_TO_SEC(TIMEDIFF(regtime,'2017-08-28 18:31:13'))/60>=20 and
*/
date(regtime)='2017-08-28' 
order by id desc 
/*
limit 1
*/