-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-02-2018 a las 04:14:32
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
  `answerid` bigint(20) NOT NULL,
  `ownerid` bigint(20) NOT NULL,
  `score` int(3) NOT NULL,
  `information` varchar(30) NOT NULL,
  `reservationsystem` varchar(30) NOT NULL,
  `chosenday` tinyint(1) NOT NULL,
  `rehire` tinyint(1) NOT NULL,
  `partyroomrecommend` tinyint(1) NOT NULL,
  `soundandilluminationrecommend` tinyint(1) NOT NULL,
  `crockeryandtablelinenrecommend` tinyint(1) NOT NULL,
  `cateringrecommend` tinyint(1) NOT NULL,
  `sendoffersbyemail` tinyint(1) NOT NULL,
  `suggest` text NOT NULL,
  `creationdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `answers`
--

INSERT INTO `answers` (`answerid`, `ownerid`, `score`, `information`, `reservationsystem`, `chosenday`, `rehire`, `partyroomrecommend`, `soundandilluminationrecommend`, `crockeryandtablelinenrecommend`, `cateringrecommend`, `sendoffersbyemail`, `suggest`, `creationdate`) VALUES
(4, 31, 10, 'Óptima', 'Muy Bueno', 1, 1, 0, 0, 0, 1, 1, 'Sos muy vivo porque ya aparecen la mejores respuestas seleccionadas, tonto!', '2018-02-09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employees`
--

CREATE TABLE `employees` (
  `employeeid` bigint(20) NOT NULL,
  `locationid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `employees`
--

INSERT INTO `employees` (`employeeid`, `locationid`) VALUES
(32, 265840),
(33, 265840),
(108, 151531),
(109, 650540),
(116, 265840);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `locations`
--

CREATE TABLE `locations` (
  `locationid` bigint(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `locations`
--

INSERT INTO `locations` (`locationid`, `description`) VALUES
(151531, 'CABA'),
(265840, 'Zona Sur'),
(650540, 'Zona Norte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `permissionid` bigint(18) NOT NULL,
  `description` varchar(100) NOT NULL,
  `uri` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`permissionid`, `description`, `uri`) VALUES
(296541, 'Ver salones', 'eventRoomViewer'),
(445561, 'Lista de eventos', 'guest-list-viewer'),
(778521, 'Realizar reserva de salón', 'loungeReservation'),
(881414, 'Cancelar reserva', 'cancelation'),
(996399, 'Modificar fecha', 'change-reservation-date'),
(4215501, 'Ver mis reservaciones', 'reservationsViewer'),
(6954111, 'Modificar lista de invitados', 'attendant'),
(11111133, 'Usuarios', 'admin-users'),
(55252552, 'Estadisticas', 'charts');

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
(5031, 296541, 535751),
(9636, 881414, 96312471),
(10132, 4215501, 535751),
(32109, 778521, 535751),
(44444, 11111133, 88107751),
(102354, 445561, 221548621),
(444282, 6954111, 96312471),
(636363, 55252552, 88107751),
(32323232, 996399, 96312471);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservations`
--

CREATE TABLE `reservations` (
  `reservationid` bigint(20) NOT NULL,
  `ownerid` bigint(20) NOT NULL,
  `locationid` bigint(20) NOT NULL,
  `reserveddate` date NOT NULL,
  `guestlist` text,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `reservations`
--

INSERT INTO `reservations` (`reservationid`, `ownerid`, `locationid`, `reserveddate`, `guestlist`, `active`) VALUES
(18, 31, 265840, '2018-02-22', '[]', 1),
(19, 31, 151531, '2018-02-08', '[]', 1),
(20, 31, 650540, '2018-03-01', '[]', 1),
(21, 31, 265840, '2018-02-19', '[{\"name\":\"Carlos\",\"lastname\":\"Perez\",\"tnumber\":\"2\"},{\"name\":\"Ana\",\"lastname\":\"Ramirez\",\"tnumber\":\"4\"},{\"name\":\"Cristian\",\"lastname\":\"Correa\",\"tnumber\":\"6\"},{\"name\":\"Alba\",\"lastname\":\"Aranda\",\"tnumber\":\"\"},{\"name\":\"Rodrigo\",\"lastname\":\"Zir\",\"tnumber\":\"\"}]', 1),
(22, 31, 151531, '2018-02-10', '[{\"name\":\"Nico\",\"lastname\":\"Cabrera\",\"tnumber\":\"3\"},{\"name\":\"Mary\",\"lastname\":\"Romero\",\"tnumber\":\"3\"}]', 1),
(23, 34, 151531, '2017-09-07', '[]', 1),
(24, 35, 265840, '2017-09-09', '[]', 0),
(25, 36, 151531, '2017-09-16', '[]', 1),
(26, 39, 151531, '2017-09-19', '[]', 1),
(27, 40, 265840, '2017-09-16', '[]', 1),
(28, 43, 265840, '2017-09-23', '[]', 1),
(29, 41, 265840, '2017-09-30', '[]', 0),
(30, 42, 650540, '2017-09-05', '[]', 1),
(31, 44, 650540, '2017-09-14', '[]', 1),
(32, 45, 151531, '2017-10-07', '[]', 1),
(33, 46, 151531, '2017-10-11', '[]', 1),
(34, 47, 151531, '2017-10-21', '[]', 1),
(35, 48, 151531, '2017-10-28', '[]', 1),
(36, 49, 265840, '2017-10-07', '[]', 1),
(37, 50, 265840, '2017-10-14', '[]', 0),
(38, 51, 265840, '2017-10-21', '[]', 1),
(39, 54, 650540, '2017-09-23', '[]', 1),
(40, 55, 650540, '2017-10-05', '[]', 1),
(41, 56, 650540, '2017-10-13', '[]', 0),
(42, 57, 650540, '2017-10-21', '[]', 1),
(43, 58, 650540, '2017-10-28', '[]', 1),
(44, 59, 650540, '2017-11-04', '[]', 1),
(45, 60, 650540, '2017-11-10', '[]', 1),
(46, 61, 265840, '2017-10-27', '[]', 1),
(47, 62, 151531, '2017-11-04', '[]', 1),
(48, 63, 151531, '2017-11-17', '[]', 0),
(49, 64, 151531, '2017-11-23', '[]', 1),
(50, 65, 151531, '2017-12-02', '[]', 1),
(51, 66, 151531, '2017-12-07', '[]', 1),
(52, 67, 151531, '2017-12-17', '[]', 1),
(53, 68, 151531, '2017-12-21', '[]', 1),
(54, 69, 151531, '2017-12-31', '[]', 1),
(55, 70, 151531, '2018-01-03', '[]', 1),
(56, 71, 151531, '2018-01-27', '[]', 1),
(57, 72, 265840, '2017-11-04', '[]', 1),
(58, 73, 265840, '2017-11-11', '[]', 1),
(59, 74, 265840, '2017-11-18', '[]', 1),
(60, 75, 265840, '2017-11-25', '[]', 1),
(61, 76, 265840, '2017-12-02', '[]', 1),
(62, 77, 265840, '2017-12-09', '[]', 1),
(63, 80, 265840, '2017-12-22', '[]', 1),
(64, 81, 265840, '2017-12-23', '[]', 1),
(65, 82, 265840, '2018-01-06', '[]', 1),
(66, 83, 265840, '2018-01-13', '[]', 1),
(67, 84, 265840, '2018-01-20', '[]', 0),
(68, 85, 265840, '2018-01-27', '[]', 1),
(69, 86, 650540, '2017-11-18', '[]', 1),
(70, 87, 650540, '2017-11-22', '[]', 1),
(71, 88, 650540, '2017-12-02', '[]', 1),
(72, 89, 650540, '2017-12-08', '[]', 1),
(73, 90, 650540, '2017-12-17', '[]', 1),
(74, 91, 650540, '2017-12-23', '[]', 1),
(75, 92, 650540, '2017-12-30', '[]', 1),
(76, 93, 650540, '2018-01-03', '[]', 1),
(77, 94, 650540, '2018-01-12', '[]', 1),
(78, 95, 650540, '2018-01-19', '[]', 1),
(79, 96, 650540, '2018-01-27', '[]', 0),
(80, 97, 650540, '2018-02-03', '[]', 1),
(81, 98, 650540, '2018-02-10', '[]', 1),
(82, 99, 650540, '2018-02-23', '[]', 1),
(84, 31, 650540, '2018-02-27', '[]', 1),
(85, 34, 265840, '2018-02-27', '[]', 1);

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
(535751, 'Cliente'),
(88107751, 'Administrador'),
(96312471, 'Encargado'),
(221548621, 'Empleado');

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
(31, 'Usuario Pruebas', 'correo@correo.com.ar', 'contraseñasuperloca123456', 535751),
(32, 'Encargado', 'encargado@encargado.com', 'contraseñasuperloca123456', 96312471),
(33, 'Roberto Gómez Bolaños', 'empleado@empleado.com', 'contraseñasuperloca123456', 221548621),
(34, 'Aatrox', 'aatrox@aatrox.com', 'contraseñasuperloca123456', 535751),
(35, 'Ahri', 'ahri@ahri.com', 'contraseñasuperloca123456', 535751),
(36, 'Akali', 'akali@akali.com', 'contraseñasuperloca123456', 535751),
(39, 'Alistar', 'alistar@alistar.com', 'contraseñasuperloca123456', 535751),
(40, 'Amumu', 'amumu@amumu.com', 'contraseñasuperloca123456', 535751),
(41, 'Anivia', 'anivia@anivia.com', 'contraseñasuperloca123456', 535751),
(42, 'Annie', 'annie@annie.com', 'contraseñasuperloca123456', 535751),
(43, 'Ashe', 'ashe@ashe.com', 'contraseñasuperloca123456', 535751),
(44, 'Aurelion', 'aurelion@aurelion.com', 'contraseñasuperloca123456', 535751),
(45, 'Azir', 'azir@azir.com', 'contraseñasuperloca123456', 535751),
(46, 'Bardo', 'bardo@bardo.com', 'contraseñasuperloca123456', 535751),
(47, 'Blitzcrank', 'blitzcrank@blitzcrank.com', 'contraseñasuperloca123456', 535751),
(48, 'Brand', 'brand@brand.com', 'contraseñasuperloca123456', 535751),
(49, 'Braum', 'braum@braum.com', 'contraseñasuperloca123456', 535751),
(50, 'Caitlyn', 'caitlyn@caitlyn.com', 'contraseñasuperloca123456', 535751),
(51, 'Camille', 'camille@camille.com', 'contraseñasuperloca123456', 535751),
(54, 'Cassiopeia', 'cassiopeia@cassiopeia.com', 'contraseñasuperloca123456', 535751),
(55, 'Corki', 'corki@corki.com', 'contraseñasuperloca123456', 535751),
(56, 'Darius', 'darius@darius.com', 'contraseñasuperloca123456', 535751),
(57, 'Diana', 'diana@diana.com', 'contraseñasuperloca123456', 535751),
(58, 'Draven', 'draven@draven.com', 'contraseñasuperloca123456', 535751),
(59, 'Ekko', 'ekko@ekko.com', 'contraseñasuperloca123456', 535751),
(60, 'Elise', 'elise@elise.com', 'contraseñasuperloca123456', 535751),
(61, 'Evelynn', 'evelynn@evelynn.com', 'contraseñasuperloca123456', 535751),
(62, 'Ezreal', 'ezreal@ezreal.com', 'contraseñasuperloca123456', 535751),
(63, 'Fiddlesticks', 'fiddlesticks@fiddlesticks.com', 'contraseñasuperloca123456', 535751),
(64, 'Fiora', 'fiora@fiora.com', 'contraseñasuperloca123456', 535751),
(65, 'Fizz', 'fizz@fizz.com', 'contraseñasuperloca123456', 535751),
(66, 'Galio', 'galio@galio.com', 'contraseñasuperloca123456', 535751),
(67, 'Gangplank', 'gangplank@gangplank.com', 'contraseñasuperloca123456', 535751),
(68, 'Garen', 'garen@garen.com', 'contraseñasuperloca123456', 535751),
(69, 'Gnar', 'gnar@gnar.com', 'contraseñasuperloca123456', 535751),
(70, 'Gragas', 'gragas@gragas.com', 'contraseñasuperloca123456', 535751),
(71, 'Graves', 'graves@graves.com', 'contraseñasuperloca123456', 535751),
(72, 'Hecarim', 'hecarim@hecarim.com', 'contraseñasuperloca123456', 535751),
(73, 'Heimerdinger', 'heimerdinger@heimerdinger.com', 'contraseñasuperloca123456', 535751),
(74, 'Illaoi', 'illaoi@illaoi.com', 'contraseñasuperloca123456', 535751),
(75, 'Irelia', 'irelia@irelia.com', 'contraseñasuperloca123456', 535751),
(76, 'Ivern', 'ivern@ivern.com', 'contraseñasuperloca123456', 535751),
(77, 'Janna', 'janna@janna.com', 'contraseñasuperloca123456', 535751),
(78, 'Jax', 'jax@jax.com', 'contraseñasuperloca123456', 535751),
(79, 'Jayce', 'jayce@jayce.com', 'contraseñasuperloca123456', 535751),
(80, 'Jinx', 'jinx@jinx.com', 'contraseñasuperloca123456', 535751),
(81, 'Xayah', 'xayah@xayah.com', 'contraseñasuperloca123456', 535751),
(82, 'Vi', 'vi@vi.com', 'contraseñasuperloca123456', 535751),
(83, 'Vayne', 'vayne@vayne.com', 'contraseñasuperloca123456', 535751),
(84, 'Sivir', 'sivir@sivir.com', 'contraseñasuperloca123456', 535751),
(85, 'Lux', 'lux@lux.com', 'contraseñasuperloca123456', 535751),
(86, 'Kalista', 'kalista@kalista.com', 'contraseñasuperloca123456', 535751),
(87, 'Karma', 'karma@karma.com.ar', 'contraseñasuperloca123456', 535751),
(88, 'Karthus', 'karthus@karthus.com', 'contraseñasuperloca123456', 535751),
(89, 'Kassadin', 'kassadin@kassadin.com', 'contraseñasuperloca123456', 535751),
(90, 'Katarina', 'katarina@katarina.com', 'contraseñasuperloca123456', 535751),
(91, 'Kayle', 'kayle@kayle.com', 'contraseñasuperloca123456', 535751),
(92, 'Kayn', 'kayn@kayn.com', 'contraseñasuperloca123456', 535751),
(93, 'Kennen', 'kennen@kennen.com', 'contraseñasuperloca123456', 535751),
(94, 'Kindred', 'kindred@kindred.com', 'contraseñasuperloca123456', 535751),
(95, 'Kled', 'kled@kled.com', 'contraseñasuperloca123456', 535751),
(96, 'Leona', 'leona@leona.com', 'contraseñasuperloca123456', 535751),
(97, 'Lissandra', 'lissandra@lissandra.com', 'contraseñasuperloca123456', 535751),
(98, 'Lucian', 'lucian@lucian.com', 'contraseñasuperloca123456', 535751),
(99, 'Morgana', 'morgana@morgana.com', 'contraseñasuperloca123456', 535751),
(100, 'El Administrador', 'administrador@administrador.com', 'contraseñasuperloca123456', 88107751),
(108, 'Juan Perez', 'Juanperez@gmail.com', 'contraseñasuperloca123456', 221548621),
(109, 'Encargado ZN', 'encargardoDeZonaNorte@norte.com', 'contraseñasuperloca123456', 96312471),
(116, 'empleadín Nuevo', 'mengargo@zonanorte.com', '123456', 221548621);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answerid`);

--
-- Indices de la tabla `employees`
--
ALTER TABLE `employees`
  ADD KEY `employeeid` (`employeeid`),
  ADD KEY `locationid` (`locationid`);

--
-- Indices de la tabla `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`locationid`);

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
-- Indices de la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservationid`),
  ADD KEY `ownerid` (`ownerid`),
  ADD KEY `locationid` (`locationid`),
  ADD KEY `locationid_2` (`locationid`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`rolid`);

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
  MODIFY `answerid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permissionid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55252553;
--
-- AUTO_INCREMENT de la tabla `permissionsbyrol`
--
ALTER TABLE `permissionsbyrol`
  MODIFY `permissionbyrolid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32323233;
--
-- AUTO_INCREMENT de la tabla `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservationid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `userid` bigint(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`employeeid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`locationid`) REFERENCES `locations` (`locationid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `permissionsbyrol`
--
ALTER TABLE `permissionsbyrol`
  ADD CONSTRAINT `permissionsbyrol_ibfk_1` FOREIGN KEY (`rolid`) REFERENCES `roles` (`rolid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `permissionsbyrol_ibfk_2` FOREIGN KEY (`permissionid`) REFERENCES `permissions` (`permissionid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`ownerid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`locationid`) REFERENCES `locations` (`locationid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`rolid`) REFERENCES `roles` (`rolid`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
