-- SomaliLingua database. Import this file in phpMyAdmin.
CREATE DATABASE IF NOT EXISTS somalingua CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE somalingua;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS quiz_attempts;
DROP TABLE IF EXISTS user_progress;
DROP TABLE IF EXISTS quiz_questions;
DROP TABLE IF EXISTS vocabulary;
DROP TABLE IF EXISTS alphabet;
DROP TABLE IF EXISTS lessons;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('student','admin') NOT NULL DEFAULT 'student',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE lessons (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  level ENUM('beginner','elementary','intermediate') NOT NULL DEFAULT 'beginner',
  description VARCHAR(500) NOT NULL DEFAULT '',
  content MEDIUMTEXT NOT NULL,
  estimated_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 10,
  is_published TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE alphabet (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  letter VARCHAR(4) NOT NULL UNIQUE,
  letter_name VARCHAR(80) NOT NULL DEFAULT '',
  pronunciation VARCHAR(255) NOT NULL DEFAULT '',
  example_word VARCHAR(120) NOT NULL DEFAULT '',
  example_meaning VARCHAR(180) NOT NULL DEFAULT '',
  audio_path VARCHAR(255) DEFAULT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE vocabulary (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  somali VARCHAR(150) NOT NULL,
  english VARCHAR(180) NOT NULL,
  definition TEXT,
  example_sentence VARCHAR(500) DEFAULT NULL,
  category VARCHAR(80) NOT NULL DEFAULT 'General',
  audio_path VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_vocabulary_somali (somali),
  INDEX idx_vocabulary_english (english)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE quiz_questions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lesson_id INT UNSIGNED NOT NULL,
  question TEXT NOT NULL,
  option_a VARCHAR(255) NOT NULL,
  option_b VARCHAR(255) NOT NULL,
  option_c VARCHAR(255) NOT NULL,
  option_d VARCHAR(255) NOT NULL,
  correct_option ENUM('a','b','c','d') NOT NULL,
  explanation TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_question_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE user_progress (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  item_type ENUM('lesson','alphabet','vocabulary') NOT NULL,
  item_id INT UNSIGNED NOT NULL,
  completed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_progress (user_id,item_type,item_id),
  CONSTRAINT fk_progress_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_progress_type (item_type,item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE quiz_attempts (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  lesson_id INT UNSIGNED NOT NULL,
  score SMALLINT UNSIGNED NOT NULL,
  total_questions SMALLINT UNSIGNED NOT NULL,
  percentage TINYINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_attempt_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_attempt_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
  INDEX idx_attempt_user (user_id,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO alphabet (letter,letter_name,pronunciation,example_word,example_meaning,sort_order) VALUES
('B','Ba','Voiced /b/ sound, as in English “book”.','Biyo','Water',1),
('T','Ta','A clear /t/ sound.','Tufaax','Apple',2),
('J','Ja','A sound similar to the “j” in “jam”.','Jilib','Knee',3),
('X','Xa','A pharyngeal sound; it is not the English “x”.','Xoolo','Livestock',4),
('Kh','Kha','A rasping sound similar to the “ch” in Scottish “loch”.','Khalad','Mistake',5),
('D','Da','A voiced /d/ sound.','Dugsi','School',6),
('R','Ra','A tapped or trilled /r/ sound.','Roob','Rain',7),
('S','Sa','An /s/ sound.','Subag','Butter',8),
('Sh','Sha','A sound similar to “sh” in “ship”.','Shimbir','Bird',9),
('Dh','Dha','A retroflex /dh/ sound, distinct from plain d.','Dhul','Land',10),
('C','Cayn','A pharyngeal consonant, often unfamiliar to English speakers.','Caano','Milk',11),
('G','Ga','A hard /g/ sound, as in “go”.','Guri','House',12),
('F','Fa','An /f/ sound.','Faras','Horse',13),
('Q','Qa','A deep /q/ sound produced farther back in the mouth.','Qalin','Pen',14),
('K','Ka','A /k/ sound.','Kalluun','Fish',15),
('L','La','An /l/ sound.','Libaax','Lion',16),
('M','Miim','An /m/ sound.','Madaxweyne','President',17),
('N','Nuun','An /n/ sound.','Nin','Man',18),
('W','Waaw','A /w/ sound.','Waraabe','Hyena',19),
('H','Ha','An /h/ sound.','Hilib','Meat',20),
('Y','Ya','A /y/ sound, as in “yes”.','Yar','Small',21),
('A','Alif','Short open vowel /a/.','Aabbe','Father',22),
('E','E','Vowel /e/, as in Somali “eber”.','Eber','Zero',23),
('I','I','Vowel /i/, as in “nin”.','Ilmo','Child',24),
('O','O','Vowel /o/, as in “roob”.','Orad','Running',25),
('U','U','Vowel /u/, as in “buug”.','Ukun','Egg',26);

INSERT INTO lessons (title,slug,level,description,content,estimated_minutes,is_published,sort_order) VALUES
('Greetings and introductions','greetings-and-introductions','beginner','Learn common greetings, polite expressions and how to introduce yourself.','In Somali, a common greeting is “Salaan”. You may also hear “Sidee tahay?” when asking someone how they are.\n\nUseful expressions:\n\nSalaan — Hello / greetings\nSidee tahay? — How are you?\nWaan fiicanahay — I am fine\nMahadsanid — Thank you\nMagacaygu waa… — My name is…\n\nTry introducing yourself: “Salaan. Magacaygu waa [your name].” Speak slowly and repeat the phrases until they feel natural.\n\nPractice tip: greetings can vary by context and relationship. Listen to native speakers and notice how the expressions are used in conversation.',10,1,1),
('The Somali alphabet','the-somali-alphabet','beginner','Understand the Somali Latin alphabet, including its distinctive consonants and vowel sounds.','Standard written Somali uses the Latin alphabet. The base inventory has 26 letters: 21 consonants and five vowels. Three consonants are written as digraphs: kh, dh and sh. Each digraph represents one letter in Somali alphabetical order.\n\nThe vowels are A, E, I, O and U. Somali also marks long vowels by doubling the vowel: aa, ee, ii, oo and uu.\n\nSome consonants, especially C, X, Q and Dh, have sounds that may be unfamiliar to English speakers. Use the alphabet page to explore examples and add native recordings as you develop the course.',12,1,2),
('Numbers 1–10','numbers-one-to-ten','beginner','Learn to recognise and use the first ten Somali numbers.','Numbers are useful in everyday situations such as shopping, counting and telling quantities.\n\n1 — Kow\n2 — Laba\n3 — Saddex\n4 — Afar\n5 — Shan\n6 — Lix\n7 — Toddoba\n8 — Sideed\n9 — Sagaal\n10 — Toban\n\nRead each number aloud. Then count objects around you in Somali. Repeat the sequence forward and backward.',10,1,3),
('Family and people','family-and-people','beginner','Learn words for close family members and people in your community.','Family vocabulary is useful in introductions and everyday conversation.\n\nHooyo — Mother\nAabbe — Father\nWalaal — Sibling\nWalaashay — My sister\nWalaalkay — My brother\nIlmo — Child\nQoys — Family\n\nExamples:\nTani waa hooyaday — This is my mother.\nWalaalkay waa arday — My brother is a student.\n\nNotice that Somali words can change with possessive endings. Learn the examples as complete expressions before studying the grammar in more detail.',12,1,4),
('Food and drink','food-and-drink','beginner','Explore common food and drink vocabulary.','Food words are useful at home, in shops and when sharing a meal.\n\nCunto — Food\nBiyo — Water\nCaano — Milk\nBariis — Rice\nHilib — Meat\nRooti — Bread\nShaah — Tea\n\nExample: Waxaan rabaa biyo — I want water.\n\nPractice by naming foods and drinks you see during the day. Use the dictionary to look up additional words.',12,1,5),
('Everyday places','everyday-places','beginner','Learn words for familiar places and locations.','These words help you talk about where people go and where things are.\n\nGuri — House\nDugsi — School\nSuuq — Market\nMagaalo — City\nTuulo — Village\nWaddo — Road\n\nExample: Waxaan aadayaa suuqa — I am going to the market.\n\nRepeat each word, then make a short sentence about a place you visit regularly.',12,1,6),
('Simple everyday sentences','simple-everyday-sentences','beginner','Combine familiar words to communicate simple ideas.','Start with short statements and questions.\n\nWaan imid — I came / I have arrived.\nWaan tegayaa — I am going.\nWaxaan rabaa shaah — I want tea.\nXaggee baad joogtaa? — Where are you?\n\nWord order and verb forms are important in Somali. Treat these as useful model sentences. Repeat them and substitute a familiar word to make new sentences.',15,1,7),
('Days and time','days-and-time','beginner','Learn basic expressions for days and time.','Time expressions help you arrange meetings and describe routines.\n\nMaanta — Today\nBerri — Tomorrow\nShalay — Yesterday\nSubax — Morning\nGalab — Afternoon\nHabeen — Night\n\nExample: Waxaan imaanayaa berri — I am coming tomorrow.\n\nPractise describing what you did yesterday, what you are doing today and what you will do tomorrow.',12,1,8);

INSERT INTO vocabulary (somali,english,definition,example_sentence,category) VALUES
('Salaan','Hello / greetings','A greeting or expression of greeting.','Salaan, sidee tahay?','Greetings'),
('Mahadsanid','Thank you','A polite expression of gratitude.','Mahadsanid caawimadaada.','Greetings'),
('Haa','Yes','An affirmative answer.','Haa, waan fahmay.','Everyday'),
('Maya','No','A negative answer.','Maya, mahadsanid.','Everyday'),
('Fadlan','Please','A polite word used when making a request.','Fadlan, ii kaalay.','Greetings'),
('Biyo','Water','Water, an essential drink.','Waxaan rabaa biyo.','Food and drink'),
('Caano','Milk','Milk.','Ilmuhu caano ayuu cabbay.','Food and drink'),
('Bariis','Rice','Rice, commonly served as a meal.','Bariis ayaan cunayaa.','Food and drink'),
('Hilib','Meat','Meat used as food.','Hilib ayaan iibsanayaa.','Food and drink'),
('Rooti','Bread','Bread.','Rooti iyo shaah.','Food and drink'),
('Shaah','Tea','Tea, commonly served as a drink.','Shaah ma rabtaa?','Food and drink'),
('Hooyo','Mother','A mother.','Hooyaday waa guriga.','Family'),
('Aabbe','Father','A father.','Aabbahay waa macallin.','Family'),
('Walaal','Sibling','A brother or sister; also used as a familiar form of address.','Walaalkay wuu yimid.','Family'),
('Qoys','Family','A family or household.','Qoyskaygu waa weyn yahay.','Family'),
('Guri','House','A home or house.','Gurigu waa weyn yahay.','Places'),
('Dugsi','School','A school or place of learning.','Ardaydu waxay joogaan dugsiga.','Places'),
('Suuq','Market','A market or marketplace.','Waxaan aadayaa suuqa.','Places'),
('Magaalo','City','A town or city.','Muqdisho waa magaalo weyn.','Places'),
('Buug','Book','A book.','Waxaan akhrinayaa buug.','Education'),
('Qalin','Pen','A pen or writing instrument.','Qalin baan haystaa.','Education'),
('Arday','Student','A learner or student.','Ardaydu way wax baranayaan.','Education'),
('Macallin','Teacher','A teacher or instructor.','Macallinku cashar ayuu bixinayaa.','Education'),
('Wanaagsan','Good','Good, fine or well, depending on context.','Maalin wanaagsan.','Descriptions'),
('Yar','Small / young','Small in size or young in age.','Gurigu waa yar yahay.','Descriptions'),
('Weyn','Big / large','Large in size or importance.','Geed weyn.','Descriptions'),
('Maanta','Today','The current day.','Maanta waan shaqaynayaa.','Time'),
('Berri','Tomorrow','The day after today.','Berri ayaan imaanayaa.','Time'),
('Shalay','Yesterday','The day before today.','Shalay roob baa da''ay.','Time'),
('Kow','One','The number one.','Hal buug / buug kow.','Numbers'),
('Laba','Two','The number two.','Laba qof ayaa yimid.','Numbers'),
('Saddex','Three','The number three.','Saddex arday.','Numbers'),
('Afar','Four','The number four.','Afar maalmood.','Numbers'),
('Shan','Five','The number five.','Shan buug.','Numbers'),
('Lix','Six','The number six.','Lix qof.','Numbers'),
('Toddoba','Seven','The number seven.','Toddoba maalmood.','Numbers'),
('Sideed','Eight','The number eight.','Sideed arday.','Numbers'),
('Sagaal','Nine','The number nine.','Sagaal buug.','Numbers'),
('Toban','Ten','The number ten.','Toban qof.','Numbers');

INSERT INTO quiz_questions (lesson_id,question,option_a,option_b,option_c,option_d,correct_option,explanation) VALUES
(1,'What does “Mahadsanid” mean?','Good morning','Thank you','Goodbye','Please','b','Mahadsanid is a common way to say thank you.'),
(1,'How do you say “My name is…”?','Magacaygu waa…','Waan fiicanahay','Sidee tahay?','Mahadsanid','a','Magacaygu waa… is used to introduce your name.'),
(1,'What does “Sidee tahay?” ask?','Where are you?','What is your name?','How are you?','How old are you?','c','Sidee tahay? means How are you?'),
(2,'How many base letters are in the standard Somali Latin alphabet?','21','24','26','32','c','The standard base inventory is 26 letters: 21 consonants and five vowels.'),
(2,'Which group contains the Somali digraph letters?','ch, th, ph','kh, dh, sh','sh, ch, gh','dh, th, kh','b','Kh, dh and sh are each treated as one letter.'),
(2,'How is a long vowel commonly written in Somali?','With an accent','By doubling the vowel','With a silent e','By adding h','b','Vowel length is written by doubling the vowel, for example aa or ee.'),
(3,'What is the Somali word for two?','Kow','Laba','Saddex','Afar','b','Laba means two.'),
(3,'What is the Somali word for ten?','Toban','Sagaal','Sideed','Toddoba','a','Toban means ten.'),
(4,'What does “Hooyo” mean?','Father','Sibling','Mother','Child','c','Hooyo means mother.'),
(4,'What does “Qoys” mean?','School','Family','Market','House','b','Qoys means family or household.');
