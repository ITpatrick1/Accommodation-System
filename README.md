#Overview
This SQL dump file contains the structure and data for the dragonhousedb database. The database is designed to manage information related to a hotel system, including accommodation details, reservations, guests, payments, and more. This file was generated using phpMyAdmin version 5.2.1 and is compatible with MariaDB server version 10.4.32.

#Database Information
Host: 127.0.0.1
Generation Time: Aug 19, 2024 at 01:39 PM
Server Version: 10.4.32-MariaDB
PHP Version: 8.2.12
Tables and Descriptions
tblaccomodation
Stores information about different types of accommodations.

ACCOMID (INT): Unique identifier for accommodation.
ACCOMODATION (VARCHAR): Name of the accommodation type.
ACCOMDESC (VARCHAR): Description of the accommodation.
tblauto
Stores automatic records, with unclear specifics.

autoid (INT): Unique identifier.
start (INT): Start value.
end (INT): End value.
tblfirstpartition
Contains initial partition information with details about the hotel.

FirstPID (INT): Unique identifier for the partition.
FirstPTitle (TEXT): Title of the partition.
FirstPImage (VARCHAR): Image associated with the partition.
FirstPSubTitle (TEXT): Subtitle of the partition.
FirstPDescription (TEXT): Description of the partition.
tblguest
Holds guest information.

GUESTID (INT): Unique identifier for the guest.
G_FNAME (VARCHAR): First name of the guest.
G_LNAME (VARCHAR): Last name of the guest.
G_CITY (VARCHAR): City of the guest.
G_ADDRESS (VARCHAR): Address of the guest.
G_PHONE (VARCHAR): Phone number of the guest.
G_NATIONALITY (VARCHAR): Nationality of the guest.
G_EMAIL (VARCHAR): Email address of the guest.
G_UNAME (VARCHAR): Username of the guest.
G_PASS (VARCHAR): Password hash of the guest.
LOCATION (VARCHAR): Guest location.
tblmeal
Details different meal types and their prices.

MealID (INT): Unique identifier for the meal.
MealType (VARCHAR): Type of the meal.
MealPrice (DOUBLE): Price of the meal.
tblpayment
Records payment transactions.

SUMMARYID (INT): Unique identifier for the payment summary.
TRANSDATE (DATETIME): Date and time of the transaction.
CONFIRMATIONCODE (VARCHAR): Confirmation code for the payment.
PQTY (INT): Quantity of items paid for.
GUESTID (INT): Reference to the guest who made the payment.
SPRICE (DOUBLE): Total price.
MSGVIEW (TINYINT): Message view flag.
STATUS (VARCHAR): Payment status.
tblreservation
Manages room reservations.

RESERVEID (INT): Unique identifier for the reservation.
CONFIRMATIONCODE (VARCHAR): Confirmation code for the reservation.
TRANSDATE (DATE): Date of the transaction.
ROOMID (INT): Reference to the room reserved.
ARRIVAL (DATETIME): Arrival date and time.
DEPARTURE (DATETIME): Departure date and time.
RPRICE (DOUBLE): Price of the reservation.
GUESTID (INT): Reference to the guest making the reservation.
PRORPOSE (VARCHAR): Purpose of the reservation.
STATUS (VARCHAR): Reservation status.
BOOKDATE (DATETIME): Date when the reservation was made.
REMARKS (TEXT): Additional remarks.
USERID (INT): Reference to the user who made the reservation.
tblroom
Contains information about rooms available in the hotel.

ROOMID (INT): Unique identifier for the room.
ROOMNUM (INT): Room number.
ACCOMID (INT): Reference to the accommodation type.
ROOM (VARCHAR): Name or type of the room.
ROOMDESC (VARCHAR): Description of the room.
NUMPERSON (INT): Number of people the room accommodates.
PRICE (DOUBLE): Price of the room.
ROOMIMAGE (VARCHAR): Image of the room.
OROOMNUM (INT): Original room number.
RoomTypeID (INT): Reference to the room type.
tblroomtype
Defines different room types.

RoomTypeID (INT): Unique identifier for the room type.
RoomType (VARCHAR): Name of the room type.
tblslideshow
Handles slideshow images and their captions.

SlideID (INT): Unique identifier for the slide.
SlideImage (TEXT): Image associated with the slide.
SlideCaption (TEXT): Caption for the slide.
tbltitle
Contains title and subtitle information.

TItleID (INT): Unique identifier for the title.
Title (TEXT): Title text.
Subtitle (TEXT): Subtitle text.
tbluseraccount
Manages user accounts for the system.

USERID (INT): Unique identifier for the user.
UNAME (VARCHAR): Username of the user.
USER_NAME (VARCHAR): Full name of the user.
UPASS (VARCHAR): Password hash of the user.
ROLE (VARCHAR): Role of the user (e.g., Administrator, Guest In-charge).
PHONE (INT): Phone number of the user.
tbl_setting_contact
Stores contact settings for the organization.

SetCon_ID (INT): Unique identifier for the contact settings.
SetConLocation (VARCHAR): Location address.
SetConEmail (VARCHAR): Contact email.
SetConContactNo (VARCHAR): Contact phone number.
Indexes
Each table has primary keys defined to ensure uniqueness and efficient querying. Some tables also have additional keys to improve performance on specific queries.

Auto-Increment
Auto-increment is enabled for primary key columns to automatically generate unique identifiers for new records.
