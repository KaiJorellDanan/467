-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: blitzdb
-- Generation Time: Nov 06, 2024 at 07:29 PM
-- Server version: 11.5.2-MariaDB-ubu2404
-- PHP Version: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `csci467`
--

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `number` int(11) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `price` float(8,2) DEFAULT NULL,
  `weight` float(4,2) NOT NULL DEFAULT 2.00,
  `pictureURL` varchar(50) NOT NULL DEFAULT 'http://blitz.cs.niu.edu/pics/any.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`number`, `description`, `price`, `weight`, `pictureURL`) VALUES
(1, 'windshield w/ polymer', 178.76, 0.55, 'http://blitz.cs.niu.edu/pics/shi.jpg'),
(2, 'wiper blade pair', 23.37, 2.50, 'http://blitz.cs.niu.edu/pics/wip.jpg'),
(3, 'solenoid', 36.58, 2.00, 'http://blitz.cs.niu.edu/pics/sol.jpg'),
(4, 'tiresome mettle', 157.46, 2.00, 'http://blitz.cs.niu.edu/pics/met.jpg'),
(5, 'bucket seat pair', 315.94, 3.50, 'http://blitz.cs.niu.edu/pics/sea.jpg'),
(6, '5 point harness', 177.79, 12.45, 'http://blitz.cs.niu.edu/pics/har.jpg'),
(7, 'turbo intake valve', 659.83, 2.00, 'http://blitz.cs.niu.edu/pics/val.jpg'),
(8, 'supercharger', 711.14, 99.99, 'http://blitz.cs.niu.edu/pics/anc.jpg'),
(9, 'inter cooler sweep', 202.17, 2.00, 'http://blitz.cs.niu.edu/pics/int.jpg'),
(10, 'gas cap - chrome', 25.38, 2.00, 'http://blitz.cs.niu.edu/pics/gas.jpg'),
(11, 'chrome brake pedals kit-manual', 45.71, 0.55, 'http://blitz.cs.niu.edu/pics/bra.jpg'),
(12, 'chrome brake pedals kit-automatic', 41.65, 1.95, 'http://blitz.cs.niu.edu/pics/bra.jpg'),
(13, 'intel inside window decal', 2.03, 2.00, 'http://blitz.cs.niu.edu/pics/itl.jpg'),
(14, 'niu alumni window decal', 1.85, 2.00, 'http://blitz.cs.niu.edu/pics/niu.jpg'),
(15, 'air freshener - lemon', 1.85, 3.50, 'http://blitz.cs.niu.edu/pics/lem.jpg'),
(16, 'air freshener - cherry', 1.85, 12.45, 'http://blitz.cs.niu.edu/pics/che.jpg'),
(17, 'air freshener - new car smell', 2.06, 2.00, 'http://blitz.cs.niu.edu/pics/usa.jpg'),
(18, 'cargo net (new model)', 25.36, 2.00, 'http://blitz.cs.niu.edu/pics/net.jpg'),
(19, 'trunk liner', 25.38, 2.00, 'http://blitz.cs.niu.edu/pics/tru.jpg'),
(20, 'floor mat - driver side', 13.21, 2.00, 'http://blitz.cs.niu.edu/pics/mat.jpg'),
(21, 'floor mat - passenger side', 13.21, 0.55, 'http://blitz.cs.niu.edu/pics/mat.jpg'),
(22, 'car detail kit', 88.38, 1.95, 'http://blitz.cs.niu.edu/pics/cdk.jpg'),
(23, 'tachometer', 30.48, 2.00, 'http://blitz.cs.niu.edu/pics/tach.jpg'),
(24, 'speedometer mph edition', 34.55, 2.00, 'http://blitz.cs.niu.edu/pics/spe.jpg'),
(25, 'gps navigation', 152.39, 3.50, 'http://blitz.cs.niu.edu/pics/gps.jpg'),
(26, 'CD/DVD/DB holder', 20.31, 0.50, 'http://blitz.cs.niu.edu/pics/dvd.jpg'),
(27, 'car charger - micro usb, 2 ft', 17.26, 2.00, 'http://blitz.cs.niu.edu/pics/cha.jpg'),
(28, 'Backup camera peephole', 10.12, 2.00, 'http://blitz.cs.niu.edu/pics/bac.jpg'),
(30, 'USB car adapter, NaviPro 2.1', 220.45, 2.00, 'http://blitz.cs.niu.edu/pics/usb.jpg'),
(31, 'Reverse Sensor, mitigatable', 50.75, 0.55, 'http://blitz.cs.niu.edu/pics/tac.jpg'),
(33, 'Broken Part: 24-11-06 13:27', 101.58, 99.99, 'http://blitz.cs.niu.edu/pics/033-3.jpg'),
(40, '1969 Harley Davidson Ultimate Chopper', 49.59, 2.00, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(41, '1952 Alpine Renault 1300', 100.16, 0.55, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(42, '1996 Moto Guzzi 1100i', 70.08, 1.95, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(43, '2003 Harley-Davidson Eagle Drag Bike', 92.47, 2.00, 'http://blitz.cs.niu.edu/pics/any.jpg'),
(44, '1972 Alfa Romeo GTA', 87.06, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(45, '1962 LanciaA Delta 16V', 105.05, 3.50, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(46, '1968 Ford Mustang', 96.84, 12.45, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(47, '2001 Ferrari Enzo', 97.11, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(48, '1958 Setra Bus', 79.14, 2.00, 'http://blitz.cs.niu.edu/pics/bus.jpg'),
(49, '2002 Suzuki XERO', 67.32, 2.00, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(50, '1969 Corvair Monza', 90.55, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(51, '1968 Dodge Charger', 76.36, 99.99, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(52, '1969 Ford Falcon', 84.38, 1.95, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(53, '1970 Plymouth Hemi Cuda', 32.42, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(54, '1957 Chevy Pickup', 56.59, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(55, '1969 Dodge Charger', 59.66, 3.50, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(56, '1940 Ford Pickup Truck', 59.25, 12.45, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(57, '1993 Mazda RX-7', 84.84, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(58, '1937 Lincoln Berline', 61.58, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(59, '1936 Mercedes-Benz 500K Special Roadster', 24.64, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(60, '1965 Aston Martin DB5', 67.01, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(61, '1980s Black Hawk Helicopter', 78.50, 0.55, 'http://blitz.cs.niu.edu/pics/heli.jpg'),
(62, '1917 Grand Touring Sedan', 88.07, 1.95, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(63, '1948 Porsche 356-A Roadster', 54.77, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(64, '1995 Honda Civic', 95.39, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(65, '1998 Chrysler Plymouth Prowler', 103.14, 3.50, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(66, '1911 Ford Town Car', 33.83, 12.45, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(67, '1964 Mercedes Tour Bus', 76.06, 2.00, 'http://blitz.cs.niu.edu/pics/bus.jpg'),
(68, '1932 Model A Ford J-Coupe', 59.42, 2.00, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(69, '1926 Ford Fire Engine', 25.32, 2.00, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(70, 'P-51-D Mustang', 49.77, 2.00, 'http://blitz.cs.niu.edu/pics/air.jpg'),
(71, '1936 Harley Davidson El Knucklehead', 24.62, 0.55, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(72, '1928 Mercedes-Benz SSK', 73.72, 1.95, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(73, '1999 Indy 500 Monte Carlo SS', 57.66, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(74, '1913 Ford Model T Speedster', 61.75, 2.00, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(75, '1934 Ford V8 Coupe', 34.89, 3.50, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(76, '1999 Yamaha Speed Boat', 52.43, 12.45, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(77, '18th Century Vintage Horse Carriage', 61.68, 2.00, 'http://blitz.cs.niu.edu/pics/horse.jpg'),
(78, '1903 Ford Model A', 69.39, 2.00, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(79, '1992 Ferrari 360 Spider red', 79.14, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(80, '1985 Toyota Supra', 57.91, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(81, 'Collectable Wooden Train', 68.65, 0.55, 'http://blitz.cs.niu.edu/pics/train.jpg'),
(82, '1969 Dodge Super Bee', 49.82, 1.95, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(83, '1917 Maxwell Touring Car', 58.44, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(84, '1976 Ford Gran Torino', 74.67, 2.00, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(85, '1948 Porsche Type 356 Roadster', 63.16, 3.50, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(86, '1957 Vespa GS150', 33.48, 12.45, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(87, '1941 Chevrolet Special Deluxe Cabriolet', 65.61, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(88, '1970 Triumph Spitfire', 93.39, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(89, '1932 Alfa Romeo 8C2300 Spider Sport', 43.96, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(90, '1904 Buick Runabout', 53.50, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(91, '1940s Ford truck', 86.11, 0.55, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(92, '1939 Cadillac Limousine', 23.51, 1.95, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(93, '1957 Corvette Convertible', 71.04, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(94, '1957 Ford Thunderbird', 34.75, 2.00, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(95, '1970 Chevy Chevelle SS 454', 50.02, 3.50, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(96, '1970 Dodge Coronet', 32.89, 12.45, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(97, '1997 BMW R 1100 S', 61.83, 2.00, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(98, '1966 Shelby Cobra 427 S/C', 29.64, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(99, '1928 British Royal Navy Airplane', 67.80, 2.00, 'http://blitz.cs.niu.edu/pics/air.jpg'),
(100, '1939 Chevrolet Deluxe Coupe', 22.92, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(101, '1960 BSA Gold Star DBD34', 37.91, 0.55, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(102, '18th century schooner', 83.65, 1.95, 'http://blitz.cs.niu.edu/pics/ship.jpg'),
(103, '1938 Cadillac V-16 Presidential Limousine', 20.94, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(104, '1962 Volkswagen Microbus', 62.31, 2.00, 'http://blitz.cs.niu.edu/pics/bus.jpg'),
(105, '1982 Ducati 900 Monster', 47.85, 3.50, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(106, '1949 Jaguar XK 120', 48.00, 12.45, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(107, '1958 Chevy Corvette Limited Edition', 16.16, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(108, '1900s Vintage Bi-Plane', 34.78, 2.00, 'http://blitz.cs.niu.edu/pics/any.jpg'),
(109, '1952 Citroen-15CV', 73.98, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(110, '1982 Lamborghini Diablo', 16.50, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(111, '1912 Ford Model T Delivery Wagon', 47.65, 0.55, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(112, '1969 Chevrolet Camaro Z28', 51.31, 1.95, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(113, '1971 Alpine Renault 1600s', 39.19, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(114, '1937 Horch 930V Limousine', 26.71, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(115, '2002 Chevy Corvette', 63.10, 3.50, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(116, '1940 Ford Delivery Sedan', 49.41, 12.45, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(117, '1956 Porsche 356A Coupe', 99.87, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(118, 'Corsair F4U ( Bird Cage)', 29.80, 2.00, 'http://blitz.cs.niu.edu/pics/cor.jpg'),
(119, '1936 Mercedes Benz 500k Roadster', 22.11, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(120, '1992 Porsche Cayenne Turbo Silver', 70.89, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(121, '1936 Chrysler Airflow', 58.39, 0.55, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(122, '1900s Vintage Tri-Plane', 36.80, 1.95, 'http://blitz.cs.niu.edu/pics/any.jpg'),
(123, '1961 Chevrolet Impala', 32.83, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(124, '1980 GM Manhattan Express', 60.70, 2.00, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(125, '1997 BMW F650 ST', 75.34, 3.50, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(126, '1982 Ducati 996 R', 27.17, 12.45, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(127, '1954 Greyhound Scenicruiser', 29.24, 2.00, 'http://blitz.cs.niu.edu/pics/bus.jpg'),
(128, '1950 Chicago Surface Lines Streetcar', 30.08, 2.00, 'http://blitz.cs.niu.edu/pics/scar.jpg'),
(129, '1996 Peterbilt 379 Stake Bed with Outrigger', 37.83, 2.00, 'http://blitz.cs.niu.edu/pics/peter.jpg'),
(130, '1928 Ford Phaeton Deluxe', 37.17, 2.00, 'http://blitz.cs.niu.edu/pics/ford.jpg'),
(131, '1974 Ducati 350 Mk3 Desmo', 63.18, 0.55, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(132, '1930 Buick Marquette Phaeton', 30.47, 1.95, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(133, 'Diamond T620 Semi-Skirted Tanker', 76.87, 2.00, 'http://blitz.cs.niu.edu/pics/ship.jpg'),
(134, '1962 City of Detroit Streetcar', 42.20, 2.00, 'http://blitz.cs.niu.edu/pics/scar.jpg'),
(135, '2002 Yamaha YZR M1', 38.47, 3.50, 'http://blitz.cs.niu.edu/pics/mop.jpg'),
(136, 'The Schooner Bluenose', 38.28, 12.45, 'http://blitz.cs.niu.edu/pics/ship.jpg'),
(137, 'American Airlines: B767-300', 57.58, 2.00, 'http://blitz.cs.niu.edu/pics/air.jpg'),
(138, 'The Mayflower', 48.74, 2.00, 'http://blitz.cs.niu.edu/pics/ship.jpg'),
(139, 'HMS Bounty', 44.84, 2.00, 'http://blitz.cs.niu.edu/pics/ship.jpg'),
(140, 'America West Airlines B757-200', 77.44, 2.00, 'http://blitz.cs.niu.edu/pics/air.jpg'),
(141, 'The USS Constitution Ship', 38.25, 0.55, 'http://blitz.cs.niu.edu/pics/ship.jpg'),
(142, '1982 Camaro Z28', 52.38, 1.95, 'http://blitz.cs.niu.edu/pics/car.jpg'),
(143, 'ATA: B757-300', 66.78, 2.00, 'http://blitz.cs.niu.edu/pics/air.jpg'),
(144, 'F/A 18 Hornet 1/72', 61.24, 2.00, 'http://blitz.cs.niu.edu/pics/air.jpg'),
(145, 'The Titanic', 57.50, 3.50, 'http://blitz.cs.niu.edu/pics/ship.jpg'),
(146, 'The Queen Mary', 60.37, 12.45, 'http://blitz.cs.niu.edu/pics/ship.jpg'),
(147, 'American Airlines: MD-11S', 40.83, 2.00, 'http://blitz.cs.niu.edu/pics/air.jpg'),
(148, 'Boeing X-32A JSF', 36.90, 2.00, 'http://blitz.cs.niu.edu/pics/air.jpg'),
(149, 'Pont Yacht', 37.48, 2.00, 'http://blitz.cs.niu.edu/pics/ship.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
