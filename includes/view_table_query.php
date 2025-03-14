<?php
//---------------------- vu_category_map ---------------------------

// ALTER VIEW vu_category_map AS (SELECT cm.cat_id, cm.sub_group_ids, cm.cm_type, cm.supplier_id, pro.pro_id, pro.manf_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pg.pg_mime_source_url FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = cm.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE pg.pg_mime_source_url IS NOT NULL AND pg.pg_mime_source_url <> '');
// ALTER VIEW vu_category_map AS (SELECT cm.cat_id, cm.sub_group_ids, cm.cm_type, cm.supplier_id, pro.pro_id, pro.pro_status, pro.manf_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pbp.pbp_tax, pg.pg_mime_source_url FROM category_map AS cm LEFT OUTER JOIN products AS pro ON pro.supplier_id = cm.supplier_id AND pro.pro_status = '1' LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = cm.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) WHERE pro.pro_status = '1');

//---------------------- vu_best_selling_product -------------------

// CREATE VIEW vu_best_selling_product AS (SELECT DISTINCT oi.supplier_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount, pbp.pbp_price_amount AS pbp_price_without_tax, pg.pg_mime_source_url FROM order_items AS oi LEFT JOIN products AS pro ON pro.supplier_id = oi.supplier_id LEFT JOIN products_bundle_price AS pbp ON pbp.supplier_id = oi.supplier_id AND pbp.pbp_lower_bound = '1' LEFT JOIN products_gallery AS pg ON pg.supplier_id = oi.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' JOIN (SELECT supplier_id FROM order_items GROUP BY supplier_id HAVING COUNT(*) >= 1 ORDER BY RAND() LIMIT 12) AS random_suppliers ON random_suppliers.supplier_id = oi.supplier_id);

//---------------------- vu_products -------------------------------

// ALTER VIEW vu_products AS (SELECT pro.pro_id, pro.supplier_id, pro.pro_manufacture_aid, pro.pro_ean, pro.manf_id, pro.pro_description_short, cm.sub_group_ids, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM products AS pro LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1');
// ALTER VIEW vu_products AS (SELECT pro.pro_id, pro.supplier_id, pro.pro_manufacture_aid, pro.pro_ean, pro.manf_id, pro.pro_description_short, cm.sub_group_ids, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM products AS pro LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1' WHERE pg.pg_mime_source_url IS NOT NULL AND pg.pg_mime_source_url <> '');
// ALTER VIEW vu_products AS (SELECT pro.pro_id, pro.supplier_id, pro.pro_manufacture_aid, pro.pro_ean, pro.manf_id, pro.pro_description_short, cm.sub_group_ids, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM products AS pro LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1));
// ALTER VIEW vu_products AS (SELECT pro.pro_id, pro.supplier_id, pro.pro_status, pro.pro_manufacture_aid, pro.pro_ean, pro.manf_id, pro.pro_description_short, pro.pro_udx_seo_internetbezeichung, pro.pro_udx_seo_epag_id, pro.pro_udx_seo_selection_feature, cm.sub_group_ids, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax, pbp.pbp_tax,  pg.pg_mime_source_url, CONCAT(pro.pro_description_short, ' ',COALESCE(NULLIF(pf.pf_fvalue, ''), '')) AS pro_description_short_new FROM products AS pro LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = pro.supplier_id LEFT OUTER JOIN category AS c ON c.group_id = cm.cat_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = pro.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = pro.supplier_id AND pg.pg_mime_source_url = (SELECT pg_inner.pg_mime_source_url FROM products_gallery AS pg_inner WHERE pg_inner.supplier_id = pro.supplier_id AND pg_inner.pg_mime_purpose = 'normal' ORDER BY pg_inner.pg_mime_source_url ASC LIMIT 1) LEFT OUTER JOIN products_feature AS pf ON pf.supplier_id = pro.supplier_id AND pf.pf_fname = 'Verwendung für Druckertyp' WHERE pro.pro_status = '1')

//---------------------- vu_left_products_feature -------------------------------

//CREATE VIEW vu_left_products_feature AS (SELECT * FROM products_feature AS pf WHERE pf.pf_fvalue_details = 'FILTER' AND pf.pf_fname NOT IN ('Made in Germany','Material der Sitzfläche', 'Packungsmenge', 'Material der Schreibfläche', 'Farbe des Rückens', 'Oberflächenbeschaffenheit', 'Fadenverstärkung vorhanden', 'Ausführung der Oberflächenbeschaffenheit', 'Farbe der Vorderseite', 'Material der Rückseite', 'Gehäusefarbe', 'Material des Rahmens', 'Trägermaterial', 'Deckel vorhanden', 'Material', 'Ausführung der Oberseite', 'Material des Papierhandtuches', 'Material des Tisches', 'Ablageschale vorhanden', 'max. Anzahl der Erweiterungshüllen', 'Motiv', 'Werkstoff', 'Zertifikat/Zulassung', 'Verwendung für Druck- oder Schreibgerät', '3 Klappen (Jurisklappen) am Unterdeckel vorhanden', 'feucht abwischbar', 'Anordnung der Lage (Öffnungsseite)', 'Ausführung der Tür', 'Material des Hygienebeutels', 'stapelbar', 'selbstklebend', 'Verschluss', 'Ausführung der Höhenverstellung', 'Boden vorhanden', 'max. Auflösung', 'Tafel beschreibbar', 'beidseitig beschreibbar', 'Weißgrad (ISO)', 'Verschlusstechnik', 'Weißgrad (CIE)', 'Lichtleistung', 'Breite des Sitzes', 'Kalenderaufteilung', 'Fenster vorhanden', 'Haftungsintensität', 'Volumen', 'Körnung', 'Heftleistung', 'Art des Auftragungshilfsmittels', 'Ausführung der vorderseitigen Lineatur', 'Rückenbreite', 'Typbezeichnung des Duftes', 'Fassungsvermögen', 'Taben', 'Grammatur', 'Dicke der Folie', 'Heftungsart', 'Auffangvolumen', 'Ausführung der Landkarte', 'Sterilität', 'Lochung', 'Arbeitsbreite', 'Kerndurchmesser', 'Anzahl der Teile', 'max. Aufbewahrungsmenge', 'Format der Folie', 'Maße der Oberfläche', 'Art des Laminierverfahrens', 'Innenmaße', 'Heftklammertyp', 'Einsatzbereich', 'max. Tragfähigkeit', 'Abmessung des Rahmens', 'Typbezeichnung'))
//---------------------- vu_wishlist -------------------------------

//CREATE VIEW vu_wishlist AS(SELECT wl.*, cm.cat_id, cm.sub_group_ids, cm.cm_type, pro.pro_id, pro.pro_description_short, (pbp.pbp_price_amount + (pbp.pbp_price_amount * pbp.pbp_tax)) AS pbp_price_amount,  pbp.pbp_price_amount AS pbp_price_without_tax,  pg.pg_mime_source_url FROM wishlist AS wl LEFT OUTER JOIN category_map AS cm ON cm.supplier_id = wl.supplier_id LEFT OUTER JOIN products AS pro ON pro.supplier_id = wl.supplier_id LEFT OUTER JOIN products_bundle_price AS pbp ON pbp.supplier_id = wl.supplier_id AND pbp.pbp_lower_bound = '1' LEFT OUTER JOIN products_gallery AS pg ON pg.supplier_id = wl.supplier_id AND pg.pg_mime_purpose = 'normal' AND pg.pg_mime_order = '1');
?>