<?php
require_once ("../includes/initialize.php");

echo "Guest ID from session: " . $_SESSION['GUESTID'];

if (!isset($_SESSION['GUESTID'])){
    redirect("index.php");
}

echo '
<table>
    <tr>
        <td width="87%" align="center">
            <h2>List of Booked Rooms</h2>
        </td>
    </tr>
</table>';

$guestId = 1; // Replace with a hardcoded value for testing
$query = "SELECT * 
          FROM `tblreservation` r
          JOIN `tblroom` rm ON r.`ROOMID` = rm.`ROOMID`
          JOIN `tblaccomodation` a ON a.`ACCOMID` = rm.`ACCOMID`
          WHERE r.`GUESTID` = $guestId";
          
$mydb->setQuery($query);
$res = $mydb->loadResultList();

var_dump($res); // This should show the reservation details


echo '<table id="table" class="fixnmix-table">
        <thead>
            <tr>
                <th align="center" width="120">Room</th>
                <th align="center" width="120">Check In</th>
                <th align="center" width="120">Check Out</th>
                <th width="120">Price</th>
                <th align="center" width="120">Nights</th>
                <th align="center" width="90">Amount</th>
            </tr>
        </thead>
        <tbody>';

if ($res) {
    foreach ($res as $result) {
        $day = (dateDiff($result->ARRIVAL, $result->DEPARTURE) > 0) ? dateDiff($result->ARRIVAL, $result->DEPARTURE) : 1;
        
        echo '<tr>';
        echo '<td>' . $result->ROOM . ' ' . $result->ROOMDESC . '</td>';
        echo '<td>' . date_format(date_create($result->ARRIVAL), "m/d/Y") . '</td>';
        echo '<td>' . date_format(date_create($result->DEPARTURE), "m/d/Y") . '</td>';
        echo '<td>&euro; ' . $result->RPRICE . '</td>';
        echo '<td>' . $day . '</td>';
        echo '<td>&euro; ' . ($result->RPRICE * $day) . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="6" align="center">No reservations found.</td></tr>';
}

echo '</tbody>
    </table>';
?>
