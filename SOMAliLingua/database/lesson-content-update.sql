-- Apply after somalingua.sql to enrich lessons in an existing SomaliLingua database.
-- Safe to run more than once: lessons are upserted by slug and quiz questions are checked before insert.
USE somalingua;

UPDATE lessons SET description='Practise greetings, polite replies and a short introduction you can use in a first conversation.', estimated_minutes=15, content='## Learning goals
- Greet someone and ask how they are.
- Say your name and give a simple reply.
- Practise a short exchange from beginning to end.

## Useful phrases
- Salaan — Hello / greetings
- Sidee tahay? — How are you?
- Waan fiicanahay — I am fine
- Adiguna? — And you?
- Mahadsanid — Thank you
- Magacaygu waa Ayaan — My name is Ayaan

## A short conversation
Read the exchange once for meaning. Then read it again aloud, taking turns between the two speakers.

- A: Salaan, sidee tahay? — Hello, how are you?
- B: Waan fiicanahay, mahadsanid. Adiguna? — I am fine, thank you. And you?
- A: Waan fiicanahay. Magacaygu waa Ayaan. — I am fine. My name is Ayaan.

## Notice the pattern
Use Magacaygu waa before a name when introducing yourself. Replace Ayaan with your own name and repeat the complete sentence. In this lesson, learn the phrases as useful chunks rather than trying to change every word.

Greetings and replies can vary with relationship, region and situation. Listen to Somali speakers around you and follow the level of formality they use.

## Practice
- Say Salaan and ask someone Sidee tahay?
- Reply Waan fiicanahay, mahadsanid.
- Introduce yourself with Magacaygu waa followed by your name.

## Check what you remember
Cover the translations and recall the meaning of Sidee tahay?, Mahadsanid and Magacaygu waa. Finish by giving your introduction without reading the example.' WHERE slug='greetings-and-introductions';

UPDATE lessons SET description='Explore the Somali Latin alphabet, vowel length and the consonants that may be new to English speakers.', estimated_minutes=18, content='## Learning goals
- Recognise the 26 base letters in standard written Somali.
- Identify the five vowels and three digraph letters.
- Notice how doubled vowels show vowel length.

## The alphabet at a glance
Standard written Somali uses the Latin alphabet. The base inventory has 21 consonants and five vowels. The letters kh, dh and sh are written with two characters but each is treated as one letter in Somali alphabetical order.

## Vowels and length
- A, E, I, O, U — The five Somali vowels
- aa, ee, ii, oo, uu — Long vowels written by doubling the vowel

Read each short and long vowel pair slowly. Keep the vowel clear and steady; do not add an extra vowel sound after it.

## Consonants to notice
- C — A distinct Somali consonant, not the English letter-name sound
- X — A distinct consonant made farther back in the throat
- Q — A deep consonant made farther back than K
- Dh — A digraph letter with a sound different from plain D
- Kh and Sh — Digraph letters that each represent one Somali letter

Pronunciation varies between speakers. Use these descriptions as a starting point, then listen to fluent speakers or recordings and imitate the sound you hear.

## Practice
Read the example words from the alphabet explorer: Caano, Xoolo, Qalin, Dhul and Shimbir. Point to the first letter in each word. Then find the five vowels in a Somali word you already know.

## Check what you remember
How many base letters are in the standard alphabet? Which three letters are digraphs? How does Somali show a long vowel in writing? Use the alphabet page to review any letter that is still unfamiliar.' WHERE slug='the-somali-alphabet';

UPDATE lessons SET description='Count from one to ten, recognise each number and use them in short everyday examples.', estimated_minutes=15, content='## Learning goals
- Say the Somali numbers from one through ten.
- Recognise the written number words in either order.
- Use a number with a familiar object or person.

## Numbers 1 to 10
- 1 — Kow
- 2 — Laba
- 3 — Saddex
- 4 — Afar
- 5 — Shan
- 6 — Lix
- 7 — Toddoba
- 8 — Sideed
- 9 — Sagaal
- 10 — Toban

## Build a little recall
Read the list once from top to bottom. Hide the Somali words and try again from memory. Then point to a number and say its word without following the list in order.

Number forms may vary slightly by context. Learn each word as it appears in the examples here, and listen for natural usage in conversation.

## Use numbers with familiar words
- Laba qof — Two people
- Saddex arday — Three students
- Shan buug — Five books
- Toban maalmood — Ten days

Say each pair aloud. Notice how the number comes before the noun in these examples.

## Practice
Count five objects near you in Somali. Then count from ten down to one by using the list as a reminder only when needed.

## Check what you remember
What is the Somali word for seven? What number is Lix? Say the full sequence from one to ten, then choose any three numbers and make your own object pairs.' WHERE slug='numbers-one-to-ten';

UPDATE lessons SET description='Talk about close family members and introduce people using short, familiar sentences.', estimated_minutes=16, content='## Learning goals
- Recognise words for family and people.
- Introduce a family member in a simple sentence.
- Practise possessive forms as complete examples.

## Family words
- Hooyo — Mother
- Aabbe — Father
- Walaal — Sibling
- Walaashay — My sister
- Walaalkay — My brother
- Ilmo — Child
- Qoys — Family / household

## Put the words into a sentence
- Tani waa hooyaday. — This is my mother.
- Walaalkay waa arday. — My brother is a student.
- Qoyskaygu waa weyn yahay. — My family is large.

## Notice the pattern
The examples Walaashay and Walaalkay include a possessive ending. Somali words can change depending on the relationship being described. For now, practise each complete example and notice the ending; avoid attaching the same ending to every noun.

## Practice
Choose one family word and make a short introduction using Tani waa... Replace the person in the first example with someone in your family. If the person is not family, use a name and a description you already know.

## Check what you remember
Which word means family? Which word can mean sibling? Say the three example sentences aloud and identify the word that signals whose family member is being described.' WHERE slug='family-and-people';

UPDATE lessons SET description='Learn familiar food and drink words, make a simple request and practise a short meal-time exchange.', estimated_minutes=16, content='## Learning goals
- Recognise common food and drink vocabulary.
- Ask for something with a simple request.
- Reuse a familiar word in a new sentence.

## Food and drink
- Cunto — Food
- Biyo — Water
- Caano — Milk
- Bariis — Rice
- Hilib — Meat
- Rooti — Bread
- Shaah — Tea

## Make a simple request
- Waxaan rabaa biyo. — I want water.
- Waxaan rabaa shaah. — I want tea.
- Rooti iyo shaah. — Bread and tea.
- Fadlan — Please

Use Fadlan to make a request more polite. Repeat Waxaan rabaa..., then substitute biyo or shaah. Keep the whole phrase together while you practise.

## In context
Imagine you are sharing a meal. Name one food and one drink you would like. Listen for the food words around you and use the dictionary to find a new item to add to your list.

## Practice
- Say each of the seven food and drink words aloud.
- Make two requests with Waxaan rabaa...
- Ask a partner to choose a word, then repeat it without looking.

## Check what you remember
What does Biyo mean? Which word means bread? Complete the phrase Waxaan rabaa... with a drink or food of your choice.' WHERE slug='food-and-drink';

UPDATE lessons SET description='Name common places and use familiar sentence patterns to say where you are going.', estimated_minutes=16, content='## Learning goals
- Recognise common places in a town or community.
- Say that you are going to a familiar place.
- Practise listening for place names.

## Places around you
- Guri — House
- Dugsi — School
- Suuq — Market
- Magaalo — City
- Tuulo — Village
- Waddo — Road

## A useful sentence
Waxaan aadayaa suuqa means I am going to the market. Read it in three parts: Waxaan / aadayaa / suuqa. Keep the phrase together as you repeat it.

## Swap the place
The pattern can help you make a new practice sentence. Substitute a place you know, such as dugsi or guri. Treat these examples as practice models; natural wording can change with the place and context.

## Practice
- Point to a place on a map or in your neighborhood and say its Somali word.
- Repeat Waxaan aadayaa suuqa.
- Replace suuqa with another place word and say the new sentence slowly.

## Check what you remember
Which word means school? Which means market? Say one place you know and use it in a sentence about where you are going.' WHERE slug='everyday-places';

UPDATE lessons SET description='Combine words you already know into short statements, requests and simple questions.', estimated_minutes=18, content='## Learning goals
- Use familiar phrases as complete sentences.
- Recognise a few useful question patterns.
- Change one word to make a new practice sentence.

## Model sentences
- Waan imid. — I came / I have arrived.
- Waan tegayaa. — I am going.
- Waxaan rabaa shaah. — I want tea.
- Xaggee baad joogtaa? — Where are you?

## Build from familiar words
Begin with Waxaan rabaa... and choose a food or drink from the previous lesson. Say the result as one complete request. Next, practise Waan tegayaa and name a place you know.

Somali word order and verb forms carry important meaning. Treat the examples as useful patterns rather than rules that work in every situation. A fluent speaker or teacher can help you refine a sentence for a specific context.

## Mini conversation
- A: Xaggee baad joogtaa? — Where are you?
- B: Guriga ayaan joogaa. — I am at home.

Read the conversation slowly, then switch roles. Focus on understanding the question before trying to answer from memory.

## Practice
- Say one statement, one request and one question from this lesson.
- Replace shaah in the request with biyo.
- Ask the question, pause, and answer with a place you know.

## Check what you remember
Which sentence asks where someone is? Which phrase means I want tea? Try to say both without reading the examples.' WHERE slug='simple-everyday-sentences';

UPDATE lessons SET description='Talk about today, yesterday, tomorrow and parts of the day in a simple daily update.', estimated_minutes=16, content='## Learning goals
- Recognise basic words for days and parts of the day.
- Use today, yesterday and tomorrow in a short update.
- Practise saying a simple plan aloud.

## Time words
- Maanta — Today
- Berri — Tomorrow
- Shalay — Yesterday
- Subax — Morning
- Galab — Afternoon
- Habeen — Night

## Put time into context
- Waxaan imaanayaa berri. — I am coming tomorrow.
- Maanta waan shaqaynayaa. — I am working today.
- Shalay roob baa da''ay. — It rained yesterday.

## Make a three-day plan
Use Maanta, Berri and Shalay to describe one thing for each day. Keep each statement short. If you are unsure about a verb form, use the model sentences and focus on the time word.

## Practice
- Say the three day words in order: Shalay, Maanta, Berri.
- Name a time of day: Subax, Galab or Habeen.
- Make one plan for tomorrow using a phrase from this lesson.

## Check what you remember
Which word means tomorrow? Which means afternoon? Say one thing you did yesterday, one thing for today and one plan for tomorrow.' WHERE slug='days-and-time';

INSERT INTO lessons (title,slug,level,description,content,estimated_minutes,is_published,sort_order) VALUES
('Ordering at a cafe','ordering-at-a-cafe','beginner','Practise asking for a drink or snack politely and understanding a simple cafe exchange.','## Learning goals
- Recognise familiar food and drink words in a cafe.
- Make a simple request using Waxaan rabaa.
- Follow a short order and reply exchange.

## Helpful phrases
- Waxaan rabaa shaah. — I want tea.
- Waxaan rabaa biyo. — I want water.
- Fadlan — Please
- Mahadsanid — Thank you
- Haa — Yes
- Maya — No

## A short exchange
- A: Maxaad rabtaa? — What do you want?
- B: Waxaan rabaa shaah, fadlan. — I want tea, please.
- A: Haa. — Yes.
- B: Mahadsanid. — Thank you.

## Make another order
Keep Waxaan rabaa together and replace shaah with biyo, caano or rooti. Add fadlan to make the request polite. These are short practice examples; listen to local speakers to learn the natural rhythm and expressions used in your community.

## Practice
Read the exchange aloud twice. The second time, hide the English meanings. Then choose a different drink and make the request from memory.

## Check what you remember
What does Mahadsanid mean? Which phrase can you use to ask for tea? Make one polite request of your own.','12',1,9),
('At the market','at-the-market','beginner','Use familiar food words and simple questions when shopping for everyday items.','## Learning goals
- Name a few foods you may see at a market.
- Ask about a price and make a simple request.
- Practise a short buyer and seller exchange.

## Useful words and phrases
- Suuq — Market
- Bariis — Rice
- Rooti — Bread
- Hilib — Meat
- Immisa ayuu yahay? — How much is it?
- Waxaan rabaa... — I want...
- Fadlan — Please

## A short exchange
- A: Salaan. — Hello.
- B: Salaan. — Hello.
- A: Immisa ayuu yahay? — How much is it?
- B: Toban. — Ten.
- A: Waxaan rabaa bariis, fadlan. — I want rice, please.

## Practice with substitutions
Say the question, then practise a reply with a number you know. Change bariis to rooti or hilib to make a new request. Number and price expressions can vary with the item and local context; ask a fluent speaker when you need an exact real-world transaction.

## Try it yourself
Imagine buying one item at a market. Greet the seller, ask the price, then name the item you want. Speak slowly and keep each phrase clear.

## Check what you remember
Which word means market? What question asks about the price? Name two food words from this lesson.','14',1,10),
('Daily routines','daily-routines','elementary','Describe a simple day using time words and familiar actions in short connected sentences.','## Learning goals
- Use time words to organise a short daily update.
- Recognise familiar actions in simple sentences.
- Describe two parts of a routine in Somali.

## Time and action words
- Subax — Morning
- Galab — Afternoon
- Habeen — Night
- Shaqaynayaa — Working
- Akhrinayaa — Reading
- Socdaa — Walking / going

## Model sentences
- Subaxdii waan shaqeeyaa. — In the morning I work.
- Galabtii waan akhriyaa. — In the afternoon I read.
- Habeenkii waan nastaa. — At night I rest.

## Notice the sequence
The time word at the start helps the listener follow your routine. Practise each sentence as a complete phrase. The action forms shown are examples; verb endings can change with the subject and context.

## Make your own routine
Choose a time word and one activity. Say one sentence, pause, then add a second sentence for another part of your day. Use an activity you know, or keep the model sentence and change only the time.

## Practice
- Read the three model sentences aloud.
- Hide the English and match each Somali sentence to morning, afternoon or night.
- Describe two parts of your day to a partner.

## Check what you remember
Which word means morning? Which sentence says that someone reads in the afternoon? Say one routine sentence without looking.','16',1,11),
('Getting around town','getting-around-town','elementary','Learn direction words and practise a few short questions for finding a place.','## Learning goals
- Recognise simple direction words.
- Ask where a familiar place is.
- Use a short phrase to explain that you are looking for a place.

## Direction words
- Bidix — Left
- Midig — Right
- Toos — Straight
- Waddo — Road
- Suuq — Market
- Dugsi — School

## Useful questions and phrases
- Xaggee ku yaal suuqa? — Where is the market?
- Waxaan raadinayaa dugsiga. — I am looking for the school.
- Bidix — Left
- Midig — Right
- Toos — Straight

## Follow a simple route
Imagine a familiar route. Start by saying the place you are looking for, then practise one direction at a time: Toos, bidix or midig. These are simple vocabulary prompts, not detailed travel instructions; confirm directions with a local speaker when needed.

## Practice
- Point left, right and straight while saying the matching word.
- Ask where the market is.
- Tell a partner one direction word, then have them repeat it.

## Check what you remember
Which word means straight? Which means left? Say the question for finding the market, then name a place you would like to find.','16',1,12),
('Ask for help and clarification','ask-for-help-and-clarification','elementary','Use polite phrases to ask someone to repeat, slow down or explain a word.', '## Learning goals
- Ask for help politely.
- Request a repetition or slower speech.
- Say when you do not understand.

## Helpful phrases
- Fadlan — Please
- Ma fahmin. — I do not understand.
- Fadlan ku celi. — Please repeat.
- Si tartiib ah, fadlan. — Slowly, please.
- Mahadsanid. — Thank you.

## Use the phrases together
- A: Sidee tahay? — How are you?
- B: Ma fahmin. — I do not understand.
- B: Fadlan ku celi. — Please repeat.
- B: Si tartiib ah, fadlan. — Slowly, please.
- A: Sidee tahay? — How are you?
- B: Mahadsanid. — Thank you.

## A useful learning habit
It is normal to miss a word while listening to a new language. Use one of these phrases, listen again, and repeat the part you understood. Ask a fluent speaker for feedback on pronunciation and when to use a particular expression.

## Practice
- Say Ma fahmin slowly and clearly.
- Ask a partner to repeat a word using Fadlan ku celi.
- Practise Si tartiib ah, fadlan when the speaker talks too quickly.

## Check what you remember
Which phrase asks someone to repeat? How can you ask for slower speech? Practise both phrases without reading.','14',1,13)
ON DUPLICATE KEY UPDATE
  title=VALUES(title), level=VALUES(level), description=VALUES(description),
  content=VALUES(content), estimated_minutes=VALUES(estimated_minutes),
  is_published=VALUES(is_published), sort_order=VALUES(sort_order);

INSERT INTO quiz_questions (lesson_id,question,option_a,option_b,option_c,option_d,correct_option,explanation)
SELECT l.id,q.question,q.option_a,q.option_b,q.option_c,q.option_d,q.correct_option,q.explanation
FROM lessons l
JOIN (
  SELECT 'ordering-at-a-cafe' AS slug,'What does Waxaan rabaa shaah mean?' AS question,'I want tea' AS option_a,'I am going home' AS option_b,'Where is the market?' AS option_c,'Thank you' AS option_d,'a' AS correct_option,'Waxaan rabaa shaah means I want tea.' AS explanation
  UNION ALL SELECT 'ordering-at-a-cafe','Which phrase means please?','Biyo','Fadlan','Berri','Suuq','b','Fadlan means please.'
  UNION ALL SELECT 'at-the-market','What does Suuq mean?','School','House','Market','Road','c','Suuq means market.'
  UNION ALL SELECT 'at-the-market','Which question asks how much something costs?','Sidee tahay?','Immisa ayuu yahay?','Xaggee baad joogtaa?','Magacaygu waa...','b','Immisa ayuu yahay? asks how much it is.'
  UNION ALL SELECT 'daily-routines','Which word means morning?','Subax','Galab','Habeen','Berri','a','Subax means morning.'
  UNION ALL SELECT 'daily-routines','What does waan akhriyaa mean in the lesson example?','I am walking','I am reading','I am resting','I am working','b','Waan akhriyaa means I read.'
  UNION ALL SELECT 'getting-around-town','Which word means left?','Midig','Toos','Bidix','Waddo','c','Bidix means left.'
  UNION ALL SELECT 'getting-around-town','What does Waxaan raadinayaa dugsiga mean?','I am looking for the school','I am going to the market','I want a book','I am at home','a','Waxaan raadinayaa dugsiga means I am looking for the school.'
  UNION ALL SELECT 'ask-for-help-and-clarification','Which phrase asks someone to repeat?','Mahadsanid','Fadlan ku celi','Ma fahmin','Sidee tahay?','b','Fadlan ku celi means please repeat.'
  UNION ALL SELECT 'ask-for-help-and-clarification','What does Si tartiib ah, fadlan ask someone to do?','Speak more slowly','Go to the market','Say your name','Count to ten','a','Si tartiib ah, fadlan means slowly, please.'
) q ON q.slug=l.slug
WHERE NOT EXISTS (
  SELECT 1 FROM quiz_questions existing
  WHERE existing.lesson_id=l.id AND existing.question=q.question
);
