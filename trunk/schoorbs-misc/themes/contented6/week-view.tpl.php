<h1>
  <?php echo $room->getArea()->getName(); ?> &#8594; <?php echo $room->getName(); ?>
</h1>
<h3>
  <em><?php echo Lang::_('View Week'); ?>:</em>
  <?php echo date('d', $nStartTime).' '.Lang::_(date('F', $nStartTime)).' '.date('Y', $nStartTime); ?>
  &#8594; 
  <?php echo date('d', $nEndTime).' '.Lang::_(date('F', $nEndTime)).' '.date('Y', $nEndTime); ?>
</h3>

<div id="schoorbs-goto-next-week">
  <a href="<?php echo self::makeInternalUrl('week-view.php', array('day' => $nextWeek[0], 'month' => $nextWeek[1], 'year' => $nextWeek[2])); ?>">
    <?php echo get_vocab('weekafter'); ?> &gt;&gt;
  </a>
</div>
<div id="schoorbs-goto-last-week">
  <a href="<?php echo self::makeInternalUrl('week-view.php', array('day' => $lastWeek[0], 'month' => $lastWeek[1], 'year' => $lastWeek[2])); ?>">
    &lt;&lt; <?php echo get_vocab('weekbefore'); ?>
  </a>
</div>

<br />

<table class="schoorbs-week-matrix">
<tr>
  <th><?php echo Lang::_('Time'); ?></th>
  <th><?php echo Lang::_('Sun'); ?></th>
  <th><?php echo Lang::_('Mon'); ?></th>
  <th><?php echo Lang::_('Tue'); ?></th>
  <th><?php echo Lang::_('Wed'); ?></th>
  <th><?php echo Lang::_('Thu'); ?></th>
  <th><?php echo Lang::_('Fri'); ?></th>
  <th><?php echo Lang::_('Sat'); ?></th>
</tr>
<?php 
foreach($entries as $sDay=>$aEntries) {
  foreach($aEntries as $sTime=>$null) { ?>
    <tr>
      <td class="schoorbs-week-matrix-time"><?php echo $sTime; ?></td>
      <?php for ($nDay = 0; $nDay < 7; $nDay++) { ?>
        <?php if ($entries[$nDay][$sTime] !== -1) { ?>
          <td class="schoorbs-week-matrix-booked">
          <a href="view-entry.php?id=<?php echo $entries[$nDay][$sTime]->getId(); ?>">
        <?php } else { ?>
          <td class="schoorbs-week-matrix-free">
          <a href="edit_entry.php?room=<?php echo $room->getId(); 
            ?>&amp;day=<?php echo date('d', $entryTime[$nDay][$sTime]); 
            ?>&amp;month=<?php echo date('n', $entryTime[$nDay][$sTime]); 
            ?>&amp;year=<?php echo date('Y', $entryTime[$nDay][$sTime]);
            if (Entry::perioded()) {
            	echo '&amp;period='.preg_replace('/^0/', '', date('i', $entryTime[$nDay][$sTime]));
            } else {
            	echo '&amp;hour='.date('H', $entryTime[$nDay][$sTime]).'&amp;minute='.date('i', $entryTime[$nDay][$sTime]);
            }
            ?>">
        <?php } ?>
        &nbsp;</a>
        </td>
      <?php } ?>
    </tr>
<?php 
  }
  break;
} 
?>
</table>

<br />

<ul>
  <?php foreach($uniqueEntries as $oEntry) { ?>
    <li>
      <a href="view-entry.php?id=<?php echo $oEntry->getId(); ?>">
        <?php echo $oEntry->getName(); ?>
      </a>
      (<?php echo date('d', $oEntry->getStartTime()).' '
                  .Lang::_(date('F', $oEntry->getStartTime())).' '
                  .date('Y H:i', $oEntry->getStartTime()); ?>
      -
      <?php echo date('d', $oEntry->getEndTime()).' '
                  .Lang::_(date('F', $oEntry->getEndTime())).' '
                  .date('Y H:i', $oEntry->getEndTime()); ?>) 
      <p>
      	<?php $aOut = str_split($oEntry->getDescription(), 100); echo ht($aOut[0]); ?>
        <a href="view-entry.php?id=<?php echo $oEntry->getId(); ?>">...</a>
        <br /><em><?php echo Lang::_('Booked by'); ?> <strong><?php echo ht($oEntry->getCreateBy()); ?></strong></em>
      </p>
    </li>
  <?php } ?>
</ul>
