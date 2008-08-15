<h1>
  <em><?php echo get_vocab('viewweek'); ?>:</em>
  <?php echo date('d', $nStartTime).' '.Lang::_(date('F', $nStartTime)).' '.date('Y', $nStartTime); ?>
  &#8594; 
  <?php echo date('d', $nEndTime).' '.Lang::_(date('F', $nEndTime)).' '.date('Y', $nEndTime); ?>
</h1>

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
          <a href="view_entry.php?id=<?php echo ht($entries[$nDay][$sTime]->getId()); ?>">
        <?php } else { ?>
          <td class="schoorbs-week-matrix-free">
          <a href="#">
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

<ul>
  <li>
    <a href="#">Kurzbeschreibung der Buchung</a>
    (12.05.2007 13:00 - 13.05.2007 14:00) 
    <p>
      Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto 
      <a href="#more">...</a>
      <br /><em>Booked by <strong>qwe</strong></em>
    </p>
  </li>
  <li>
    <a href="#">Kurzbeschreibung der Buchung</a>
    (12.05.2007 13:00 - 13.05.2007 14:00) 
    <p>
      Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto
      <a href="#more">...</a>
      <br /><em>Booked by <strong>qwe</strong></em>
    </p>
  </li>
  <li>
    <a href="#">Kurzbeschreibung der Buchung</a>
    (12.05.2007 13:00 - 13.05.2007 14:00) 
    <p>
      Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto
      <a href="#more">...</a>
      <br /><em>Booked by <strong>qwe</strong></em>
    </p>
  </li>
</ul>
