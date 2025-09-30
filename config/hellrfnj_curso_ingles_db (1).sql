-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: mysql:3306
-- Tiempo de generación: 30-09-2025 a las 19:20:44
-- Versión del servidor: 5.7.44
-- Versión de PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hellrfnj_curso_ingles_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exercises`
--

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `exercise_type` enum('choose_word','complete_sentence','translate','listen_and_write') NOT NULL,
  `question` text COMMENT 'La pregunta o instrucción principal',
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'Para opciones de respuesta en formato: ["op1", "op2"]',
  `correct_answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `exercises`
--

INSERT INTO `exercises` (`id`, `lesson_id`, `exercise_type`, `question`, `options`, `correct_answer`) VALUES
(23, 5, 'choose_word', '(Escoge y escucha la palabra correcta) _________. My name is María', '[\"Hello\",\"I Am\",\"Am\",\"I\"]', 'Hello'),
(24, 5, 'complete_sentence', '(Escoge  la palabra correcta) Hi, What&#39;s your _________?', '[\"Name\",\"Age\",\"City\",\"Last Name\"]', 'Name'),
(25, 5, 'translate', 'Traduce esta frase (¿Cuál es tu nombre?)', NULL, 'What&#39;s your name?'),
(26, 5, 'listen_and_write', 'Escribe la frase que escuchas', NULL, 'Hello, My name is Maria'),
(27, 6, 'choose_word', 'I am fine, _________ you', '[\"And\",\"Thank\",\"He\",\"Good\"]', 'And'),
(28, 6, 'complete_sentence', 'Completa la frase (Hello, Pedro. How _________ you?)', '[\"Are\",\"He\",\"She\",\"We\"]', 'Are'),
(29, 6, 'translate', 'Traduce esta frase (¿Cómo estás?)', NULL, 'How are you?'),
(30, 6, 'listen_and_write', 'Escribe la frase que escuchas', NULL, 'How are you? I am great!'),
(31, 7, 'choose_word', '¿Cuál de estas palabras significa mamá en inglés?', '[\"Mother\",\"Father\",\"Sister\",\"Brother\"]', 'Mother'),
(32, 7, 'complete_sentence', 'Completa la frase (The place where you live is the ___.)', '[\"House\",\"Door\",\"Window\",\"Sister\"]', 'House'),
(33, 7, 'translate', 'Traduce (ventana)', NULL, 'Window'),
(34, 7, 'listen_and_write', 'Escribe la frase que escuchas', NULL, 'I love my mother'),
(35, 8, 'choose_word', '¿Cuál de estas palabras significa (tener) en inglés?', '[\"Have\",\"Has\",\"Am\",\"Is\"]', 'Have'),
(36, 8, 'complete_sentence', 'Completa la frase (I have a ___.)', '[\"Dog\",\"Sun\",\"Tree\",\"Cloud\"]', 'Dog'),
(37, 8, 'translate', 'Traducir la palabra (Libro) al inglés', NULL, 'Book'),
(38, 8, 'listen_and_write', 'Escribe la frase que escuchas', NULL, 'I have a dog'),
(39, 9, 'choose_word', '¿Cuál de estas palabras es el número 15 en inglés?', '[\"Fifteen\",\"Five\",\"Forty\",\"Five-teen\"]', 'Fifteen'),
(40, 9, 'translate', 'Translate the word (doce) to English.', NULL, 'Twelve'),
(41, 9, 'listen_and_write', 'Escribe los números que escuchas (en letras)', NULL, 'one two five'),
(42, 9, 'complete_sentence', 'Completa la serie con el número que sigue, 18, 19, ___', '[\"Twenty\",\"Twenty-one\",\"Twenty-two\",\"Twenty-five\"]', 'Twenty'),
(43, 10, 'choose_word', '¿Cuál de estas frases se utiliza para preguntar la hora en inglés?', '[\"What time is it?\",\"How are you?\",\"Where is the clock?\",\"How old are you?\"]', 'What time is it?'),
(44, 10, 'complete_sentence', 'Mira el reloj y completa la oración con el número correcto, después de las dos. (It&#39;s ___ o&#39;clock.)', '[\"Three\",\"Four\",\"One\",\"Five\"]', 'Three'),
(45, 10, 'translate', 'Traduce la palabra (reloj) al Ingles', NULL, 'Clock'),
(46, 10, 'listen_and_write', 'Tu profesor dirá una de las siguientes frases (It&#39;s one o&#39;clock.), (It&#39;s six o&#39;clock), (It is ten o clock) o (It&#39;s twelve o&#39;clock). Escucha atentamente y escribe la oración completa que escuchaste.', NULL, 'It is ten o clock'),
(47, 11, 'choose_word', '¿Cuál de estas palabras es el mes de agosto en inglés?', '[\"August\",\"April\",\"January\",\"June\"]', 'August'),
(48, 11, 'complete_sentence', 'Completa la serie con el día de la semana correcto. Monday, Tuesday, ___, Thursday', '[\"Wednesday\",\"Friday\",\"Sunday\",\"Saturday\"]', 'Wednesday'),
(49, 11, 'translate', 'Traduce la palabra (sábado) al inglés.', NULL, 'Saturday'),
(50, 11, 'listen_and_write', 'Escribe la frase que escuchas', NULL, 'My birthday is in May'),
(51, 12, 'choose_word', 'Completa la frase (The ball is ___ the table.)', '[\"Under\",\"On\",\"In\",\"Next to\"]', 'Under'),
(52, 12, 'complete_sentence', 'The dog is ___ the box', '[\"In\",\"On\",\"Under\",\"Behind\"]', 'In'),
(53, 12, 'translate', 'Traduce la palabra (encima) al Ingles', NULL, 'On'),
(54, 12, 'listen_and_write', 'Escribe la frase que escuchas', NULL, 'The cat is in the box'),
(55, 13, 'choose_word', '¿Cuál de estas palabras completa la pregunta correctamente? (___ are you from?)', '[\"Where\",\"What\",\"Who\",\"When\"]', 'Where'),
(56, 13, 'complete_sentence', 'Completa la oración con la palabra correcta. (I am ___ Colombia.)', '[\"From\",\"In\",\"On\",\"Under\"]', 'From'),
(57, 13, 'translate', 'Traduce la frase (¿De dónde eres?) al inglés.', NULL, 'Where are you from?'),
(58, 13, 'listen_and_write', 'Escribe la frase que escuchas', NULL, 'I am from Bogotá'),
(59, 14, 'choose_word', 'Completa la frase (I ___ fruit.)', '[\"Like\",\"Am\",\"Don\'t\",\"Has\"]', 'Like'),
(60, 14, 'complete_sentence', 'Completa la oración con la frase correcta de la lección.  (___ chocolate.)', '[\"I like\",\"I have\",\"I am\",\"He is\"]', 'I like'),
(61, 14, 'translate', 'Traduce la frase (Me gusta la música) al inglés.', NULL, ' I like music'),
(62, 14, 'listen_and_write', 'Escribe la frase que escuchas', NULL, 'I like coffee'),
(63, 15, 'choose_word', '¿Cuál de estas palabras significa (arroz) en inglés?', '[\"Rice\",\"Beans\",\"Chicken\",\"Bread\"]', 'Rice'),
(64, 15, 'complete_sentence', 'Completa la frase (I like to eat ___)', '[\"Chicken\",\"Bread\",\"Cheese\",\"Beans\"]', 'Chicken'),
(65, 15, 'translate', 'Traduce la palabra (Queso) al Ingles', NULL, 'Cheese'),
(66, 15, 'listen_and_write', 'Escribe la frase que escuchas', NULL, 'I like to eat rice'),
(67, 16, 'choose_word', 'Completa la frase (My ___ is six years old)', '[\"brother\",\"house\",\"door\",\"bed\"]', 'brother'),
(68, 16, 'complete_sentence', 'I like to ___ rice.', '[\"eat\",\"drink\",\"have\",\"from\"]', 'eat'),
(69, 16, 'translate', 'Traduce (I have a car)', NULL, 'Tengo un carro'),
(70, 16, 'listen_and_write', 'Escribe los números que escucha', NULL, 'Five Ten Fifteen Twenty');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `video_url` varchar(255) DEFAULT NULL,
  `pdf_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `lessons`
--

INSERT INTO `lessons` (`id`, `level_id`, `title`, `description`, `video_url`, `pdf_url`) VALUES
(5, 9, '¡Hola! Soy Juan. ¿Y tú? (Saludos y Presentaciones)', 'Aprende a saludar y presentarte en inglés de forma simple. Con solo unas pocas palabras, podrás iniciar una conversación básica y conocer a otras personas. ¡Es el primer paso para hablar inglés!\r\nVideo de referencia: Mira y escucha este video varias veces. Repite en voz alta y practica la pronunciación de los saludos.\r\n', 'https://www.youtube.com/watch?v=8M8Wamg__bY', ''),
(6, 10, 'How are you? (Feelings and Short Answers)', 'Ahora que sabes saludar y presentarte es hora de preguntar cómo se siente la otra persona. Con esta lección aprenderás a preguntar cómo estás y a responder con frases sencillas.\r\n', 'https://www.youtube.com/watch?v=5wwcacU83EU', ''),
(7, 11, 'My family and my house (Basic Vocabulary)', 'En esta lección aprenderás a nombrar a los miembros de tu familia y algunas cosas sencillas que encuentras en tu casa. Conocer estas palabras te ayudará a hablar de las personas y el lugar que más quieres.\r\n', 'https://youtu.be/24GWC1dDyUM', ''),
(8, 12, 'I have a dog. (The verb to have)', 'El verbo (to have) significa (tener). Con él puedes hablar de las cosas que posees como una casa una bicicleta o incluso una mascota. Aprenderlo te ayudará a describir lo que tienes.\r\n', 'https://www.youtube.com/watch?v=gFaLojX1xMc', ''),
(9, 13, 'Numbers from 1 to 20', 'En esta lección, aprenderás a contar del 1 al 20 en inglés. Saber los números es esencial para muchas cosas, como dar un número de teléfono o entender la hora. ¡Es un paso muy importante!', 'https://www.youtube.com/watch?v=o7AJW-B2FRA', ''),
(10, 14, 'What time is it? (Hours on the hour)', 'En esta lección, aprenderás a preguntar y a decir la hora en punto. Es muy útil para saber a qué hora es la novela, el partido de fútbol o para acordar una cita con un amigo.', 'https://www.youtube.com/watch?v=3cwzRNZf0w0', ''),
(11, 15, 'Days of the week and months', 'En esta lección, aprenderás a nombrar los días de la semana y los meses del año. Esto te ayudará a hablar de tu cumpleaños y a entender cuándo ocurren eventos importantes. ¡Es clave para planear el futuro!', 'https://www.youtube.com/watch?v=5o9rgTQCYtU', ''),
(12, 16, 'Simple prepositions (in, on, under)', 'En esta lección, aprenderás a usar tres palabras muy útiles para decir dónde están las cosas. Con in, on y under, podrás describir la ubicación de objetos de forma sencilla y clara.', 'https://www.youtube.com/watch?v=mJsppGdO-bI', ''),
(13, 17, 'I am from Colombia. (Nationality and Cities)', 'Aprende a decir de dónde eres y a preguntar a los demás sobre su país o ciudad de origen. Es perfecto para hacer amigos y para cuando conozcas a gente nueva.', 'https://www.youtube.com/watch?v=OuLP0PXmgKk', ''),
(14, 18, 'El verbo (to like)', 'Prende a expresar tus gustos de forma muy simple usando el verbo (to like) (gustar). Podrás decir si te gusta la comida, la música o un deporte de forma directa y sencilla.', 'https://www.youtube.com/watch?v=iqniLGlPx2Y', ''),
(15, 19, 'What do you like to eat? (Food)', 'Aprende a nombrar alimentos comunes y a hablar de lo que te gusta comer. Es una lección deliciosa y muy útil para usar todos los días, ya sea en casa o cuando vas a la tienda.\r\n', 'https://www.youtube.com/watch?v=GWElwZGZaLo', ''),
(16, 20, 'Final Exam: A Great Review! (General Practice)', '¡Llegamos al final! En esta lección, vamos a repasar y a usar todo lo que aprendiste. Es el momento de poner en práctica tus conocimientos para conversar, jugar y ver que ahora sí puedes comunicarte en inglés.', 'https://www.youtube.com/watch?v=Z6GGAQOMX8c', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `levels`
--

CREATE TABLE `levels` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text,
  `summary_points` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'Puntos clave del resumen del nivel en formato JSON',
  `level_order` int(11) NOT NULL COMMENT 'Para ordenar los niveles del 1 al 12',
  `Traduccion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `levels`
--

INSERT INTO `levels` (`id`, `title`, `description`, `summary_points`, `level_order`, `Traduccion`) VALUES
(9, 'Hello! I&#39;m Juan. And you? (Greetings and Introductions)', 'Aprende a saludar y presentarte en inglés de forma simple. Con solo unas pocas palabras, podrás iniciar una conversación básica y conocer a otras personas. ¡Es el primer paso para hablar inglés!\r\nVideo de referencia: Mira y escucha este video varias veces. Repite en voz alta y practica la pronunciación de los saludos.\r\n', '[{\"title\":\"Saludos B\\u00e1sicos\",\"desc\":\"Hello  y Hi son las formas m\\u00e1s comunes de saludar. Hello es un poco m\\u00e1s formal, mientras que Hi es m\\u00e1s informal y amigable. Puedes usarlos en cualquier momento del d\\u00eda.\"},{\"title\":\"Mi nombre es...\",\"desc\":\"I&#39;m... (Yo soy...) My name is... (Mi nombre es...) Ambas son correctas y se usan todo el tiempo. Elige la que m\\u00e1s te guste para empezar.\"},{\"title\":\"\\u00bfY el tuyo?\",\"desc\":\"Si quieres saber el nombre de otra persona solo tienes que preguntar What is your name? \\u00bfCu\\u00e1l es tu nombre?. Es la pregunta m\\u00e1s importante de esta lecci\\u00f3n.\"}]', 1, '¡Hola! Soy Juan. ¿Y tú? (Saludos y Presentaciones)'),
(10, 'How are you? (Feelings and Short Answers)', 'Ahora que sabes saludar y presentarte es hora de preguntar cómo se siente la otra persona. Con esta lección aprenderás a preguntar cómo estás y a responder con frases sencillas.\r\n', '[{\"title\":\"La pregunta How are you?\",\"desc\":\"Esta es la pregunta que usamos para saber c\\u00f3mo se siente alguien. Se traduce como \\u00bfC\\u00f3mo est\\u00e1s?. Es una de las preguntas m\\u00e1s comunes en ingl\\u00e9s, \\u00a1as\\u00ed que \\u00fasala mucho!\"},{\"title\":\"Respuestas positivas\",\"desc\":\"I am fine thank you, Estoy bien, gracias y I am good, Estoy bien son respuestas muy usadas. I am great! \\u00a1Estoy genial! Se usa cuando te sientes muy bien y con mucha energ\\u00eda.\"},{\"title\":\"Respuestas simples\",\"desc\":\"No siempre estamos bien y es correcto decirlo. Si est\\u00e1s cansado puedes decir: I am tired, Estoy cansado. Si est\\u00e1s triste, I am sad, Estoy triste. Estas respuestas cortas son muy \\u00fatiles para empezar.\"}]', 2, '¿Cómo estás? (Sentimientos y Respuestas Cortas)'),
(11, 'My family and my house (Basic Vocabulary)', 'En esta lección aprenderás a nombrar a los miembros de tu familia y algunas cosas sencillas que encuentras en tu casa. Conocer estas palabras te ayudará a hablar de las personas y el lugar que más quieres.\r\n', '[{\"title\":\"Nombres de la familia\",\"desc\":\"Conocer a tu familia es muy importante. En inglés usamos palabras sencillas como (mother) mamá (father) papá (brother) hermano y (sister) hermana para referirnos a ellos. Memorizar estas palabras es el primer paso para hablar de tus seres queridos.\"},{\"title\":\"Mi casa mi mundo\",\"desc\":\"Tu casa es el lugar donde vives. Aprender a decir (house) casa (door) puerta y (window) ventana es un excelente comienzo. Estas son palabras que usarás todos los días.\"},{\"title\":\"Practica practica practica\",\"desc\":\"La mejor manera de aprender este vocabulario es usándolo. Cuando estés en tu casa nombra los objetos y a tu familia en inglés. Repetir las palabras en voz alta te ayudará a recordarlas más fácilmente.\"}]', 3, 'Mi familia y mi casa (Vocabulario Básico)'),
(12, 'I have a dog. The verb (to have)', 'El verbo (to have) significa (tener). Con él puedes hablar de las cosas que posees como una casa una bicicleta o incluso una mascota. Aprenderlo te ayudará a describir lo que tienes.\r\n', '[{\"title\":\"El verbo (to have)\",\"desc\":\"(To have) es un verbo muy útil que significa (tener). Se usa para hablar de posesiones. Para la primera persona (yo) la frase es (I have...).\"},{\"title\":\"(a) y (an)\",\"desc\":\"Antes de nombrar un objeto en singular casi siempre usamos (a) o (an). (A) se usa antes de una palabra que empieza con un sonido de consonante (como en (a dog)). Por ahora solo concéntrate en usar (a) con los objetos que aprendiste.\"},{\"title\":\"¡Úsalo en tu día a día!\",\"desc\":\"La mejor manera de recordar (I have...) es usándolo todos los días. Mira a tu alrededor y di en voz alta (I have a phone) (I have a shirt) (I have a key). ¡Cuanto más lo repitas más fácil será para ti!\"}]', 4, 'Tengo un perro. (El verbo tener)'),
(13, 'Numbers from 1 to 20', 'En esta lección, aprenderás a contar del 1 al 20 en inglés. Saber los números es esencial para muchas cosas, como dar un número de teléfono o entender la hora. ¡Es un paso muy importante!', '[{\"title\":\"La base de los números\",\"desc\":\"Los números del 1 al 20 son la base para contar. Es muy importante memorizarlos para poder hacer cosas tan básicas como decir tu número de teléfono. Presta atención a la pronunciación.\"},{\"title\":\"Las (trampas) en la pronunciación\",\"desc\":\"Los números que terminan en (-teen) (como thirteen y fifteen) tienen una pronunciación un poco más larga al final. Practica mucho estos, ya que se parecen a los números que terminan en (-ty) (como thirty y fifty), que verás más adelante.\"},{\"title\":\"¡Practica en todas partes!\",\"desc\":\"La mejor manera de dominar los números es usándolos. Cuenta los objetos en tu casa, las personas en una fila o incluso los huevos que vas a comprar. Repetirlos en voz alta te ayudará a recordarlos más fácilmente.\"}]', 5, 'Los números del 1 al 20'),
(14, 'What time is it? (Hours on the hour)', 'En esta lección, aprenderás a preguntar y a decir la hora en punto. Es muy útil para saber a qué hora es la novela, el partido de fútbol o para acordar una cita con un amigo.', '[{\"title\":\"La pregunta clave\",\"desc\":\"Para preguntar la hora, solo necesitas una frase: (What time is it?) (\\u00bfQu\\u00e9 hora es?).\"},{\"title\":\"C\\u00f3mo responder\",\"desc\":\"Para responder la hora en punto, usa la estructura (It is...) y agrega el n\\u00famero de la hora seguido de (o&#39;clock). Por ejemplo, para decir que son las 5, dices (Its five o&#39;clock). La palabra (o&#39;clock) solo se usa para las horas en punto.\"},{\"title\":\"\\u00a1Usa tu reloj!\",\"desc\":\"La mejor forma de practicar es usando un reloj, o dibujando uno. Cambia la manecilla grande y dile la hora a tus compa\\u00f1eros en ingl\\u00e9s.\"}]', 6, '¿Qué hora es? (Horas en punto)'),
(15, 'Days of the week and months', 'En esta lección, aprenderás a nombrar los días de la semana y los meses del año. Esto te ayudará a hablar de tu cumpleaños y a entender cuándo ocurren eventos importantes. ¡Es clave para planear el futuro!', '[{\"title\":\"Días y meses en mayúscula\",\"desc\":\"En inglés, los días de la semana y los meses del año siempre se escriben con la primera letra en mayúscula. Por ejemplo, Monday, January.\"},{\"title\":\"Mi cumpleaños es...\",\"desc\":\"Para decir el mes de tu cumpleaños, usa la frase (My birthday is in...). No te preocupes por el día, con solo saber el mes es suficiente para esta lección.\"},{\"title\":\"Palabras clave\",\"desc\":\"Para los días, usa (day) y para los meses, usa (month). Así, cuando alguien te pregunte por tu día favorito o tu mes de nacimiento, sabrás de qué están hablando\"}]', 7, 'Días de la semana y meses'),
(16, 'Simple prepositions (in, on, under)', 'En esta lección, aprenderás a usar tres palabras muy útiles para decir dónde están las cosas. Con in, on y under, podrás describir la ubicación de objetos de forma sencilla y clara.', '[{\"title\":\"Las palabras mágicas\",\"desc\":\"Hay tres palabras claves para saber dónde están las cosas: in (dentro), on (encima) y under (debajo). Son muy importantes porque te ayudan a dar y recibir indicaciones claras.\"},{\"title\":\"El orden de las palabras\",\"desc\":\"Para decir una frase completa, el orden es muy simple: El objeto + El verbo (is) + la preposición + el lugar. Por ejemplo: The ball is under the chair.\"},{\"title\":\"¡Practica con todo!\",\"desc\":\"La mejor manera de aprender es practicando. Mira a tu alrededor y di en voz alta dónde están las cosas. Por ejemplo: (The phone is on the table) (El teléfono está sobre la mesa) o (The keys are in the bag) (Las llaves están en el bolso).\"}]', 8, 'Preposiciones simples (in, on, under)'),
(17, 'I am from Colombia. (Nationality and Cities)', 'Aprende a decir de dónde eres y a preguntar a los demás sobre su país o ciudad de origen. Es perfecto para hacer amigos y para cuando conozcas a gente nueva.', '[{\"title\":\"La pregunta clave\",\"desc\":\"Para saber el origen de una persona, solo necesitas una frase: (Where are you from?) (¿De dónde eres?). Es la forma más común de preguntar la nacionalidad o la ciudad.\"},{\"title\":\"La respuesta simple\",\"desc\":\"Para responder, la frase es muy sencilla: (I am from...) (Yo soy de...). Puedes decir el país, la ciudad, o incluso un municipio pequeño como Ibagué o El Líbano.\"},{\"title\":\"Hablar de los demás\",\"desc\":\"También puedes hablar de dónde son otras personas. Usa (He is from...) para un hombre y (She is from...) para una mujer.\"}]', 9, 'Yo soy de Colombia. (Nacionalidades y Ciudades)'),
(18, 'The verb (to like) in the affirmative', 'Prende a expresar tus gustos de forma muy simple usando el verbo (to like) (gustar). Podrás decir si te gusta la comida, la música o un deporte de forma directa y sencilla.\r\nUsa (to like) para expresar lo que te gusta.\r\n\r\nEjemplos:\r\n\r\nI like pizza. (Me gusta la pizza)\r\n\r\nShe likes music. (A ella le gusta la música)\r\n\r\nWe like soccer. (Nos gusta el fútbol)', '[{\"title\":\"El verbo (to like)\",\"desc\":\"La palabra (like) significa (gustar) o (caer bien). Es una de las palabras más importantes para hablar de lo que te gusta.\"},{\"title\":\"La frase clave\",\"desc\":\"Para decir que algo te gusta, la estructura es siempre la misma: (I like...). Es muy simple. Por ejemplo, si te gusta el arroz, solo dices (I like rice).\"},{\"title\":\"(Practica con todo lo que te gusta)\",\"desc\":\"Mira a tu alrededor y piensa en las cosas que te gustan. Practica diciendo (I like...) con todo lo que se te ocurra: tu comida, tus pasatiempos, las personas que quieres, etc.\"}]', 10, 'El verbo (to like) en afirmativo'),
(19, 'What do you like to eat? (Food)', 'Aprende a nombrar alimentos comunes y a hablar de lo que te gusta comer. Es una lección deliciosa y muy útil para usar todos los días, ya sea en casa o cuando vas a la tienda.\r\n', '[{\"title\":\"La pregunta clave\",\"desc\":\"Para saber los gustos de comida de alguien, solo necesitas una frase: (What do you like to eat?) (¿Qué te gusta comer?).\"},{\"title\":\"La respuesta\",\"desc\":\"Para responder de forma simple, usa la frase (I like to eat...) (Me gusta comer...). Puedes usar esta frase con cualquier comida que te guste.\"},{\"title\":\"(Aprende más)\",\"desc\":\"Piensa en tu plato favorito y busca en el diccionario o en el celular cómo se dice cada ingrediente en inglés. Por ejemplo, el sancocho, la lechona, o el tamal. Así, puedes decir (I like to eat sancocho).\"}]', 11, '¿Qué te gusta comer? (Comida)'),
(20, 'Final Exam: A Great Review! (General Practice)', '¡Llegamos al final! En esta lección, vamos a repasar y a usar todo lo que aprendiste. Es el momento de poner en práctica tus conocimientos para conversar, jugar y ver que ahora sí puedes comunicarte en inglés.', '[{\"title\":\"(Lo lograste)\",\"desc\":\"¡Felicitaciones! En solo 11 lecciones, aprendiste a presentarte, a hablar de tu familia, a decir lo que te gusta y a preguntar por lugares. Ahora tienes las herramientas para empezar a comunicarte en inglés de forma simple. No subestimes el gran paso que acabas de dar.\"},{\"title\":\"Lo más importante\",\"desc\":\"El inglés es más fácil de lo que parece si usas las frases clave. Recuerda las estructuras que hemos practicado: (I am...) (Yo soy), (I have...) (Yo tengo), (I like...) (Me gusta). Con estas tres frases, puedes hablar de muchas cosas.\"},{\"title\":\"El futuro del inglés\",\"desc\":\"La mejor forma de seguir aprendiendo es practicando todos los días. Mira videos en inglés, escucha música o intenta nombrar las cosas a tu alrededor en inglés. Lo más importante es que no dejes de usar lo que aprendiste. (Tienes una base increíble para seguir creciendo)\"}]', 12, 'Examen final: ¡Un Gran Repaso! (Práctica General)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Guardar siempre la contraseña encriptada',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rol` int(10) NOT NULL DEFAULT '2',
  `current_level_id` int(11) DEFAULT '9',
  `code` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `phone_number`, `password`, `created_at`, `rol`, `current_level_id`, `code`) VALUES
(15, 'Larry Garcia Morales', '573173328716', '$2y$10$fbA97mEI2W2CDqi6MieyBebUvkDfvaRi0JMOoZWGcpwhjqzvrRh.u', '2025-09-18 21:55:55', 2, 9, NULL),
(48, 'Camila Carvajal', '3002988565', '$2y$10$GY259SPy3IU6ZZF6Uvsf7OoZePX00x0njdg6IXKoypcSQz0jyfdPW', '2025-09-25 18:06:34', 2, 11, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_progress`
--

CREATE TABLE `user_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `user_answer` text,
  `is_correct` tinyint(1) DEFAULT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indices de la tabla `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level_id` (`level_id`);

--
-- Indices de la tabla `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `level_order` (`level_order`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`);

--
-- Indices de la tabla `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `exercises`
--
ALTER TABLE `exercises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `levels`
--
ALTER TABLE `levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `exercises`
--
ALTER TABLE `exercises`
  ADD CONSTRAINT `exercises_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`exercise_id`) REFERENCES `exercises` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
