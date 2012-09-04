SELECT * , COUNT( `object_id` ) AS count
FROM `omeka_record_relations_relations`
WHERE property_id =98
GROUP BY `object_id`
LIMIT 0 , 30


SELECT *, COUNT(`object_id`) as obj_count FROM `omeka_record_relations_relations` WHERE property_id = 98 GROUP BY `object_id` ORDER BY obj_count DESC



SELECT COUNT( `object_id` ) AS obj_count, * 
FROM `omeka_record_relations_relations`
INNER JOIN `omeka_mla_tei_element_commentary_notes` AS `cn` ON cn.id = object_id
WHERE property_id =97
GROUP BY `object_id`
ORDER BY obj_count DESC
LIMIT 10