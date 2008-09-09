<div class="vevent">
  <h3 class="summary"><?php echo ht($entry->getName()); ?></h3>
   <table border="0" class="vevent">
     <tr>
       <td><strong><?php echo get_vocab('description'); ?></strong></td>
       <td><span class="description"><?php echo nl2br($entry->getDescription()); ?></span></td>
     </tr>
     <tr>
       <td><strong><?php echo get_vocab('room'); ?>:</strong></td>
       <td>
         <span class="location">
           <?php echo $entry->getRoom()->getArea()->getName(); ?> - <?php echo $entry->getRoom()->getName(); ?>
         </span>
       </td>
     </tr>
     <tr>
       <td><strong><?php echo get_vocab('start_date'); ?></strong></td>
       <td>
         <abbr class="dtstart" title="<?php if (Entry::perioded()) { echo strftime("%Y-%m-%d", $entry->getStartTime()); } else { echo strftime("%Y-%m-%dT%H:%M:00", $entry->getStartTime()); } ?>">
           <?php 
           if (Entry::perioded()) {
           	echo date('Y-m-d', $entry->getStartTime());
           	echo ' '.$entry->getStartPeriodString();
           } else {
           	echo date('Y-m-d H:i', $entry->getStartTime());
           }
           ?>
         </abbr>
       </td>
     </tr>
     <tr>
       <td><strong><?php echo get_vocab('duration'); ?></strong></td>
       <td><span class="duration"><?php echo $entry->getDurationString(); ?></span></td>
     </tr>
     <tr>
       <td><strong><?php echo get_vocab('end_date'); ?></strong></td>
       <td>
         <abbr class="dtend" title="<?php if (Entry::perioded()) { echo strftime("%Y-%m-%d", $entry->getEndTime()); } else { echo strftime("%Y-%m-%dT%H:%M:00", $entry->getEndTime()); } ?>">
           <?php 
           if (Entry::perioded()) {
           	echo date('Y-m-d', $entry->getEndTime());
           	echo ' '.$entry->getEndPeriodString();
           } else {
           	echo date('Y-m-d H:i', $entry->getEndTime());
           }
           ?>
         </abbr>
       </td>
     </tr>
     <tr>
       <td><strong><?php echo get_vocab('type'); ?></strong></td>
       <td><span class="class"><?php echo $entry->getTypeLong(); ?></span></td>
     </tr>
     <tr>
       <td><strong><?php echo get_vocab('createdby'); ?></strong></td>
       <td><span class="uid"><?php echo $entry->getCreateBy(); ?></span></td>
     </tr>
     <tr>
       <td><strong><?php echo get_vocab('lastupdate'); ?></strong></td>
       <td><span class="dtstamp" title="<?php echo strftime("%Y-%m-%dT%H:%M:00", $entry->getTimestamp()); ?>"><?php echo date('Y-m-d H:i:s', $entry->getTimestamp()); ?></span></td>
     </tr>
     <tr>
       <td><strong><?php echo get_vocab('rep_type'); ?></strong></td>
       <td><?php echo $entry->getRepetitionString(); ?></td>
     </tr>
     <?php if ($entry->isRepeated()) { ?>
       <tr>
         <td><strong><?php echo Lang::_('Repeat End Date:'); ?></strong></td>
         <td>
           <?php echo date('Y-m-d', $entry->getRepetition()->getEndDate()); ?>
           <?php  
           if (Entry::perioded()) {
             echo Entry::formatTimePeriodString($entry->getRepetition()->getEndDate());
           } else {
             echo date('H:i', $entry->getRepetition()->getEndDate());
           }
           ?>
         </td>
       </tr>
       <?php if ($entry->getRepetition()->getRepType() == 6) { ?>
         <tr>
           <td><strong><?php echo Lang::_('Number of weeks').Lang::_('(for (n-)weekly)'); ?></strong></td>
           <td><?php echo $entry->getRepetiton->getRepNumWeeks(); ?></td>
         </tr>
       <?php } ?>
       <?php if (($entry->getRepetition()->getRepType() == 6) || ($entry->getRepetition()->getRepType() == 2)) { ?>
         <tr>
           <td><strong><?php echo Lang::_('Repeat Day:'); ?></strong></td>
           <td><?php echo $entry->getRepetition()->getRepeatDayString(); ?></td>
         </tr>
       <?php } ?>
     <?php } ?>
  </table>
  <br />
</div>

<a href="edit_entry.php?id=<?php echo $entry->getId(); ?>">
  <?php echo Lang::_('Edit Entry'); ?>
</a>
<?php if ($entry->isRepeated()) { ?>
  &nbsp;-&nbsp;
  <a href="edit_entry.php?id=<?php echo $entry->getId(); ?>&amp;edit_type=series"><?php echo Lang::_('Edit Series'); ?></a>
<?php } ?>
<br />

<a href="del_entry.php?id=<?php echo $entry->getId(); ?>&amp;series=0" onclick="return confirm('<?php echo Lang::_('Are you sure\\nyou want to\\ndelete this entry?\\n\\n'); ?>');">
  <?php echo Lang::_('Delete Entry'); ?>
</a>
<?php if ($entry->isRepeated()) { ?>
  &nbsp;-&nbsp;
  <a href="del_entry.php?id=<?php echo $entry->getId(); ?>&amp;series=1" onclick="return confirm('<?php echo Lang::_('Are you sure\\nyou want to\\ndelete this entry?\\n\\n'); ?>');">
    <?php echo Lang::_('Delete Series'); ?>
  </a>
<?php } ?>
