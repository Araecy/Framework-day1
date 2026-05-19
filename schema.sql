CREATE TABLE
    `EX_DB_102953`.`accounts` (
        `Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `Naam` VARCHAR(255) NOT NULL,
        `Email` VARCHAR(255) NOT NULL UNIQUE,
        `Adres` VARCHAR(255) NOT NULL,
        `Woonplaats` VARCHAR(255) NOT NULL,
        `Telefoonnummer` VARCHAR(20) NOT NULL,
        `Geboortedatum` DATE NOT NULL,
        `Geslacht` ENUM ('man', 'vrouw', 'anders') NOT NULL,
        `Wachtwoord_hash` VARCHAR(255) NOT NULL,
        `has_Ticket` BOOLEAN NOT NULL DEFAULT FALSE,
        PRIMARY KEY (`Id`)
    );