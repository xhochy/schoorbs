# Things to do before releasing #

  * Make sure that there are no open bugs for the upcoming release
  * Make sure all unit-tests run without a problem
  * **_Schoorbs 2.0 and newer:_**
    * Get [latest translations](https://translations.launchpad.net/schoorbs/trunk/+pots/schoorbs)

# Things to do while releasing #

  * Build a zip- and a 7z-archive of Schoorbs -> `rake release`
  * Upload these files to http://static.xhochy.com and https://www.ohloh.net/projects/schoorbs/packages
  * Add a new release and upload the files to https://launchpad.net/schoorbs/trunk/+addrelease
  * Build Debian and Ubuntu Packages of Schoorbs -> `rake debian:binary:fakeroot`
  * Upload those packages to http://static.xhochy.com
  * Announce on http://twitter.com/xhochy
  * Announce on [xhochy.org/en](http://xhochy.org/en)
  * Announce on [xhochy.com](http://xhochy.com)
  * Announce on [Launchpad](https://launchpad.net/schoorbs/+announce)
  * Announce on [XhochY Announce](http://groups.google.com/group/xy-announce)
  * Announce on [Schoorbs general discussion](http://groups.google.com/group/schoorbs-general-discussion)
  * Announce on [the Schoorbs Website](http://schoorbs.xhochy.com)
  * Announce on [Freshmeat](http://freshmeat.net/projects/schoorbs) and add this release
  * Update the [Hotscripts listing](http://www.hotscripts.com/Detailed/78727.html)
  * Check data on [Ohloh](http://www.ohloh.net/projects/12616) (Announcements to this site are made via the Launchpad Announce Feed)