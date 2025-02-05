<?php
//---------------------- vu_category_map ---------------------------

// CREATE VIEW vu_category_map AS (SELECT cm.cat_id, cm.sub_group_ids, cm.cm_type, cm.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1');

//---------------------- vu_best_selling_product -------------------

// CREATE VIEW vu_best_selling_product AS (SELECT DISTINCT oi.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pg.pg_mime_source_url FROM order_items AS oi LEFT JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT JOIN products_bundle_price AS pbp ON pbp.supplier_id = oi.supplier_id AND pbp.pbp_lower_bound = '1' LEFT JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' JOIN (SELECT supplier_id FROM order_items GROUP BY supplier_id HAVING COUNT(*) >= 1 ORDER BY RAND() LIMIT 12) AS random_suppliers ON random_suppliers.supplier_id = oi.supplier_id);

//---------------------- vu_products -------------------------------

// CREATE VIEW vu_products AS (SELECT pro.pro_id, pro.supplier_id, pro.pro_description_short, cm.sub_group_ids, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source FROM products AS pro LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1');
?>