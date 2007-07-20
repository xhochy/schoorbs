<h3>{$name}</h3>
 <table border=0>
   <tr>
    <td><strong>{get_vocab text="description"}</strong></td>
    <td>{$description|nl2br}</td>
   </tr>
   <tr>
    <td><strong>{get_vocab text="room"}:</strong></td>
    <td>{$area_name|nl2br} - {$room_name|nl2br}</td>
   </tr>
   <tr>
    <td><strong>{get_vocab text="start_date"}</strong></td>
    <td>{$start_date}</td>
   </tr>
   <tr>
    <td><strong>{get_vocab text="duration"}</strong></td>
    <td>{$duration} {$dur_units}</td>
   </tr>
   <tr>
    <td><strong>{get_vocab text="end_date"}</strong></td>
    <td>{$end_date}</td>
   </tr>
   