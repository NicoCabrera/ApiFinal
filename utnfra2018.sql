-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-01-2018 a las 19:08:42
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `utnfra2018`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `answers`
--

CREATE TABLE `answers` (
  `answerid` bigint(18) NOT NULL,
  `text` text NOT NULL,
  `userid` bigint(18) DEFAULT NULL,
  `questionid` bigint(18) NOT NULL,
  `surveyid` bigint(18) NOT NULL,
  `choosenothing` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `answers`
--

INSERT INTO `answers` (`answerid`, `text`, `userid`, `questionid`, `surveyid`, `choosenothing`) VALUES
(35, 'Es puntual. Es bastante exigente.', NULL, 64, 62, 0),
(36, '', NULL, 65, 63, 0),
(37, '', NULL, 65, 63, 0),
(38, 'Excelente. 100% recomendado, 1link , mega, no virus', NULL, 64, 62, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `options`
--

CREATE TABLE `options` (
  `optionid` bigint(18) NOT NULL,
  `text` text NOT NULL,
  `isright` tinyint(1) NOT NULL,
  `questionid` bigint(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `options`
--

INSERT INTO `options` (`optionid`, `text`, `isright`, `questionid`) VALUES
(63, 'Matemática', 0, 65),
(64, 'Estadística', 0, 65);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `optionsbyanswer`
--

CREATE TABLE `optionsbyanswer` (
  `optionsbyanswerid` bigint(18) NOT NULL,
  `optionid` bigint(18) NOT NULL,
  `answerid` bigint(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `optionsbyanswer`
--

INSERT INTO `optionsbyanswer` (`optionsbyanswerid`, `optionid`, `answerid`) VALUES
(29, 63, 36),
(30, 63, 37);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `permissionid` bigint(18) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`permissionid`, `description`) VALUES
(1, 'Gestionar profesor'),
(2, 'Gestionar administrativo'),
(3, 'Gestionar usuarios'),
(4, 'Gestionar encuestas'),
(5, 'Ver encuestas'),
(6, 'Tomar asistencia'),
(7, 'Ver estadísticas'),
(8, 'Ver faltas y asistencias');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissionsbyrol`
--

CREATE TABLE `permissionsbyrol` (
  `permissionbyrolid` bigint(18) NOT NULL,
  `permissionid` bigint(18) NOT NULL,
  `rolid` bigint(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permissionsbyrol`
--

INSERT INTO `permissionsbyrol` (`permissionbyrolid`, `permissionid`, `rolid`) VALUES
(1, 4, 2),
(2, 6, 2),
(3, 7, 2),
(4, 5, 4),
(5, 6, 3),
(6, 3, 3),
(9, 3, 1),
(10, 7, 1),
(11, 8, 4),
(12, 4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `questions`
--

CREATE TABLE `questions` (
  `questionid` bigint(18) NOT NULL,
  `text` text NOT NULL,
  `surveyid` bigint(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `questions`
--

INSERT INTO `questions` (`questionid`, `text`, `surveyid`) VALUES
(64, '¿Qué te parece R. Fonte como profesor?', 62),
(65, '¿Qué materia te resultó más difícil?', 63);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `rolid` bigint(18) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`rolid`, `code`) VALUES
(1, 'Administrator'),
(2, 'Teacher'),
(3, 'Administrative'),
(4, 'Student'),
(535751, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `surveys`
--

CREATE TABLE `surveys` (
  `surveyid` bigint(18) NOT NULL,
  `title` text NOT NULL,
  `creationdate` date NOT NULL,
  `enddate` date NOT NULL,
  `ownerid` bigint(20) DEFAULT NULL,
  `waseliminated` tinyint(1) NOT NULL DEFAULT '0',
  `surveytypeid` bigint(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `surveys`
--

INSERT INTO `surveys` (`surveyid`, `title`, `creationdate`, `enddate`, `ownerid`, `waseliminated`, `surveytypeid`) VALUES
(62, 'Opinión sobre el profesor', '2017-06-23', '2017-07-31', NULL, 0, 1),
(63, 'Dificultad de la materia', '2017-06-23', '2017-08-24', NULL, 0, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `surveytype`
--

CREATE TABLE `surveytype` (
  `surveytypeid` bigint(18) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `surveytype`
--

INSERT INTO `surveytype` (`surveytypeid`, `name`, `description`) VALUES
(1, 'FreeAnswer', 'El usuario ingresa la respuesta por medio de un textarea.'),
(2, 'Radiobuttons1Correct2Graphics', 'La respuesta es por medio de opciones predefinidas y sólo se puede seleccionar una. Se utilizan radiobuttons. Existe una respuesta correcta'),
(3, 'Radiobuttons1Graphic', 'La respuesta es por medio de opciones predefinidas y sólo se puede seleccionar una. No existe respuesta correcta, ya que se busca obtener la tendencia de opinión.'),
(4, 'Checkboxes1GraphicChooseNothing', 'La respuesta es por medio de opciones predefinidas y se puede seleccionar todas o ninguna. No hay respuesta correcta.'),
(5, 'CheckboxesCorrects2GraphicsChooseNothing', 'La respuesta es por medio de opciones predefinidas y se puede seleccionar todas o ninguna. Exite 1 o varias respuestas correctas, incluso la opción correcta podría llegar a ser no seleccionar ninguna.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `userid` bigint(18) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `rolid` bigint(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`userid`, `username`, `email`, `password`, `rolid`) VALUES
(31, 'Usuario Pruebas', 'correo@correo.com.ar', '1azcGXGneEkrk', 535751);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answerid`),
  ADD KEY `surveyid` (`surveyid`),
  ADD KEY `userid` (`userid`);

--
-- Indices de la tabla `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`optionid`),
  ADD KEY `questionid` (`questionid`);

--
-- Indices de la tabla `optionsbyanswer`
--
ALTER TABLE `optionsbyanswer`
  ADD PRIMARY KEY (`optionsbyanswerid`),
  ADD KEY `optionid` (`optionid`),
  ADD KEY `answerid` (`answerid`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permissionid`);

--
-- Indices de la tabla `permissionsbyrol`
--
ALTER TABLE `permissionsbyrol`
  ADD PRIMARY KEY (`permissionbyrolid`),
  ADD KEY `permissionid` (`permissionid`),
  ADD KEY `rolid` (`rolid`);

--
-- Indices de la tabla `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`questionid`),
  ADD KEY `surveyid` (`surveyid`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rolid`);

--
-- Indices de la tabla `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`surveyid`),
  ADD KEY `ownerid` (`ownerid`),
  ADD KEY `surveytypeid` (`surveytypeid`);

--
-- Indices de la tabla `surveytype`
--
ALTER TABLE `surveytype`
  ADD PRIMARY KEY (`surveytypeid`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `rolid` (`rolid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `answers`
--
ALTER TABLE `answers`
  MODIFY `answerid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT de la tabla `options`
--
ALTER TABLE `options`
  MODIFY `optionid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT de la tabla `optionsbyanswer`
--
ALTER TABLE `optionsbyanswer`
  MODIFY `optionsbyanswerid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permissionid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `permissionsbyrol`
--
ALTER TABLE `permissionsbyrol`
  MODIFY `permissionbyrolid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `questions`
--
ALTER TABLE `questions`
  MODIFY `questionid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
--
-- AUTO_INCREMENT de la tabla `surveys`
--
ALTER TABLE `surveys`
  MODIFY `surveyid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT de la tabla `surveytype`
--
ALTER TABLE `surveytype`
  MODIFY `surveytypeid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `userid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE SET NULL;

--
-- Filtros para la tabla `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`questionid`) REFERENCES `questions` (`questionid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `optionsbyanswer`
--
ALTER TABLE `optionsbyanswer`
  ADD CONSTRAINT `optionsbyanswer_ibfk_1` FOREIGN KEY (`optionid`) REFERENCES `options` (`optionid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `optionsbyanswer_ibfk_2` FOREIGN KEY (`answerid`) REFERENCES `answers` (`answerid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permissionsbyrol`
--
ALTER TABLE `permissionsbyrol`
  ADD CONSTRAINT `permissionsbyrol_ibfk_1` FOREIGN KEY (`rolid`) REFERENCES `roles` (`rolid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `permissionsbyrol_ibfk_2` FOREIGN KEY (`permissionid`) REFERENCES `permissions` (`permissionid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`surveyid`) REFERENCES `surveys` (`surveyid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `surveys`
--
ALTER TABLE `surveys`
  ADD CONSTRAINT `surveys_ibfk_1` FOREIGN KEY (`ownerid`) REFERENCES `users` (`userid`) ON DELETE SET NULL,
  ADD CONSTRAINT `surveys_ibfk_2` FOREIGN KEY (`surveytypeid`) REFERENCES `surveytype` (`surveytypeid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`rolid`) REFERENCES `roles` (`rolid`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
