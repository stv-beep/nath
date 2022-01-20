CREATE TABLE `treballador` (
  `id` integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nom` varchar(50),
  `cognom` varchar(50),
  `identificador` varchar(200)
);

CREATE TABLE `activitat` (
    `id_treballador` integer,
    `inici_jornada` timestamp NULL,
    `fi_jornada` timestamp NULL,
    `total` integer
);

ALTER TABLE `activitat` ADD FOREIGN KEY (`id_treballador`) REFERENCES `treballador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;