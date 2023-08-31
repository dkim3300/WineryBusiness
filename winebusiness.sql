--
--  First drop any existing tables. Any errors are ignored.
--
DROP TABLE Vineyard CASCADE CONSTRAINTS;
DROP TABLE Winery CASCADE CONSTRAINTS;
DROP TABLE PostalCode_City CASCADE CONSTRAINTS;
DROP TABLE PostalCode_Province CASCADE CONSTRAINTS;
DROP TABLE PostalCode_Climate CASCADE CONSTRAINTS;
DROP TABLE WineGrower CASCADE CONSTRAINTS;
DROP TABLE Experience_Certification CASCADE CONSTRAINTS;
DROP TABLE Experience_HourlyWage CASCADE CONSTRAINTS;
DROP TABLE Parcel CASCADE CONSTRAINTS;
DROP TABLE Grape CASCADE CONSTRAINTS;
DROP TABLE Wine CASCADE CONSTRAINTS;
DROP TABLE Bottle CASCADE CONSTRAINTS;
DROP TABLE TastingRoom CASCADE CONSTRAINTS;
DROP TABLE WineTasting CASCADE CONSTRAINTS;
DROP TABLE TastingType_FoodPairing CASCADE CONSTRAINTS;
DROP TABLE Provide CASCADE CONSTRAINTS;
--
-- Create new tables
-- 

CREATE TABLE Vineyard (
    BusinessName        CHAR(100),
    StreetAddress       CHAR(50),
    PostalCode          CHAR(10),
    Acres               INTEGER         DEFAULT 0,
    SoilType            CHAR(50),
    PRIMARY KEY (BusinessName, PostalCode),
    UNIQUE (StreetAddress, PostalCode)
);

CREATE TABLE Winery (
    BusinessName        CHAR(100),
    StreetAddress       CHAR(50),
    PostalCode          CHAR(10),
    Revenue             INTEGER         DEFAULT 0,
    ProductionCapacity  INTEGER         DEFAULT 0,
    PRIMARY KEY (BusinessName, PostalCode),
    UNIQUE (StreetAddress, PostalCode)
);

CREATE TABLE PostalCode_City (
    PostalCode          CHAR(10),
    City                CHAR(50),
    PRIMARY KEY (PostalCode)
);

CREATE TABLE PostalCode_Province (
    PostalCode          CHAR(10),
    Province            CHAR(50),
    PRIMARY KEY (PostalCode)
);

CREATE TABLE PostalCode_Climate (
    PostalCode          CHAR(10),
    Climate             CHAR(50),
    PRIMARY KEY (PostalCode)
);

CREATE TABLE WineGrower (
    WGID                INTEGER,
    WGName              CHAR(50),
    YearsExperience     INTEGER         DEFAULT 0,
    PRIMARY KEY (WGID)
);

CREATE TABLE Experience_Certification (
    YearsExperience     INTEGER,
    Certification       CHAR(50),
    PRIMARY KEY (YearsExperience)
);

CREATE TABLE Experience_HourlyWage (
    YearsExperience     INTEGER,
    HourlyWage          INTEGER,
    PRIMARY KEY (YearsExperience)
);

CREATE TABLE Grape (
    VarietyName         CHAR(50),
    GrowthDuration      INTEGER,
    Sweetness           CHAR(50),
    PRIMARY KEY (VarietyName)
);

CREATE TABLE Parcel (
    Latitude            DECIMAL(6,4),
    Longitude           DECIMAL(7,4),
    Altitude            INTEGER,
    BusinessName        CHAR(100)       NOT NULL,
    PostalCode          CHAR(10)        NOT NULL,
    WGID                INTEGER,
    VarietyName         CHAR(50),
    PlantDate           DATE,
    HarvestDate         DATE,
    PRIMARY KEY (Latitude, Longitude),
    FOREIGN KEY (BusinessName, PostalCode) REFERENCES Vineyard(BusinessName, PostalCode) ON DELETE CASCADE,
    FOREIGN KEY (WGID) REFERENCES WineGrower(WGID) ON DELETE SET NULL, 
    FOREIGN KEY (VarietyName) REFERENCES Grape(VarietyName) ON DELETE SET NULL
);

CREATE TABLE Wine (
    Vintage             INTEGER,
    WineName            CHAR(100),
    WineType            CHAR(50),
    AlcoholPercent      INTEGER,
    Body                CHAR(50),
    Acidity             CHAR(50),
    BusinessName        CHAR(100)       NOT NULL,
    PostalCode          CHAR(10)        NOT NULL,
    VarietyName         CHAR(50)        NOT NULL,
    PRIMARY KEY (WineName, Vintage),
    FOREIGN KEY (BusinessName, PostalCode) REFERENCES Winery(BusinessName, PostalCode) ON DELETE CASCADE,
    FOREIGN KEY (VarietyName) REFERENCES Grape(VarietyName)
);

CREATE TABLE Bottle (
    LotNum              INTEGER,
    MSRP                INTEGER,
    Volume              INTEGER,
    NumBottles          INTEGER,
    Vintage             INTEGER          NOT NULL,
    WineName            CHAR(100)        NOT NULL,
    PRIMARY KEY (LotNum),
    FOREIGN KEY (Vintage, WineName) REFERENCES Wine(Vintage, WineName)
);

CREATE TABLE TastingRoom (
    LiquorLicenseNum    INTEGER,
    RoomName            CHAR(50),
    Capacity            INTEGER,
    BusinessName        CHAR(100)       NOT NULL,
    PostalCode          CHAR(10)        NOT NULL,
    PRIMARY KEY (LiquorLicenseNum),
    FOREIGN KEY (BusinessName, PostalCode) REFERENCES Winery(BusinessName, PostalCode) ON DELETE CASCADE
);

CREATE TABLE WineTasting (
    DayOfWeek            CHAR(50),
    TimeOfDay            INTERVAL DAY(0) TO SECOND,
    TastingType          CHAR(80),
    LiquorLicenseNum     INTEGER,
    PRIMARY KEY (DayOfWeek, TimeOfDay, LiquorLicenseNum),
    FOREIGN KEY (LiquorLicenseNum) REFERENCES TastingRoom(LiquorLicenseNum) ON DELETE CASCADE
);

CREATE TABLE TastingType_FoodPairing (
    TastingType          CHAR(80),
    FoodPairing          CHAR(50),
    PRIMARY KEY (TastingType)
);

CREATE TABLE Provide (
    LiquorLicenseNum     INTEGER,
    DayOfWeek            CHAR(50),
    TimeOfDay            INTERVAL DAY(0) TO SECOND,
    Vintage              INTEGER,
    WineName             CHAR(100),
    PRIMARY KEY (LiquorLicenseNum, DayOfWeek, TimeOfDay, Vintage, WineName),
    FOREIGN KEY (LiquorLicenseNum, DayOfWeek, TimeOfDay) REFERENCES WineTasting(LiquorLicenseNum, DayOfWeek, TimeOfDay) ON DELETE CASCADE,
    FOREIGN KEY (Vintage, WineName) REFERENCES Wine(Vintage, WineName) ON DELETE SET NULL
);

--
-- Populate tables with values
--

INSERT INTO Vineyard VALUES ('Coastal Estate', '123 Vineyard Lane', 'V6A 0A1', 50, 'Loam');
INSERT INTO Vineyard VALUES ('Seaside Vineyard', '398 Ocean Road', 'V6K 7A5', 20, 'Silty Loam');
INSERT INTO Vineyard VALUES ('Sunset Bluffs', '745 Grape Road', 'V2A 2K5', 75, 'Silt');
INSERT INTO Vineyard VALUES ('Misty Meadows Vineyard', '863 Sunset Boulevard', 'V2A 1A1', 85, 'Silty Loam');
INSERT INTO Vineyard VALUES ('Ultimate Wine Producers', '456 Grapevine Road','L0S 1J0', 80, 'Sandy Loam');
INSERT INTO Vineyard VALUES ('Valley View Vineyards', '573 Mountain View Drive', 'L0S 1J9', 60, 'Loam');
INSERT INTO Vineyard VALUES ('Golden Harvest Growers', '333 Wine Street', 'L2E 6S6', 50, 'Sandy Loam');
INSERT INTO Vineyard VALUES ('Misty Meadows Vineyard', '111 Wine Street', 'L2E 6S6', 10, 'Sand');

INSERT INTO Winery VALUES ('Coastal Estate', '123 Vineyard Lane', 'V6A 0A1', 100000, 5000);
INSERT INTO Winery VALUES ('Mountain Winery', '1024 Green Drive', 'V6K 0C6', 200000, 12000);
INSERT INTO Winery VALUES ('Sunset Bluffs', '745 Grape Road', 'V2A 2K5', 150000, 7500);
INSERT INTO Winery VALUES ('Lakeside Winery', '555 Bluff Road', 'V2A 1A1', 60000, 2500);
INSERT INTO Winery VALUES ('Ultimate Wine Producers', '456 Grapevine Road', 'L0S 1J0', 175000, 8000);
INSERT INTO Winery VALUES ('Wine R Us', '197 Tourist Road', 'L0S 1J5', 130000, 6500);
INSERT INTO Winery VALUES ('Golden Harvest Growers', '333 Wine Street', 'L2E 6S6', 90000, 6000);
INSERT INTO Winery VALUES ('Lakeside Winery', '2476 Oak Street', 'L2E 1C5', 75000, 3500);

INSERT INTO PostalCode_City VALUES ('V6A 0A1', 'Vancouver');
INSERT INTO PostalCode_City VALUES ('V6K 7A5', 'Vancouver');
INSERT INTO PostalCode_City VALUES ('V6K 0C6', 'Vancouver');
INSERT INTO PostalCode_City VALUES ('V2A 2K5', 'Penticton');
INSERT INTO PostalCode_City VALUES ('V2A 1A1', 'Penticton');
INSERT INTO PostalCode_City VALUES ('L0S 1J0', 'Niagara-on-the-Lake');
INSERT INTO PostalCode_City VALUES ('L0S 1J5', 'Niagara-on-the-Lake');
INSERT INTO PostalCode_City VALUES ('L0S 1J9', 'Niagara-on-the-Lake');
INSERT INTO PostalCode_City VALUES ('L2E 6S6', 'Niagara Falls');
INSERT INTO PostalCode_City VALUES ('L2E 1C5', 'Niagara Falls');

INSERT INTO PostalCode_Province VALUES ('V6A 0A1', 'British Columbia');
INSERT INTO PostalCode_Province VALUES ('V6K 7A5', 'British Columbia');
INSERT INTO PostalCode_Province VALUES ('V6K 0C6', 'British Columbia');
INSERT INTO PostalCode_Province VALUES ('V2A 2K5', 'British Columbia');
INSERT INTO PostalCode_Province VALUES ('V2A 1A1', 'British Columbia');
INSERT INTO PostalCode_Province VALUES ('L0S 1J0', 'Ontario');
INSERT INTO PostalCode_Province VALUES ('L0S 1J5', 'Ontario');
INSERT INTO PostalCode_Province VALUES ('L0S 1J9', 'Ontario');
INSERT INTO PostalCode_Province VALUES ('L2E 6S6', 'Ontario');
INSERT INTO PostalCode_Province VALUES ('L2E 1C5', 'Ontario');

INSERT INTO PostalCode_Climate VALUES ('V6A 0A1', 'Temperate');
INSERT INTO PostalCode_Climate VALUES ('V6K 7A5', 'Temperate');
INSERT INTO PostalCode_Climate VALUES ('V2A 2K5', 'Dry');
INSERT INTO PostalCode_Climate VALUES ('V2A 1A1', 'Dry');
INSERT INTO PostalCode_Climate VALUES ('L0S 1J0', 'Continental');
INSERT INTO PostalCode_Climate VALUES ('L0S 1J9', 'Continental');
INSERT INTO PostalCode_Climate VALUES ('L2E 6S6', 'Continental');

INSERT INTO Winegrower VALUES (10023, 'John Smith', 15);
INSERT INTO Winegrower VALUES (10057, 'Emma Johnson', 8);
INSERT INTO Winegrower VALUES (20045, 'Michael Williams', 4);
INSERT INTO Winegrower VALUES (90099, 'Olivia Brown', 3);
INSERT INTO Winegrower VALUES (10182, 'William Jones', 20);
INSERT INTO Winegrower VALUES (90161, 'Ava Davis', 8);
INSERT INTO Winegrower VALUES (20001, 'James Miller', 18);
INSERT INTO Winegrower VALUES (90917, 'Sophia Wilson', 2);
INSERT INTO Winegrower VALUES (10315, 'Alexander Taylor', 14);
INSERT INTO Winegrower VALUES (10368, 'Isabella Martinez', 9);

INSERT INTO Experience_Certification VALUES (15, 'Advanced');
INSERT INTO Experience_Certification VALUES (8, 'Intermediate');
INSERT INTO Experience_Certification VALUES (4, 'Beginner');
INSERT INTO Experience_Certification VALUES (3, 'Beginner');
INSERT INTO Experience_Certification VALUES (20, 'Advanced');
INSERT INTO Experience_Certification VALUES (18, 'Advanced');
INSERT INTO Experience_Certification VALUES (2, 'Beginner');
INSERT INTO Experience_Certification VALUES (14, 'Advanced');
INSERT INTO Experience_Certification VALUES (9, 'Intermediate');

INSERT INTO Experience_HourlyWage VALUES (15, 40);
INSERT INTO Experience_HourlyWage VALUES (8, 30);
INSERT INTO Experience_HourlyWage VALUES (4, 20);
INSERT INTO Experience_HourlyWage VALUES (3, 20);
INSERT INTO Experience_HourlyWage VALUES (20, 40);
INSERT INTO Experience_HourlyWage VALUES (18, 40);
INSERT INTO Experience_HourlyWage VALUES (2, 20);
INSERT INTO Experience_HourlyWage VALUES (14, 40);
INSERT INTO Experience_HourlyWage VALUES (9, 30);

INSERT INTO Grape VALUES ('Cabernet Sauvignon', 290, 'medium-sweet');
INSERT INTO Grape VALUES ('Chardonnay', 210, 'dry');
INSERT INTO Grape VALUES ('Merlot', 230, 'semi-sweet');
INSERT INTO Grape VALUES ('Pinot Noir', 240, 'medium-dry');
INSERT INTO Grape VALUES ('Riesling', 150, 'very sweet');
INSERT INTO Grape VALUES ('Gewurztraminer', 200, 'sweet');
INSERT INTO Grape VALUES ('Syrah', 270, 'dry');
INSERT INTO Grape VALUES ('Gamay Noir', 180, 'semi-sweet');
INSERT INTO Grape VALUES ('Sauvignon Blanc', 210, 'dry');
INSERT INTO Grape VALUES ('Pinot Gris', 220, 'medium-dry');

INSERT INTO Parcel VALUES (49.2835, -123.1207, 100, 'Coastal Estate', 'V6A 0A1', 10023, 'Cabernet Sauvignon', '13-MAR-23', '18-OCT-23');
INSERT INTO Parcel VALUES (49.2827, -123.1178, 110, 'Coastal Estate', 'V6A 0A1', 10023, 'Merlot', '26-FEB-23', '08-SEP-23');
INSERT INTO Parcel VALUES (49.2821, -123.1256, 90, 'Coastal Estate', 'V6A 0A1', 10023, 'Pinot Noir', '02-APR-23', '23-OCT-23');
INSERT INTO Parcel VALUES (49.1947, -123.1792, 20, 'Seaside Vineyard', 'V6K 7A5', 10057, 'Gewurztraminer', '15-MAR-23', '27-SEP-23');
INSERT INTO Parcel VALUES (49.1896, -123.2013, 30, 'Seaside Vineyard', 'V6K 7A5', 10057, 'Pinot Gris', '12-MAR-23', '06-OCT-23');
INSERT INTO Parcel VALUES (49.4958, -119.6168, 300, 'Sunset Bluffs', 'V2A 2K5', 90099, 'Gamay Noir', '02-MAR-23', '16-SEP-23');
INSERT INTO Parcel VALUES (49.4920, -119.6123, 310, 'Sunset Bluffs', 'V2A 2K5', 10182, 'Sauvignon Blanc', '26-FEB-23', '03-OCT-23');
INSERT INTO Parcel VALUES (49.4925, -119.6210, 290, 'Sunset Bluffs', 'V2A 2K5', 90099, 'Cabernet Sauvignon', '02-APR-23', '19-OCT-23');
INSERT INTO Parcel VALUES (49.4988, -119.6150, 320, 'Sunset Bluffs', 'V2A 2K5', 10182, 'Chardonnay', '09-APR-23', '26-SEP-23');
INSERT INTO Parcel VALUES (49.5120, -119.7235, 340, 'Misty Meadows Vineyard', 'V2A 1A1', 90161, 'Merlot', '23-MAR-23', '12-OCT-23');
INSERT INTO Parcel VALUES (49.5103, -119.7268, 350, 'Misty Meadows Vineyard', 'V2A 1A1', 90099, 'Chardonnay', '26-MAR-23', '08-OCT-23');
INSERT INTO Parcel VALUES (49.5137, -119.7213, 360, 'Misty Meadows Vineyard', 'V2A 1A1', 90161, 'Pinot Noir', '11-MAR-23', '05-OCT-23');
INSERT INTO Parcel VALUES (49.5146, -119.7251, 330, 'Misty Meadows Vineyard', 'V2A 1A1', 10182, 'Riesling', '25-FEB-23', '28-SEP-23');
INSERT INTO Parcel VALUES (43.1557, -79.0023, 80, 'Ultimate Wine Producers', 'L0S 1J0', 20001, 'Cabernet Sauvignon', '09-APR-23', '08-SEP-23');
INSERT INTO Parcel VALUES (43.1521, -79.0054, 90, 'Ultimate Wine Producers', 'L0S 1J0', 90917, 'Chardonnay', '23-MAR-23', '28-SEP-23');
INSERT INTO Parcel VALUES (43.1573, -79.0012, 70, 'Ultimate Wine Producers', 'L0S 1J0', 90917, 'Merlot', '01-APR-23', '13-OCT-23');
INSERT INTO Parcel VALUES (43.1550, -79.0037, 80, 'Ultimate Wine Producers', 'L0S 1J0', 20001, 'Pinot Noir', '19-MAR-23', '24-OCT-23');
INSERT INTO Parcel VALUES (43.2878, -79.1236, 90, 'Valley View Vineyards', 'L0S 1J9', 90917, 'Riesling', '20-MAR-23', '18-SEP-23');
INSERT INTO Parcel VALUES (43.2896, -79.1213, 100, 'Valley View Vineyards', 'L0S 1J9', 20001, 'Gewurztraminer', '19-FEB-23', '07-OCT-23');
INSERT INTO Parcel VALUES (43.2857, -79.1198, 80, 'Valley View Vineyards', 'L0S 1J9', 90917, 'Chardonnay', '12-APR-23', '01-OCT-23');
INSERT INTO Parcel VALUES (43.0850, -79.0876, 100, 'Golden Harvest Growers', 'L2E 6S6', 10315, 'Sauvignon Blanc', '08-MAR-23', '13-SEP-23');
INSERT INTO Parcel VALUES (43.0874, -79.0897, 90, 'Golden Harvest Growers', 'L2E 6S6', 10368, 'Pinot Gris', '18-MAR-23', '04-OCT-23');
INSERT INTO Parcel VALUES (43.1034, -79.0755, 100, 'Misty Meadows Vineyard', 'L2E 6S6', 10315, 'Cabernet Sauvignon', '23-MAR-23', '23-OCT-23');
    
INSERT INTO Wine VALUES (2017, 'Coastal Estate Reserve Cabernet Sauvignon', 'red', 13, 'med+', 'low+', 'Coastal Estate', 'V6A 0A1', 'Cabernet Sauvignon');
INSERT INTO Wine VALUES (2020, 'Coastal Estate Chardonnay', 'white', 12, 'low+', 'med', 'Coastal Estate', 'V6A 0A1', 'Chardonnay');
INSERT INTO Wine VALUES (2021, 'Coastal Estate Merlot Rose', 'rose', 11, 'med', 'med-', 'Coastal Estate', 'V6A 0A1', 'Merlot');
INSERT INTO Wine VALUES (2018, 'Coastal Estate Pinot Noir', 'red', 14, 'med-', 'med-', 'Coastal Estate', 'V6A 0A1', 'Pinot Noir');
INSERT INTO Wine VALUES (2022, 'Coastal Estate Riesling Icewine', 'late-harvest icewine', 10, 'full', 'low', 'Coastal Estate', 'V6A 0A1', 'Riesling');
INSERT INTO Wine VALUES (2020, 'Mountain Winery Merlot', 'red', 12, 'med', 'med-', 'Mountain Winery', 'V6K 0C6', 'Merlot');
INSERT INTO Wine VALUES (2021, 'Mountain Winery Gewurztraminer', 'white', 14, 'med ', 'low+', 'Mountain Winery', 'V6K 0C6', 'Gewurztraminer');
INSERT INTO Wine VALUES (2017, 'Mountain Winery Syrah', 'red', 13, 'full', 'low+', 'Mountain Winery', 'V6K 0C6', 'Syrah');
INSERT INTO Wine VALUES (2023, 'Mountain Winery Chardonnay', 'white', 13, 'med-', 'med', 'Mountain Winery', 'V6K 0C6', 'Chardonnay');
INSERT INTO Wine VALUES (2018, 'Mountain Winery Pinot Noir Rose', 'rose', 14, 'med', 'med+', 'Mountain Winery', 'V6K 0C6', 'Pinot Noir');
INSERT INTO Wine VALUES (2022, 'Sunset Bluffs Gamay Noir', 'red', 11, 'low', 'med', 'Sunset Bluffs', 'V2A 2K5', 'Gamay Noir');
INSERT INTO Wine VALUES (2023, 'Sunset Bluffs Sauvignon Blanc', 'white', 12, 'low+', 'med+', 'Sunset Bluffs', 'V2A 2K5', 'Sauvignon Blanc');
INSERT INTO Wine VALUES (2020, 'Sunset Bluffs Cabernet Sauvignon', 'red', 13, 'med-', 'med-', 'Sunset Bluffs', 'V2A 2K5', 'Cabernet Sauvignon');
INSERT INTO Wine VALUES (2017, 'Sunset Bluffs Pinot Noir Reserve', 'red', 14, 'med-', 'med', 'Sunset Bluffs', 'V2A 2K5', 'Pinot Noir');
INSERT INTO Wine VALUES (2021, 'Sunset Bluffs Chardonnay', 'white', 13, 'med-', 'med', 'Sunset Bluffs', 'V2A 2K5', 'Chardonnay');
INSERT INTO Wine VALUES (2020, 'Lakeside Winery Merlot', 'red', 13, 'med', 'med-', 'Lakeside Winery', 'V2A 1A1', 'Merlot');
INSERT INTO Wine VALUES (2021, 'Lakeside Winery Chardonnay', 'white', 12, 'low+', 'med+', 'Lakeside Winery', 'V2A 1A1', 'Chardonnay');
INSERT INTO Wine VALUES (2017, 'Lakeside Winery Cabernet Sauvignon', 'rosé', 14, 'med-', 'med+', 'Lakeside Winery', 'V2A 1A1', 'Cabernet Sauvignon');
INSERT INTO Wine VALUES (2018, 'Lakeside Winery Pinot Noir Reserve', 'red', 15, 'med-', 'med', 'Lakeside Winery', 'V2A 1A1', 'Pinot Noir');
INSERT INTO Wine VALUES (2022, 'Lakeside Winery Riesling Icewine', 'late-harvest icewine', 10, 'full', 'low', 'Lakeside Winery', 'V2A 1A1', 'Riesling');
INSERT INTO Wine VALUES (2018, 'Ultimate Wine Producers Reserve Cabernet Sauvignon', 'red', 15, 'med-', 'med-', 'Ultimate Wine Producers', 'L0S 1J0', 'Cabernet Sauvignon');
INSERT INTO Wine VALUES (2019, 'Ultimate Wine Producers Chardonnay', 'white', 14, 'med', 'med-', 'Ultimate Wine Producers', 'L0S 1J0', 'Chardonnay');
INSERT INTO Wine VALUES (2020, 'Ultimate Wine Producers Merlot Rose', 'rose', 13, 'low+', 'med-', 'Ultimate Wine Producers', 'L0S 1J0', 'Merlot');
INSERT INTO Wine VALUES (2017, 'Ultimate Wine Producers Pinot Noir Reserve', 'red', 16, 'med-', 'med', 'Ultimate Wine Producers', 'L0S 1J0', 'Pinot Noir');
INSERT INTO Wine VALUES (2019, 'Ultimate Wine Producers Gewurztraminer', 'white', 11, 'med', 'low+', 'Ultimate Wine Producers', 'L0S 1J0', 'Gewurztraminer');
INSERT INTO Wine VALUES (2019, 'Wine R Us Merlot', 'red', 12, 'med', 'med-', 'Wine R Us', 'L0S 1J5', 'Merlot');
INSERT INTO Wine VALUES (2022, 'Wine R Us Chardonnay', 'white', 11, 'low+', 'low+', 'Wine R Us', 'L0S 1J5', 'Chardonnay');
INSERT INTO Wine VALUES (2020, 'Wine R Us Cabernet Sauvignon', 'red', 13, 'med-', 'med', 'Wine R Us', 'L0S 1J5', 'Cabernet Sauvignon');
INSERT INTO Wine VALUES (2019, 'Wine R Us Pinot Noir Reserve', 'red', 14, 'med-', 'med-', 'Wine R Us', 'L0S 1J5', 'Pinot Noir');
INSERT INTO Wine VALUES (2023, 'Wine R Us Sauvignon Blanc', 'white', 10, 'low+', 'med+', 'Wine R Us', 'L0S 1J5', 'Sauvignon Blanc');
INSERT INTO Wine VALUES (2019, 'Golden Harvest Growers Merlot', 'red', 13, 'med', 'med-', 'Golden Harvest Growers', 'L2E 6S6', 'Merlot');
INSERT INTO Wine VALUES (2020, 'Golden Harvest Growers Chardonnay', 'white', 12, 'low+', 'med', 'Golden Harvest Growers', 'L2E 6S6', 'Chardonnay');
INSERT INTO Wine VALUES (2018, 'Golden Harvest Growers Cabernet Sauvignon Reserve', 'red', 15, 'med-', 'med', 'Golden Harvest Growers', 'L2E 6S6', 'Cabernet Sauvignon');
INSERT INTO Wine VALUES (2017, 'Golden Harvest Growers Pinot Noir', 'red', 16, 'med-', 'med-', 'Golden Harvest Growers', 'L2E 6S6', 'Pinot Noir');
INSERT INTO Wine VALUES (2019, 'Golden Harvest Growers Sauvignon Blanc', 'white', 11, 'low+', 'med+', 'Golden Harvest Growers', 'L2E 6S6', 'Sauvignon Blanc');
INSERT INTO Wine VALUES (2020, 'Lakeside Winery Merlot Premium', 'red', 13, 'med', 'med-', 'Lakeside Winery', 'L2E 1C5', 'Merlot');
INSERT INTO Wine VALUES (2019, 'Lakeside Winery Chardonnay', 'white', 12, 'low+', 'med+', 'Lakeside Winery', 'L2E 1C5', 'Chardonnay');
INSERT INTO Wine VALUES (2019, 'Lakeside Winery Cabernet Sauvignon Premium', 'red', 14, 'med-', 'med-', 'Lakeside Winery', 'L2E 1C5', 'Cabernet Sauvignon');
INSERT INTO Wine VALUES (2018, 'Lakeside Winery Reserve Pinot Noir', 'red', 15, 'med-', 'med', 'Lakeside Winery', 'L2E 1C5', 'Pinot Noir');
INSERT INTO Wine VALUES (2022, 'Lakeside Winery Pinot Gris', 'white', 11, 'med-', 'med', 'Lakeside Winery', 'L2E 1C5', 'Pinot Gris');

INSERT INTO Bottle VALUES (1052, 30, 750, 100, 2017, 'Coastal Estate Reserve Cabernet Sauvignon');
INSERT INTO Bottle VALUES (1037, 25, 750, 200, 2020, 'Coastal Estate Chardonnay');
INSERT INTO Bottle VALUES (1043, 20, 750, 150, 2021, 'Coastal Estate Merlot Rose');
INSERT INTO Bottle VALUES (1051, 35, 750, 80, 2018, 'Coastal Estate Pinot Noir');
INSERT INTO Bottle VALUES (1028, 50, 375, 120, 2022, 'Coastal Estate Riesling Icewine');
INSERT INTO Bottle VALUES (1010, 28, 750, 90, 2020, 'Mountain Winery Merlot');
INSERT INTO Bottle VALUES (1024, 18, 750, 180, 2021, 'Mountain Winery Gewurztraminer');
INSERT INTO Bottle VALUES (1045, 40, 750, 70, 2017, 'Mountain Winery Syrah');
INSERT INTO Bottle VALUES (1021, 22, 750, 250, 2023, 'Mountain Winery Chardonnay');
INSERT INTO Bottle VALUES (1033, 32, 750, 60, 2018, 'Mountain Winery Pinot Noir Rose');
INSERT INTO Bottle VALUES (1040, 25, 750, 110, 2022, 'Sunset Bluffs Gamay Noir');
INSERT INTO Bottle VALUES (1029, 28, 750, 90, 2023, 'Sunset Bluffs Sauvignon Blanc');
INSERT INTO Bottle VALUES (1027, 30, 750, 100, 2020, 'Sunset Bluffs Cabernet Sauvignon');
INSERT INTO Bottle VALUES (1019, 35, 750, 85, 2017, 'Sunset Bluffs Pinot Noir Reserve');
INSERT INTO Bottle VALUES (1016, 38, 375, 75, 2021, 'Sunset Bluffs Chardonnay');
INSERT INTO Bottle VALUES (1008, 32, 750, 90, 2020, 'Lakeside Winery Merlot');
INSERT INTO Bottle VALUES (1050, 18, 750, 120, 2021, 'Lakeside Winery Chardonnay');
INSERT INTO Bottle VALUES (1001, 26, 750, 70, 2017, 'Lakeside Winery Cabernet Sauvignon');
INSERT INTO Bottle VALUES (1034, 35, 750, 85, 2018, 'Lakeside Winery Pinot Noir Reserve');
INSERT INTO Bottle VALUES (1022, 48, 375, 40, 2022, 'Lakeside Winery Riesling Icewine');
INSERT INTO Bottle VALUES (1006, 32, 750, 90, 2018, 'Ultimate Wine Producers Reserve Cabernet Sauvignon');
INSERT INTO Bottle VALUES (1026, 20, 750, 180, 2019, 'Ultimate Wine Producers Chardonnay');
INSERT INTO Bottle VALUES (1048, 25, 750, 120, 2020, 'Ultimate Wine Producers Merlot Rose');
INSERT INTO Bottle VALUES (1031, 35, 750, 85, 2017, 'Ultimate Wine Producers Pinot Noir Reserve');
INSERT INTO Bottle VALUES (1054, 28, 750, 100, 2019, 'Ultimate Wine Producers Gewurztraminer');
INSERT INTO Bottle VALUES (1046, 30, 750, 100, 2019, 'Wine R Us Merlot');
INSERT INTO Bottle VALUES (1012, 22, 750, 250, 2022, 'Wine R Us Chardonnay');
INSERT INTO Bottle VALUES (1042, 20, 750, 180, 2020, 'Wine R Us Cabernet Sauvignon');
INSERT INTO Bottle VALUES (1053, 35, 750, 85, 2019, 'Wine R Us Pinot Noir Reserve');
INSERT INTO Bottle VALUES (1004, 48, 375, 40, 2023, 'Wine R Us Sauvignon Blanc');
INSERT INTO Bottle VALUES (1049, 28, 750, 100, 2019, 'Golden Harvest Growers Merlot');
INSERT INTO Bottle VALUES (1003, 20, 750, 150, 2020, 'Golden Harvest Growers Chardonnay');
INSERT INTO Bottle VALUES (1038, 25, 750, 120, 2018, 'Golden Harvest Growers Cabernet Sauvignon Reserve');
INSERT INTO Bottle VALUES (1055, 35, 750, 85, 2017, 'Golden Harvest Growers Pinot Noir');
INSERT INTO Bottle VALUES (1020, 28, 750, 100, 2019, 'Golden Harvest Growers Sauvignon Blanc');
INSERT INTO Bottle VALUES (1036, 30, 750, 100, 2020, 'Lakeside Winery Merlot');
INSERT INTO Bottle VALUES (1005, 18, 750, 120, 2019, 'Lakeside Winery Chardonnay');
INSERT INTO Bottle VALUES (1035, 26, 750, 70, 2019, 'Lakeside Winery Cabernet Sauvignon Premium');
INSERT INTO Bottle VALUES (1041, 35, 750, 85, 2018, 'Lakeside Winery Reserve Pinot Noir');
INSERT INTO Bottle VALUES (1023, 48, 375, 40, 2022, 'Lakeside Winery Pinot Gris');

INSERT INTO TastingRoom VALUES (53089746, 'Coastal Lounge', 50, 'Coastal Estate', 'V6A 0A1');
INSERT INTO TastingRoom VALUES (30147856, 'Vineyard View', 40, 'Coastal Estate', 'V6A 0A1');
INSERT INTO TastingRoom VALUES (96482031, 'Ocean Breeze', 35, 'Coastal Estate', 'V6A 0A1');
INSERT INTO TastingRoom VALUES (19438562, 'Mountain Vista', 60, 'Mountain Winery', 'V6K 0C6');
INSERT INTO TastingRoom VALUES (83547028, 'Green Oasis', 30, 'Mountain Winery', 'V6K 0C6');
INSERT INTO TastingRoom VALUES (29571648, 'Sunset Deck', 45, 'Sunset Bluffs', 'V2A 2K5');
INSERT INTO TastingRoom VALUES (60918725, 'Grape Terrace', 35, 'Sunset Bluffs', 'V2A 2K5');
INSERT INTO TastingRoom VALUES (73984615, 'Lake View Lounge', 55, 'Lakeside Winery', 'V2A 1A1');
INSERT INTO TastingRoom VALUES (15039782, 'Bluffside Retreat', 25, 'Lakeside Winery', 'V2A 1A1');
INSERT INTO TastingRoom VALUES (47250938, 'Harbor View', 30, 'Lakeside Winery', 'V2A 1A1');
INSERT INTO TastingRoom VALUES (38567049, 'Ultimate Tasting Hall', 80, 'Ultimate Wine Producers', 'L0S 1J0');
INSERT INTO TastingRoom VALUES (92740568, 'Rustic Cellar', 50, 'Ultimate Wine Producers', 'L0S 1J0');
INSERT INTO TastingRoom VALUES (50491826, 'Wine R Us Tasting Room', 60, 'Wine R Us', 'L0S 1J5');
INSERT INTO TastingRoom VALUES (83560219, 'Tourist Delight', 40, 'Wine R Us', 'L0S 1J5');
INSERT INTO TastingRoom VALUES (60214879, 'Golden Harvest Tasting Lounge', 70, 'Golden Harvest Growers', 'L2E 6S6');
INSERT INTO TastingRoom VALUES (47198265, 'Fallsview Terrace', 30, 'Golden Harvest Growers', 'L2E 6S6');
INSERT INTO TastingRoom VALUES (86253917, 'Oak Street Cellar', 40, 'Lakeside Winery', 'L2E 1C5');
INSERT INTO TastingRoom VALUES (21048396, 'Niagara Retreat', 20, 'Lakeside Winery', 'L2E 1C5');

INSERT INTO WineTasting VALUES ('Monday', INTERVAL '14:00:00' HOUR TO SECOND, 'Wine and Cheese', 53089746);
INSERT INTO WineTasting VALUES ('Tuesday', INTERVAL '16:30:00' HOUR TO SECOND, 'Wine with Fresh Meat', 30147856);
INSERT INTO WineTasting VALUES ('Wednesday', INTERVAL '18:15:00' HOUR TO SECOND, 'Ultimate Tasting', 96482031);
INSERT INTO WineTasting VALUES ('Thursday', INTERVAL '15:45:00' HOUR TO SECOND, 'Wine and Cheese', 19438562);
INSERT INTO WineTasting VALUES ('Friday', INTERVAL '17:20:00' HOUR TO SECOND, 'Wine with Fresh Meat', 83547028);
INSERT INTO WineTasting VALUES ('Saturday', INTERVAL '12:00:00' HOUR TO SECOND, 'Ultimate Tasting', 29571648);
INSERT INTO WineTasting VALUES ('Sunday', INTERVAL '13:30:00' HOUR TO SECOND, 'Wine and Cheese', 60918725);
INSERT INTO WineTasting VALUES ('Monday', INTERVAL '19:45:00' HOUR TO SECOND, 'Wine with Fresh Meat', 73984615);
INSERT INTO WineTasting VALUES ('Tuesday', INTERVAL '20:00:00' HOUR TO SECOND, 'Ultimate Tasting', 15039782);
INSERT INTO WineTasting VALUES ('Wednesday', INTERVAL '15:10:00' HOUR TO SECOND, 'Wine and Cheese', 47250938);
INSERT INTO WineTasting VALUES ('Thursday', INTERVAL '17:35:00' HOUR TO SECOND, 'Wine with Fresh Meat', 38567049);
INSERT INTO WineTasting VALUES ('Friday', INTERVAL '14:40:00' HOUR TO SECOND, 'Ultimate Tasting', 83560219);
INSERT INTO WineTasting VALUES ('Saturday', INTERVAL '12:30:00' HOUR TO SECOND, 'Wine and Cheese', 47198265);

INSERT INTO TastingType_FoodPairing VALUES ('Wine and Cheese', 'Cheeseboard');
INSERT INTO TastingType_FoodPairing VALUES ('Wine with Fresh Meat', 'Charcuterie Board');
INSERT INTO TastingType_FoodPairing VALUES ('Ultimate Tasting', '3-Course Meal');

INSERT INTO Provide VALUES (53089746, 'Monday', INTERVAL '14:00:00' HOUR TO SECOND, 2017, 'Coastal Estate Reserve Cabernet Sauvignon');
INSERT INTO Provide VALUES (30147856, 'Tuesday', INTERVAL '16:30:00' HOUR TO SECOND, 2021, 'Coastal Estate Merlot Rose');
INSERT INTO Provide VALUES (96482031, 'Wednesday', INTERVAL '18:15:00' HOUR TO SECOND, 2022, 'Sunset Bluffs Gamay Noir');
INSERT INTO Provide VALUES (19438562, 'Thursday', INTERVAL '15:45:00' HOUR TO SECOND, 2017, 'Golden Harvest Growers Pinot Noir');
INSERT INTO Provide VALUES (83547028, 'Friday', INTERVAL '17:20:00' HOUR TO SECOND, 2019, 'Ultimate Wine Producers Chardonnay');
INSERT INTO Provide VALUES (29571648, 'Saturday', INTERVAL '12:00:00' HOUR TO SECOND, 2019, 'Wine R Us Pinot Noir Reserve');
INSERT INTO Provide VALUES (60918725, 'Sunday', INTERVAL '13:30:00' HOUR TO SECOND, 2021, 'Mountain Winery Gewurztraminer');
INSERT INTO Provide VALUES (73984615, 'Monday', INTERVAL '19:45:00' HOUR TO SECOND, 2018, 'Lakeside Winery Reserve Pinot Noir');
INSERT INTO Provide VALUES (15039782, 'Tuesday', INTERVAL '20:00:00' HOUR TO SECOND, 2020, 'Coastal Estate Chardonnay');
INSERT INTO Provide VALUES (47250938, 'Wednesday', INTERVAL '15:10:00' HOUR TO SECOND, 2022, 'Wine R Us Chardonnay');
INSERT INTO Provide VALUES (38567049, 'Thursday', INTERVAL '17:35:00' HOUR TO SECOND, 2020, 'Ultimate Wine Producers Merlot Rose');
INSERT INTO Provide VALUES (83560219, 'Friday', INTERVAL '14:40:00' HOUR TO SECOND, 2019, 'Golden Harvest Growers Sauvignon Blanc');
INSERT INTO Provide VALUES (47198265, 'Saturday', INTERVAL '12:30:00' HOUR TO SECOND, 2017, 'Lakeside Winery Cabernet Sauvignon');
INSERT INTO Provide VALUES (53089746, 'Monday', INTERVAL '14:00:00' HOUR TO SECOND, 2023, 'Sunset Bluffs Sauvignon Blanc');
INSERT INTO Provide VALUES (30147856, 'Tuesday', INTERVAL '16:30:00' HOUR TO SECOND, 2021, 'Sunset Bluffs Chardonnay');