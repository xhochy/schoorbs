<h3><?php echo Lang::_('About Schoorbs'); ?></h3>
<p>
  <a name="top" href="http://schoorbs.xhochy.com"><?php echo Lang::_('Schoorbs - Room Booking System'); ?></a> - <?php echo $version; ?>
</p>
<h3><?php echo Lang::_('Help'); ?></h3>
<p>
  <?php echo Lang::_('Please contact '); ?>
  <a href="mailto:<?php echo $adminMail; ?>"><?php echo $admin; ?></a> 
  <?php echo Lang::_('for any questions that aren\'t answered here.'); ?>
</p>
<p>
  <strong><?php echo Lang::_('Authentication'); ?></strong>
  <ul>
    <li><a href="#authenticate"><?php echo Lang::_('How do I login?'); ?></a></li>
    <li><a href="#meeting_delete"><?php echo Lang::_('Why can\'t I delete/alter a meeting?'); ?></a></li>
  <ul>  <strong><?php echo Lang::_('Making/Altering Meetings'); ?></strong>
  <ul>
    <li><a href="#repeating"><?php echo Lang::_('How do I make a recurring meeting?'); ?></a></li>
    <li><a href="#repeating_delete"><?php echo Lang::_('How do I delete one instance of a recurring meeting?'); ?></a></li>
    <li><a href="#multiple_sites"><?php echo Lang::_('How do I schedule rooms at different sites?'); ?></a></li>
    <li><a href="#too_many"><?php echo Lang::_('My meeting failed to be created because of <em>too many entries</em>!'); ?></a></li>
    <li><a href="#multiple_users"><?php echo Lang::_('What happens if multiple people schedule the same meeting?'); ?></a></li>
  </ul>
  <strong><?php echo Lang::_('Miscellaneous'); ?></strong>
  <ul>
    <li><a href="#internal_external"><?php echo Lang::_('What is the difference between <em>Internal</em> and <em>External</em>?'); ?></a></li>
  </ul>
  <strong><?php echo Lang::_('About Schoorbs'); ?></strong>
  <ul>
    <li><a href="#how_much"><?php echo Lang::_('How much did the system cost?'); ?></a></li>
    <li><a href="#about"><?php echo Lang::_('How does the system work and who wrote it?'); ?></a></li>
  </ul>
</p>
<hr />

<p>
  <h4><a name="authenticate"><strong><?php echo Lang::_('How do I login?'); ?></strong></a></h4>
  <?php echo Lang::_('The system can be configured to use one of several methods of authentication, including LDAP, Netware, and SMB. See your system administrator if you are having trouble logging in. Some functions are restricted to certain users, and other users will get the message <em>You do not have access rights to modify this item</em>. See your system administrator if this is not working correctly for you. If the system is configured to use LDAP authentication, this means that you login with the same username and password as you use for getting email e.g. <em>Mark Belanger</em> and <em>MyPassword</em>.'); ?>
</p>
<p>
  <a href="#top">Top</a>
</p>
<hr />

<p>
  <h4><a name="meeting_delete"><strong><?php echo Lang::_('Why can\'t I delete/alter a meeting?'); ?></strong></a></h4>  <?php echo Lang::_('In order to delete or alter a meeting, you must be logged in as the same person that made the meeting.  Contact one of the meeting room administrators or the person who initially made the meeting to have it deleted or changed.'); ?>
</p>
<p>
  <a href="#top">Top</a>
</p>
<hr />

<p>
  <h4><a name="repeating"><strong><?php echo Lang::_('How do I make a recurring meeting?'); ?></strong></a></h4>
  <?php echo Lang::_('Clicking on the desired time brings you into the booking screen. Select the appropriate <em>Repeat Type</em>. The room will be scheduled at the same time, until the <em>Repeat End Date</em>, on the days determined by the Repeat Type.'); ?>
  <ul>
    <li><?php echo Lang::_('<em>Täglich</em> bedeutet eine Einplanung für jeden Tag.'); ?></li>
    <li><?php echo Lang::_('A <em>Weekly</em> repeat schedules those days of the week that you check under <em>Repeat Day</em>. For example, use Weekly repeat to schedule the room every Monday, Tuesday, and Thursday; check those days under Repeat Day. If you check no days under Repeat Day, the schedule will repeat on the same day of each week as the first scheduled day.'); ?></li>
    <li><?php echo Lang::_('A <em>Monthly</em> repeat schedules the same day of each month, for example the 15th of the month.'); ?></li>
    <li><?php echo Lang::_('A <em>Yearly</em> repeat schedules the same month and day of the month, for example every March 15th.'); ?></li>
    <li><?php echo Lang::_('Finally, a <em>Monthly, corresponding day</em> repeat schedules one day each month, the same weekday and ordinal position within the month. Use this repeat type to schedule the first Monday, second Tuesday, or fourth Friday of each month, for example. Do not use this repeat type after the 28th day of the month.'); ?></li>
  </ul>
</p>
<p>
  <a href="#top">Top</a>
</p>
<hr />

<p>
  <h4><a name="repeating_delete"><strong><?php echo Lang::_('How do I delete one instance of a recurring meeting?'); ?></strong></a></h4>
  <?php echo Lang::_('Select the day/room/time that you want to delete and select <em>Delete Entry</em>.'); ?>
</p>
<p>
  <a href="#top">Top</a>
</p>
<hr />

<p>
  <h4><a name="multiple_sites"><strong><?php echo Lang::_('How do I schedule rooms at different sites?'); ?></strong></a></h4>
  <?php echo Lang::_('You don\'t.  Presently the system cannot book two different rooms simultaneously. You must schedule each one separately.  Make sure that the time you want is available at both sites before making a booking.'); ?>
</p>
<p>
  <a href="#top">Top</a>
</p>
<hr />

<p>
  <h4><a name="too_many"><strong><?php echo Lang::_('My meeting failed to be created because of <em>too many entries</em>!'); ?></strong></a></h4>
  <?php echo Lang::_('No meeting can create more than 365 entries. There needs to be some limit on the number of meetings created. This number can be increased if necessary.'); ?>
</p>
<p>
  <a href="#top">Top</a>
</p>
<hr />

<p>
  <h4><a name="multiple_users"><strong><?php echo Lang::_('What happens if multiple people schedule the same meeting?'); ?></strong></a></h4>
  <?php echo Lang::_('The short answer is: The first person to click on the <em>Submit</em> button wins. Behind the scenes, the system is using a proper multi-user, multi-threaded relational database than can handle many thousands of simultaneous users.'); ?>
</p>
<p>
  <a href="#top">Top</a>
</p>
<hr />

<p>
  <h4><a name="internal_external"><strong><?php echo Lang::_('What is the difference between <em>Internal</em> and <em>External</em>?'); ?></strong></a></h4>
  <?php echo Lang::_('By default, MRBS defines two meeting types. <em>Internal</em> means that the meeting will only be attended by employees. An <em>External</em> meeting might also be attended by customers, vendors, investors, etc. Your site can define up to a total of 10 meeting types, according to your needs. Meetings are highlighted in the main calendar view with a color corresponding to their type, and a color key of all defined types is shown at the bottom of the main calendar view.'); ?>
</p>
<p>
  <a href="#top">Top</a>
</p>
<hr />

<p>
  <h4><a name="how_much"><strong><?php echo Lang::_('How much did the system cost?'); ?></strong></a></h4>
  <?php echo Lang::_('Nothing.  See the next section for more information.'); ?>
</p>
<p>
  <a href="#top">Top</a>
</p>
<hr />

<p>
  <h4><a name="about"><strong><?php echo Lang::_('How does the system work and who wrote it?'); ?></strong></a></h4>
  <?php echo Lang::_('<a href="http://schoorbs.xhochy.com/">Schoorbs</a> is open source software that is distributed under the Gnu Public License(GPLv2). This means that software is free to use, distribute, and modify.'); ?>
  <br /><br />
  <?php echo Lang::_('The system is written mostly in <a href="http://www.php.net">PHP</a>, which is an open source programming language that can be embedded in web pages similar in concept to Microsoft active server pages.  PHP is especially good at accessing databases.'); ?>
  <br /><br />
  <?php echo Lang::_('The database used for the system is either <a href="http://www.mysql.com/">MySQL</a> or <a href="http://www.postgresql.org/">PostgreSQL</a>. MySQL is a very fast, multi-threaded, multi-user and robust SQL (Structured Query Language) database server that is also GPL. PostgreSQL is a full-featured multi-user open source Object Relational SQL database server.'); ?>
  <br /><br />
  <?php echo Lang::_('The system will run on multiple platforms, including the PC architecture using the <a href="http://www.linux.com/">Linux</a> operating system. Linux, is a free, open source, unix-like operating system.'); ?>
  <br /><br />
  <?php echo Lang::_('The web server being used is yet another piece of free, open source software.  The <a href="http://www.apache.org/">Apache</a> web server is the world\'s most popular web server.'); ?>
  <br /><br />
  <?php echo Lang::_('The bottom line is: <strong>every piece of this system, from the operating system to the application, is completely free - source code and all</strong>.'); ?></p>
<p>
  <a href="#top">Top</a>
</p>
