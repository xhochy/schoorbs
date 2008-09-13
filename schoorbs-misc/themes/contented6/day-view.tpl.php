<h1>
  <?php echo $room->getArea()->getName(); ?> &#8594; <?php echo $room->getName(); ?>
</h1>
<h3>
  <em><?php echo Lang::_('View Day'); ?>:</em>
  <?php echo date('d', $nStartTime).' '.Lang::_(date('F', $nStartTime)).' '.date('Y', $nStartTime); ?>
</h3>

<div id="schoorbs-goto-next-week">
  <a href="<?php echo self::makeInternalUrl('day-view.php', array('day' => $nextDay[0], 'month' => $nextDay[1], 'year' => $nextDay[2])); ?>">
    <?php echo Lang::_('Go To Day After'); ?> &gt;&gt;
  </a>
</div>
<div id="schoorbs-goto-last-week">
  <a href="<?php echo self::makeInternalUrl('day-view.php', array('day' => $lastDay[0], 'month' => $lastDay[1], 'year' => $lastDay[2])); ?>">
    &lt;&lt; <?php echo Lang::_('Go To Day Before'); ?>
  </a>
</div>

<table class="schoorbs-day-matrix">
<tr>
  <th><?php echo Lang::_('Time'); ?></th>
  <th><?php echo Lang::_('Name'); ?></th>
</tr>
<?php foreach($entries as $sTime=>$mEntry) { ?>
  <tr>
    <td class="schoorbs-day-matrix-time"><?php echo $sTime; ?></td>
      <?php if ($mEntry !== -1) { ?>
        <td class="schoorbs-day-matrix-booked">
          <a href="view-entry.php?id=<?php echo $mEntry->getId(); ?>">
            <?php echo $mEntry->getName(); ?>
      <?php } else { ?>
        <td class="schoorbs-day-matrix-free">
          <a href="edit_entry.php?room=<?php echo $room->getId(); 
            ?>&amp;day=<?php echo date('d', $entryTime[$sTime]); 
            ?>&amp;month=<?php echo date('n', $entryTime[$sTime]); 
            ?>&amp;year=<?php echo date('Y', $entryTime[$sTime]);
            if (Entry::perioded()) {
            	echo '&amp;period='.preg_replace('/^0/', '', date('i', $entryTime[$sTime]));
            } else {
            	echo '&amp;hour='.date('H', $entryTime[$sTime]).'&amp;minute='.date('i', $entryTime[$sTime]);
            }
            ?>">
      <?php } ?>
      &nbsp;</a>
    </td>
  </tr>
<?php } ?>
</table>
