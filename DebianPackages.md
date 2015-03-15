# About #

At the moment there are only packages available for Debian 4.0 "Etch". Due to the portability of deb-Packages, in future, there will be packages for several Debian versions and Debian derivates.

# Debian 4.0 Etch #

If you want to get up-to-date Packages add this repository to your `/etc/apt/sources.list` :

```
deb http://static.xhochy.com/debian/ etch schoorbs
deb-src http://static.xhochy.com/debian/ etch schoorbs
```

Packages in this repository can be gpg authenticated. The key that is being used for signing the packages is 129DE187. You can enter this key into the APT trusted keys database with the following command:

```
wget http://static.xhochy.com/keys/xhochy-packagers.asc -O- | sudo apt-key add - 
```