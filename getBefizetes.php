<?php
require_once './dbConnect.php';
$result='';
$tagid = filter_input(INPUT_GET, "tagid", FILTER_SANITIZE_NUMBER_INT);
$ev = filter_input(INPUT_GET, "evszam", FILTER_SANITIZE_NUMBER_INT);
if($tagid != null && $ev != null) {
    $sql = "SELECT `datum`,`osszeg` FROM `befiz` WHERE `azon` = ? AND YEAR(datum) = ? ORDER BY datum";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $tagid, $ev);
    $stmt->execute();
    $result = $stmt->get_result() or die("database error:". mysqli_error($conn));
    if($result != null && $result->num_rows > 0){
        $data = "<table>
            <thead>
                <tr>
                    <th>Befizetés dátuma</th>
                    <th>Befizetett összeg</th>
                </tr>
            </thead>
            <tbody>";
        $sum = 0;
        while( $row = $result->fetch_assoc() ) {
            $data .= '<tr>
            <td>'.$row["datum"].'</td>
            <td class="jobbra">'.number_format($row["osszeg"], 0, ',', ' ').' Ft</td>
        </tr>';
            $sum += $row["osszeg"];
        }
        $data .= '</tbody>
            <tfoot>
                <td class="jobbra">Mindösszesen</td>
                <th class="jobbra">'.number_format($sum, 0, ',', ' ').' Ft</th>
            </tfoot>
        </table>';
        echo $data;
    } else {
        echo 'Nincs befizetése!';
    }
} else {
    echo 0;
}
