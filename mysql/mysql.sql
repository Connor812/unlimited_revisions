DROP DATABASE IF EXISTS loridb;

CREATE DATABASE loridb;

USE loridb;

-- Set up of tables -------------------------------------------->

CREATE TABLE users (
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE permission (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id int(11) NOT NULL,
    page_num int(11) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE journal_page (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    section_type varchar(30) NOT NULL,
    section_name TEXT NOT NULL,
    order_num int(11) NOT NULL,
    page_num int(11) NOT NULL
);

CREATE INDEX idx_page_num ON journal_page (page_num);

CREATE TABLE page_name (
    id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    page_name VARCHAR(255) NOT NULL,
    page_num INT(11) NOT NULL,
    FOREIGN KEY (page_num) REFERENCES journal_page(page_num) ON DELETE CASCADE
);

CREATE TABLE heading (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    heading_content TEXT NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE subheading (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    subheading_content TEXT NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE quote (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    quote_content TEXT NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE byline (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    byline_content TEXT NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE story_box (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    story_box_userdata_name varchar(100) NOT NULL,
    placeholder_text TEXT NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE video (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    video_src TEXT NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE image (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    image_src TEXT NOT NULL,
    image_text TEXT NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE click_list (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE click_list_items (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    item_type varchar(30) NOT NULL,
    item_title TEXT NOT NULL,
    placeholder_text TEXT DEFAULT NULL,
    item_userdata_name varchar(100) NOT NULL UNIQUE,
    click_list_id int(11) NOT NULL,
    FOREIGN KEY (click_list_id) REFERENCES click_list(id) ON DELETE CASCADE
);

CREATE TABLE bullet (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE bullet_point (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    bullet_content TEXT NOT NULL,
    bullet_id int(11) NOT NULL,
    FOREIGN KEY (bullet_id) REFERENCES bullet(id) ON DELETE CASCADE
);

CREATE TABLE text (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    text_content TEXT NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE comment (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    comment_userdata_name TEXT NOT NULL,
    comment_placeholder TEXT NOT NULL,
    section_id int(11) NOT NULL,
    FOREIGN KEY (section_id) REFERENCES journal_page(id) ON DELETE CASCADE
);

CREATE TABLE user_input (
    id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id int(11) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
-- Seed data  ------------------------------------------------------>

INSERT INTO `users` (`username`, `email`, `password`, `first_name`, `last_name`) VALUES 
('Connor812','connor812@gmail.com','123','Connor','Savoy');

INSERT INTO `permission`(`user_id`, `page_num`) VALUES ('1','1');

INSERT INTO journal_page (`section_type`, `section_name`, `order_num`, `page_num`) VALUES 
('heading', 'heading 1', '1', '1'),
('heading', 'heading 2', '2', '1'),
('heading', 'heading 3', '3', '1'),
('heading', 'heading 4', '4', '1'),
('byline', 'byline 1', '5', '1'),
('byline', 'byline 2', '6', '1'),
('byline', 'byline 3', '7', '1'),
('click_list', 'click_list 1', '8', '1'),
('click_list', 'click_list 2', '9', '1'),
('quote', 'quote 1', '10', '1'),
('quote', 'quote 2', '11', '1'),
('quote', 'quote 3', '12', '1'),
('story_box', 'story_box 1', '13', '1'),
('story_box', 'story_box 2', '14', '1'),
('video', 'video 1', '15', '1'),
('image', 'image 1', "16", '1'),
('text', 'text 1', "17", '1'),
('bullet', 'bullet 1', "18", '1'),
('heading', 'heading 1', '1', '2'),
('heading', 'heading 2', '2', '2'),
('heading', 'heading 3', '3', '2'),
('heading', 'heading 4', '4', '2');


INSERT INTO heading (`heading_content`, `section_id`) VALUES 
('Connors First Heading','1'),
('Connors Second Heading','2'),
('Connors Third Heading','3'),
('Connors Forth Heading','4'),
('Connors First Heading','19'),
('Connors Second Heading','20'),
('Connors Third Heading','21'),
('Connors Forth Heading','22');

INSERT INTO byline (`byline_content`, `section_id`) VALUES 
('byline content 1', '5'),
('byline content 2', '6'),
('byline content 2', '7');

INSERT INTO click_list (`section_id`) VALUES 
('8'),
('9');


INSERT INTO click_list_items (`item_type`, `item_title`, `placeholder_text`, `item_userdata_name`, `click_list_id`) VALUES 
('checkbox', 'click list 1 title 1', NULL, 'types_trauma','1'),
('checkbox', 'click list 1 title 2', NULL, 'types_abuse','1'),
('textarea', 'click list 1 title 3', 'Placeholder text for click list 1 item 3', 'types_textarea', '1'),
('checkbox', 'click list 2 title 1', NULL, 'types_stress','2'),
('checkbox', 'click list 2 title 2', NULL, 'types_care','2'),
('textarea', 'click list 2 title 3', 'Placeholder text for click list 2 item 3', 'types_placeholder', '2');

INSERT INTO quote (`quote_content`, `section_id`) VALUES 
('Quote 1','10'),
('Quote 2','11'),
('Quote 3','12');

INSERT INTO story_box (`story_box_userdata_name`, `placeholder_text`, `section_id`) VALUES 
('stroy_box_data1', 'placeholder text for stroy box 1', '13'),
('stroy_box_data2', 'placeholder text for stroy box 2', '14');

INSERT INTO video (`video_src`, `section_id`) VALUES 
('videos/URmaster.mp4', '15');

INSERT INTO image (`image_src`, `image_text`, `section_id`) VALUES 
('images/book1.png', 'Image Text', '16');

INSERT INTO text (`text_content`, `section_id`) VALUES 
('text 1', '17');

INSERT INTO bullet (`section_id`) VALUES 
('18');

INSERT INTO `bullet_point`(`bullet_content`, `bullet_id`) VALUES 
('Bullet 1','1'),
('Bullet 2','1'),
('Bullet 3','1');

-- Table for the user input ------------------------------------------------------------->

CREATE TABLE user_input (
    
    user_id int(11) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);


-- Main Query to get the ordered list of data -------------------------------------------->

SELECT
    jp.id AS journal_page_id,
    jp.section_type,
    jp.section_name,
    jp.order_num,
    h.id AS heading_id,
    h.heading_content,
    q.id AS quote_id,
    q.quote_content,
    b.id AS byline_id,
    b.byline_content,
    sb.id AS story_box_id,
    sb.story_box_userdata_name,
    sb.placeholder_text,
    v.id AS video_id,
    v.video_src,
    c.id AS click_list_id,
    sh.id AS subheading_id,
    sh.subheading_content,
    i.id AS image_id,
    i.image_src,
    i.image_text,
    cm.id AS comment_id, 
    cm.comment_userdata_name,
    cm.comment_placeholder,
    bt.id AS bullet_id,
    t.id AS text_id,
    t.text_content
FROM journal_page AS jp
LEFT JOIN heading AS h ON jp.id = h.section_id
LEFT JOIN quote AS q ON jp.id = q.section_id
LEFT JOIN byline AS b ON jp.id = b.section_id
LEFT JOIN story_box AS sb ON jp.id = sb.section_id
LEFT JOIN video AS v ON jp.id = v.section_id
LEFT JOIN click_list AS c ON jp.id = c.section_id
LEFT JOIN subheading AS sh ON jp.id = sh.section_id
LEFT JOIN image AS i ON jp.id = i.section_id
LEFT JOIN comment AS cm ON jp.id = cm.section_id
LEFT JOIN bullet AS bt ON jp.id = bt.section_id
LEFT JOIN text AS t ON jp.id = t.section_id
WHERE jp.page_num = ?
ORDER BY jp.order_num ASC;
-- Query for getting the click_list items for the click list

SELECT
    cl.id AS click_list_id,
    cl.section_id AS click_list_section_id,
    cli.id AS click_list_item_id,
    cli.item_type AS click_list_item_type,
    cli.item_title AS item_title,
    cli.placeholder_text AS placeholder_text,
    cli.item_userdata_name AS item_userdata_name
FROM click_list AS cl
LEFT JOIN click_list_items AS cli ON cl.id = cli.click_list_id
WHERE cl.id = ?;

-- Query for getting the story_box paragraphs

-- SELECT sb.id AS story_box_id,
--        sb.img,
--        sb.section_id,
--        sbp.id AS paragraph_id,
--        sbp.paragraph
-- FROM story_box AS sb
-- LEFT JOIN story_box_paragraphs AS sbp ON sb.id = sbp.story_box_id
-- WHERE sb.id = ?;



-- This is how ill fix the order when i delete an item out of a table

UPDATE your_table
SET order_number = order_number - 1
WHERE order_number > 3;

CREATE TABLE `user_input` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `Our_Story` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `My_Concerns` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `communication` tinyint(1) DEFAULT '0',
  `Silence` tinyint(1) DEFAULT '0',
  `Name_calling` tinyint(1) DEFAULT '0',
  `comment_on_dealing_with_stress` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `Story_Ending` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `Former_My_Concerns` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `Former_Communication` tinyint(1) DEFAULT '0',
  `Former_Silence` tinyint(1) DEFAULT '0',
  `Former_Name_Calling` tinyint(1) DEFAULT '0',
  `Former_partner_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `Former_Story_Ending` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `Themes_in_past_relationships` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `Partner_family_role` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `toxic_relationship` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `damaged_self_esteem` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `self_esteem_contributed` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `former_concerns` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `TestCheckBox` tinyint(1) DEFAULT '0',
  `TextAreaTest` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `NewTestBox` tinyint(1) DEFAULT '0',
  `Newcheckboxsection` tinyint(1) DEFAULT '0',
  `Newtextarea` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `TestOne` tinyint(1) DEFAULT '0',
  `TestTwo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `testthree` tinyint(1) DEFAULT '0',
  `Our_Story1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `New_story_Box` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `newstorybox` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `NewStoryBox1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `clickboxone` tinyint(1) DEFAULT '0',
  `textareaone` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `clickboxthree` tinyint(1) DEFAULT '0',
  `current` tinyint(1) DEFAULT '0',
  `About_me` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `How_long_` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `present_relationship` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `like_him` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `likes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `dislikes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `previoius` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `signs_of_toxic_relationship` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `indicators_that_apply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `abuse_indicators_apply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `dissatisfying_relationship` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `medical_stressors` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `legal_issues` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `financial_issues` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `intimacy_issues` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `income` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `stuck` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `have_not_attended_to_myself` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `more_than_one` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `returned` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `attract_same` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `resources_given` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `counselling` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `questions_that_drive_seeking` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `questionone` tinyint(1) DEFAULT '0',
  `questiononecomment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `relationshipbeenin` tinyint(1) DEFAULT '0',
  `relationshipdescription` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `65` tinyint(1) DEFAULT '0',
  `66` tinyint(1) DEFAULT '0',
  `67` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `68` tinyint(1) DEFAULT '0',
  `69` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `70` tinyint(1) DEFAULT '0',
  `71` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `72` tinyint(1) DEFAULT '0',
  `73` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `74` tinyint(1) DEFAULT '0',
  `75` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `76` tinyint(1) DEFAULT '0',
  `77` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `78` tinyint(1) DEFAULT '0',
  `79` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `80` tinyint(1) DEFAULT '0',
  `81` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `82` tinyint(1) DEFAULT '0',
  `83` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `84` tinyint(1) DEFAULT '0',
  `85` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `86` tinyint(1) DEFAULT '0',
  `87` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `88` tinyint(1) DEFAULT '0',
  `89` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `90` tinyint(1) DEFAULT '0',
  `91` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `92` tinyint(1) DEFAULT '0',
  `93` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `94` tinyint(1) DEFAULT '0',
  `95` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `96` tinyint(1) DEFAULT '0',
  `97` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `98` tinyint(1) DEFAULT '0',
  `99` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `100` tinyint(1) DEFAULT '0',
  `101` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `102` tinyint(1) DEFAULT '0',
  `103` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `104` tinyint(1) DEFAULT '0',
  `105` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `106` tinyint(1) DEFAULT '0',
  `107` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `108` tinyint(1) DEFAULT '0',
  `109` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `110` tinyint(1) DEFAULT '0',
  `111` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `112` tinyint(1) DEFAULT '0',
  `113` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `114` tinyint(1) DEFAULT '0',
  `115` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `116` tinyint(1) DEFAULT '0',
  `117` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `118` tinyint(1) DEFAULT '0',
  `119` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `120` tinyint(1) DEFAULT '0',
  `121` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `122` tinyint(1) DEFAULT '0',
  `123` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `124` tinyint(1) DEFAULT '0',
  `125` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `126` tinyint(1) DEFAULT '0',
  `127` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `128` tinyint(1) DEFAULT '0',
  `129` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `130` tinyint(1) DEFAULT '0',
  `131` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `132` tinyint(1) DEFAULT '0',
  `133` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `134` tinyint(1) DEFAULT '0',
  `135` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `136` tinyint(1) DEFAULT '0',
  `137` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `138` tinyint(1) DEFAULT '0',
  `139` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `140` tinyint(1) DEFAULT '0',
  `141` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `142` tinyint(1) DEFAULT '0',
  `143` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `144` tinyint(1) DEFAULT '0',
  `145` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `146` tinyint(1) DEFAULT '0',
  `147` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `148` tinyint(1) DEFAULT '0',
  `149` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `150` tinyint(1) DEFAULT '0',
  `151` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `152` tinyint(1) DEFAULT '0',
  `153` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `154` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `155` tinyint(1) DEFAULT '0',
  `156` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `157` tinyint(1) DEFAULT '0',
  `158` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `159` tinyint(1) DEFAULT '0',
  `160` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `161` tinyint(1) DEFAULT '0',
  `162` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `163` tinyint(1) DEFAULT '0',
  `164` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `165` tinyint(1) DEFAULT '0',
  `166` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `167` tinyint(1) DEFAULT '0',
  `168` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `169` tinyint(1) DEFAULT '0',
  `170` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `171` tinyint(1) DEFAULT '0',
  `172` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `173` tinyint(1) DEFAULT '0',
  `174` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `175` tinyint(1) DEFAULT '0',
  `176` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `177` tinyint(1) DEFAULT '0',
  `178` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `179` tinyint(1) DEFAULT '0',
  `180` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `181` tinyint(1) DEFAULT '0',
  `182` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `183` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `184` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `185` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `186` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `187` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `188` tinyint(1) DEFAULT '0',
  `189` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `190` tinyint(1) DEFAULT '0',
  `191` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `192` tinyint(1) DEFAULT '0',
  `193` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `194` tinyint(1) DEFAULT '0',
  `195` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `196` tinyint(1) DEFAULT '0',
  `197` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `198` tinyint(1) DEFAULT '0',
  `199` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `200` tinyint(1) DEFAULT '0',
  `201` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `202` tinyint(1) DEFAULT '0',
  `203` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `204` tinyint(1) DEFAULT '0',
  `205` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `206` tinyint(1) DEFAULT '0',
  `207` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `208` tinyint(1) DEFAULT '0',
  `209` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `210` tinyint(1) DEFAULT '0',
  `211` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `212` tinyint(1) DEFAULT '0',
  `213` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `214` tinyint(1) DEFAULT '0',
  `215` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `216` tinyint(1) DEFAULT '0',
  `217` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `218` tinyint(1) DEFAULT '0',
  `219` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `220` tinyint(1) DEFAULT '0',
  `221` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `222` tinyint(1) DEFAULT '0',
  `223` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `224` tinyint(1) DEFAULT '0',
  `225` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `226` tinyint(1) DEFAULT '0',
  `227` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `228` tinyint(1) DEFAULT '0',
  `229` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `230` tinyint(1) DEFAULT '0',
  `231` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `232` tinyint(1) DEFAULT '0',
  `233` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `234` tinyint(1) DEFAULT '0',
  `235` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `236` tinyint(1) DEFAULT '0',
  `237` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `238` tinyint(1) DEFAULT '0',
  `239` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `240` tinyint(1) DEFAULT '0',
  `241` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `242` tinyint(1) DEFAULT '0',
  `243` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `244` tinyint(1) DEFAULT '0',
  `245` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `246` tinyint(1) DEFAULT '0',
  `247` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `248` tinyint(1) DEFAULT '0',
  `249` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `250` tinyint(1) DEFAULT '0',
  `251` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `252` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `253` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `254` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `255` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `256` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `257` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `258` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `259` tinyint(1) DEFAULT '0',
  `260` text COLLATE utf8mb4_general_ci,
  `261` tinyint(1) DEFAULT '0',
  `262` text COLLATE utf8mb4_general_ci,
  `263` text COLLATE utf8mb4_general_ci,
  `264` text COLLATE utf8mb4_general_ci,
  `265` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `story_box` (
  `id` int NOT NULL AUTO_INCREMENT,
  `story_box_userdata_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `placeholder_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `section_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `click_list_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `item_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `placeholder_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `item_userdata_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `click_list_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=404 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_c

CREATE TABLE `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `comment_userdata_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `comment_placeholder` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `section_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci