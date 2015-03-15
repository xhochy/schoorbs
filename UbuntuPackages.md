# About #

At the moment we are able to serve packages for Ubuntu 8.04, 7.10 and 7.04.

# Ubuntu 8.04 Hardy Heron #

If you want to get up-to-date Packages add this repository to your `/etc/apt/sources.list` :

```
deb http://static.xhochy.com/ubuntu/ hardy schoorbs
deb-src http://static.xhochy.com/ubuntu/ hardy schoorbs
```

# Ubuntu 7.10 Gutsy Gibbon #

If you want to get up-to-date Packages add this repository to your `/etc/apt/sources.list` :

```
deb http://static.xhochy.com/ubuntu/ gutsy schoorbs
deb-src http://static.xhochy.com/ubuntu/ gutsy schoorbs
```

# Ubuntu 7.04 Feisty Fawn #

If you want to get up-to-date Packages add this repository to your `/etc/apt/sources.list` :

```
deb http://static.xhochy.com/ubuntu/ feisty schoorbs
deb-src http://static.xhochy.com/ubuntu/ feisty schoorbs
```

# Security #

Packages in these repositories can be gpg authenticated. The key that is being used for signing the packages is 129DE187. You can enter this key into the APT trusted keys database with the following command:

```
wget http://static.xhochy.com/keys/xhochy-packagers.asc -O- | sudo apt-key add - 
```