-- =====================================================================
--  Vhudi Optical Dispensing — Database schema reconstructed from PHP code
-- =====================================================================
--  This schema is derived from how the application ACTUALLY queries the
--  database (SELECT/INSERT/UPDATE/DELETE + $row['col'] accesses), not
--  from the original phpMyAdmin dump. Only the 5 tables the code touches
--  are modelled. Legacy/unused tables (account, clients, extra, lense,
--  my_price, invoice, checkout, etc.) are intentionally omitted.
--
--  Engine/charset kept consistent with the original dump (InnoDB/latin1).
-- =====================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ---------------------------------------------------------------------
-- Table: doctors  (the "clients" of the business = optometry practices)
-- ---------------------------------------------------------------------
-- Referenced by:
--   all-clients.php / remove.php  -> SELECT * (reads surname, names, id, `cell no`, email, pk)
--   new-client.php                -> INSERT (surname, names, `cell no`, email, surname_intial)
--   update.php                    -> UPDATE names, `cell no`, email, surname_intial WHERE pk
--   new_order.php                 -> SELECT WHERE id  (reads surname_intial)
--   view_order.php / invoice.php  -> SELECT WHERE pk  (get_doctor -> surname_intial)
--   pdf.php / transaction.php     -> SELECT WHERE pk  (surname_intial)
-- Notes:
--   * `pk` is the true primary key used by joins/links (db_practice = pk).
--   * `id` is a SEPARATE free-text field shown as "Id Number" (not the PK).
--   * `cell no` literally contains a space in the column name.
CREATE TABLE `doctors` (
  `pk`             INT(11)     NOT NULL AUTO_INCREMENT,
  `surname`        VARCHAR(20) NOT NULL DEFAULT '',
  `names`          VARCHAR(20) NOT NULL DEFAULT '',
  `cell no`        VARCHAR(20) NOT NULL DEFAULT '',
  `email`          VARCHAR(40) NOT NULL DEFAULT '',
  `id`             VARCHAR(60) NOT NULL DEFAULT '',   -- "Id Number" (display), not the PK
  `surname_intial` VARCHAR(40) NOT NULL DEFAULT '',   -- surname + ' ' + initials (display name)
  `initial`        VARCHAR(60) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ---------------------------------------------------------------------
-- Table: price  (lens catalog: Item Type + Vision + Price)
-- ---------------------------------------------------------------------
-- Referenced by:
--   lense-and-vision.php          -> SELECT *, INSERT (Item_Type, Item_Vision, Item_Price)
--   lense-and-vision_update.php   -> UPDATE ... WHERE Item_No ; DELETE WHERE Item_No
--   new_order.php                 -> SELECT WHERE Item_No (lens_price -> Item_Type/Item_Vision,
--                                    lens_price2 -> Item_Price) ; SELECT * for dropdowns
-- Notes:
--   * Item_No is the PK referenced by new_order.php lens lookups.
--   * INSERT omits Item_No => it must be AUTO_INCREMENT.
CREATE TABLE `price` (
  `Item_No`     INT(11)      NOT NULL AUTO_INCREMENT,
  `Item_Type`   VARCHAR(100) NOT NULL DEFAULT '',
  `Item_Vision` VARCHAR(100) NOT NULL DEFAULT '',
  `Item_Price`  DOUBLE(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`Item_No`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ---------------------------------------------------------------------
-- Table: addon  (addon/service catalog)
-- ---------------------------------------------------------------------
-- Referenced by:
--   addons.php          -> SELECT *, INSERT (addon_Type, addon_Price)
--   addons_update.php   -> UPDATE ... WHERE addon_No ; DELETE WHERE addon_No
--   addons2.php         -> SELECT * (dropdown) ; SELECT WHERE addon_No
--                          (reads addon_No, addon_Type, addon_Price)
-- Notes:
--   * addon_No is the PK; INSERT omits it => AUTO_INCREMENT.
CREATE TABLE `addon` (
  `addon_No`    INT(15)      NOT NULL AUTO_INCREMENT,
  `addon_Type`  VARCHAR(50)  NOT NULL DEFAULT '',
  `addon_Price` DOUBLE(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`addon_No`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ---------------------------------------------------------------------
-- Table: addon_clients  (addons attached to a specific order)
-- ---------------------------------------------------------------------
-- Referenced by:
--   addons2.php         -> INSERT (order_id, description, price)
--                          DELETE WHERE id
--                          SELECT WHERE order_id (reads id, description, price)
--   view_order.php /
--   invoice.php /
--   transaction.php /
--   pdf.php             -> SELECT WHERE order_id (get_total_cost sums price)
-- Notes:
--   * order_id stores prescription.db_order (a code, hence TEXT), NOT a numeric FK.
--   * `id` is the surrogate PK used by the DELETE.
CREATE TABLE `addon_clients` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `order_id`    TEXT    NOT NULL,   -- = prescription.db_order
  `description` TEXT    NOT NULL,
  `price`       TEXT    NOT NULL,   -- numeric string, summed in PHP
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ---------------------------------------------------------------------
-- Table: transaction  (payments recorded against an order)
-- ---------------------------------------------------------------------
-- Referenced by:
--   transaction.php     -> INSERT (order_id, date, amount)  via payme()
--   view_order.php /
--   invoice.php /
--   transaction.php /
--   pdf.php             -> SELECT WHERE order_id (get_total_transaction sums amount)
-- Notes:
--   * order_id = prescription.db_order.
--   * date is written as PHP date('Y-m-d H:i:s') string.
--   * No surrogate key is ever used by the code; add one for sanity.
CREATE TABLE `transaction` (
  `pk`       INT(11)      NOT NULL AUTO_INCREMENT,  -- added; code never selects it
  `order_id` VARCHAR(100) NOT NULL DEFAULT '',      -- = prescription.db_order
  `date`     DATETIME     DEFAULT NULL,
  `amount`   DOUBLE(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`pk`),
  KEY `idx_transaction_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ---------------------------------------------------------------------
-- Table: prescription  (the core "order" table)
-- ---------------------------------------------------------------------
-- Referenced by:
--   new_order.php  -> INSERT of the columns listed below; validity check
--                     SELECT WHERE db_order ; get_count() reads MAX(myCount)
--   view_order.php / invoice.php -> SELECT (WHERE db_practice / db_order / 1 / date range)
--   open.php / print.php / pdf.php -> SELECT WHERE myCount / db_order
--   transaction.php -> SELECT WHERE db_practice
-- Columns confirmed used in code:
--   READ:  myCount, db_order, db_practice, db_patient, db_date,
--          db_FrameType, db_FramePrice, db_Rstatus, `LEFT`, `RIGHT`,
--          lens_left, db_lens (and the Rx fields for the printout).
--   The INSERT in new_order.php lists the full column set below.
-- Notes:
--   * `LEFT` and `RIGHT` are RESERVED WORDS and store the per-lens PRICE
--     (get_total_cost() sums LEFT + RIGHT). They MUST stay backtick-quoted.
--   * db_practice = doctors.pk ; db_order is the human order number (PK-ish, string).
--   * `db_rP.D` / `db_lP.D` contain a dot in the column name.
--   * myCount is a manual sequence (get_count() = MAX+1), so NOT auto-increment.
CREATE TABLE `prescription` (
  `myCount`         INT(100)     NOT NULL,            -- app-managed sequence (MAX+1)
  `invoice`         TEXT         NOT NULL,
  `db_order`        VARCHAR(100) NOT NULL DEFAULT '', -- human order number (looked up everywhere)
  `db_practice`     TEXT,                             -- = doctors.pk
  `db_patient`      TEXT,
  `db_lens`         TEXT,                             -- right-eye lens description
  `db_Vision`       TEXT,
  `lens_left`       TEXT,                             -- left-eye lens description
  `vision_left`     TEXT,
  `db_rSPH`         TEXT,
  `db_rCLYL`        TEXT,
  `db_rAXIS`        TEXT,
  `db_rP.D`         TEXT,
  `db_rSEG`         TEXT,
  `db_rRGD`         TEXT,
  `db_lSPH`         TEXT,
  `db_lCLYL`        TEXT,
  `db_lAXIS`        TEXT,
  `db_lP.D`         TEXT,
  `db_lSEG`         TEXT,
  `db_lRGD`         TEXT,
  `db_TINT`         TEXT,
  `db_FrameD`       TEXT,
  `db_sInstruction` TEXT,
  `db_FrameType`    TEXT,
  `db_FramePrice`   DOUBLE(10,2) DEFAULT NULL,
  `db_Ref`          TEXT,
  `db_Progress`     TEXT,
  `db_date`         TEXT,                             -- stored as 'YYYY-MM-DD' string
  `LEFT`            TEXT,                             -- RIGHT-lens PRICE (reserved word!)
  `RIGHT`           TEXT,                             -- LEFT-lens PRICE  (reserved word!)
  `extra`           TEXT,
  `db_Paid`         DOUBLE(10,2) DEFAULT NULL,
  `db_Bal`          DOUBLE(10,2) DEFAULT NULL,
  `db_Total`        DOUBLE(10,2) DEFAULT NULL,
  `db_Description`  TEXT,
  `db_Rstatus`      TEXT,                             -- shown as order "Status"
  `db_Lstatus`      TEXT,
  PRIMARY KEY (`myCount`),
  KEY `idx_prescription_order`    (`db_order`),
  KEY `idx_prescription_practice` (`db_practice`(20))
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
