<h1>
  <?php echo $room->getArea()->getName(); ?> &#8594; <?php echo $room->getName(); ?>
</h1>
<h3>
  <em><?php echo Lang::_('View Month'); ?>:</em>
  <?php echo Lang::_(date('F', $nStartTime)).' '.date('Y', $nStartTime); ?>
</h3>

<div id="schoorbs-goto-next-week">
  <a href="<?php echo self::makeInternalUrl('month-view.php', array('month' => date('n', $nextMonth), 'year' => date('Y', $nextMonth))); ?>">
    <?php echo Lang::_('Go To Month After'); ?> &gt;&gt;
  </a>
</div>
<div id="schoorbs-goto-last-week">
  <a href="<?php echo self::makeInternalUrl('month-view.php', array('month' => date('n', $lastMonth), 'year' => date('Y', $lastMonth))); ?>">
    &lt;&lt; <?php echo Lang::_('Go To Month Before'); ?>
  </a>
</div>

<br />

<!-- 
  Matrix styling:
  - green = 100% free
  - yellow/orange = partly booked
  - red = fully booked
-->
<table class="schoorbs-week-matrix">
<tr>
  <th><?php echo Lang::_('Sunday'); ?></th>
  <th><?php echo Lang::_('Monday'); ?></th>
  <th><?php echo Lang::_('Tuesday'); ?></th>
  <th><?php echo Lang::_('Wednesday'); ?></th>
  <th><?php echo Lang::_('Thursday'); ?></th>
  <th><?php echo Lang::_('Friday'); ?></th>
  <th><?php echo Lang::_('Saturday'); ?></th>
</tr>
<?php
// Get the relevant date information
list($null, $nMonth, $nYear) = input_DayMonthYear();

$nSkipTime = 0;
// We should start with a sunday
while (intval(date('w', $nStartTime - $nSkipTime)) != 0) {
	// Add one day
	$nSkipTime += 24 * 60 * 60;
}

// Use this variable as a helper to check if we need to go into the next row
$nBreakCount = 0;
?>
<tr>
  <?php for (;$nSkipTime > 0; $nSkipTime -= 24 * 60 * 60) { ?>
    <td class="schoorbs-month-matrix-ignored"><a href="#">&nbsp;</a></td>
    <?php $nBreakCount++; ?>
  <?php } ?>
  <?php 
    for ($i = 0; $i < $daysInMonth; $i++) {
      // Check if we need to imply a line break into the table
      if ($nBreakCount == 7) {
        $nBreakCount = 1;
    	echo '</tr><tr>';
      // if not so, just increase the counter.
      } else {
        $nBreakCount++;
      }
    	
      // Display a coloured cell reffering to the booking status of the day
      //
      //  - green = 100% free
      //  - yellow/orange = partly booked
      //  - red = fully booked
      if ($numberOfBookings[$i] == 0) {
        echo '<td class="schoorbs-month-matrix-free">';
      } else if ($numberOfBookings[$i] == $unitsPerDay) {
        echo '<td class="schoorbs-month-matrix-booked">';
      } else {
        echo '<td class="schoorbs-month-matrix-partly">';
      } ?>
        <a href="<?php echo self::makeInternalUrl('day-view.php', array('day' => $i+1, 'month' => $nMonth, 'year' => $nYear)); ?>">&nbsp;</a>
      </td>
      <?php
    }
    // Fill up remaining empty fields
    for(;$nBreakCount < 7; $nBreakCount++) { ?>
      <td class="schoorbs-month-matrix-ignored"><a href="#">&nbsp;</a></td>
   <?php } ?>
</tr>
</table>
