-- DayQuest seed data. Places/distances/descriptions come from the Project Proposal, Table 2.
-- !! Coordinates are APPROXIMATE. Verify each on openstreetmap.org (right-click > Show address).
-- !! opening_hours / entrance_fee are 'TBC' and visit_duration values are estimates: verify all.
USE dayquest;

INSERT INTO category (category_id, category_name, is_outdoor) VALUES
 (1,'Nature',1),(2,'Religious',0),(3,'Heritage',0),(4,'Cultural',0),(5,'Recreation',1),(6,'Wildlife',1);

INSERT INTO place (place_id,name,category_id,description,opening_hours,visit_duration,latitude,longitude,entrance_fee,distance_from_panadura,travel_tips,accessibility_info) VALUES
 (1,'Panadura Beach',1,'A quieter, less-crowded stretch of coastline with calm waters close to the town centre; popular for beach walks, sunbathing and photography.','TBC',90,6.710000,79.893000,'TBC',2.0,'TBC: swim only where safe; check currents.','TBC'),
 (2,'Rankoth Viharaya',2,'A Buddhist temple in Panadura with a 200-plus-year-old image house adorned with fresco paintings; site of the historic Panadura Debate.','TBC',45,6.713000,79.904000,'TBC',3.0,'Dress modestly; remove shoes and hats.','TBC'),
 (3,'Wadduwa Beach',1,'Palm-fringed golden-sand beach south of Panadura with calmer waters than the main tourist beaches further south; popular for swimming and relaxing.','TBC',120,6.665000,79.928000,'TBC',6.0,'TBC','TBC'),
 (4,'Bolgoda Lake',1,'The largest natural lake in the country, covering an area of 374 square kilometers.','TBC',90,6.770000,79.920000,'TBC',8.0,'TBC','TBC'),
 (5,'Pearl Bay (Bolgoda)',5,'A lakeside recreation spot on Bolgoda Lake offering boating and water-sport activities with scenic sunset views.','TBC',120,6.753000,79.932000,'TBC',9.0,'TBC: plan around sunset.','TBC'),
 (6,'Sri Lanka Air Force Museum',4,'Museum at Ratmalana displaying historic aircraft, uniforms and memorabilia tracing the history of the Sri Lanka Air Force.','TBC',90,6.819000,79.883000,'TBC',13.0,'TBC','TBC'),
 (7,'Bellanwila Raja Maha Viharaya',2,'A historic Buddhist temple beside the Bellanwila wetland, known for its architecture and as a major site during the Duruthu Perahera festival.','TBC',45,6.833000,79.883000,'TBC',15.0,'Dress modestly; remove shoes and hats.','TBC'),
 (8,'Kalutara Bodhiya',2,'Iconic hollow dagoba on the Kalutara river bank, famous for its unique interior murals and views over the Kalu Ganga estuary from the Galle Road bridge.','TBC',45,6.585000,79.960000,'TBC',16.0,'Dress modestly; remove shoes and hats.','TBC'),
 (9,'Richmond Castle',3,'An early-20th-century colonial mansion near Kalutara built by a wealthy mudaliyar, blending Edwardian and traditional Sri Lankan architectural styles.','TBC',45,6.579000,79.956000,'TBC',17.0,'TBC: check if open to visitors.','TBC'),
 (10,'Pothupitiya Beach',1,'A relatively secluded beach north of Panadura offering a quieter alternative to the busier coastal stretches, good for a short seaside stop.','TBC',60,6.790000,79.880000,'TBC',12.0,'Secluded: add a safety note for solo visitors (e.g. avoid after dusk).','TBC'),
 (11,'Dehiwala Zoo',6,'National Zoological Gardens.','TBC',180,6.856000,79.876000,'TBC',15.0,'TBC','TBC');

INSERT INTO template (template_id, template_name) VALUES (1,'Solo Explorer'),(2,'Family Fun Day');
INSERT INTO template_place (template_id, place_id, order_index) VALUES
 (1,2,1),(1,8,2),(1,9,3),(1,3,4),
 (2,6,1),(2,11,2),(2,7,3);
