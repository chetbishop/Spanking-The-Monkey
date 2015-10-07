#Spanking The Monkey (or how pentesters can do it better!)

In today’s mainstream penetration testing and Red Team environments we feel that the teams are relying too much on noisy scanners. In part making for a large group of scanner monkeys. This talk is bringing back old school ways with a new flavor, a new flair to prove that those techniques still work in the majority of engagements that many of us are involved in. The problem with many tools is not only how noisy they can be, but also the amount of logic and decision making that goes on under the hood without any human input. 

*****
##Index
* Recorded Talks
* Follow Along
* Resources

*****
###Recorded Talks
####SecKC May 2015 (Kansas City, Kansas)

<a href="http://www.youtube.com/watch?feature=player_embedded&v=_d6E__jmYdA" target="_blank"><img src="http://img.youtube.com/vi/_d6E__jmYdA/0.jpg" 
alt=“SecKC” width=“560” height=“315” border=“0” /></a> 

####DerbyCon 5.0 (Louisville, Kentucky)

<a href="http://www.youtube.com/watch?feature=player_embedded&v=sQTiLD32U70" target="_blank"><img src="http://img.youtube.com/vi/sQTiLD32U70/0.jpg" 
alt=“SecKC” width=“560” height=“315” border=“0” /></a> 

*****
###Follow Along
Follow along with us or play around with the website.  This section will describe how to get the vulnerable website up and running on your own virtual machine.  

The configuration directions are for Ubuntu Desktop 14.04.3, but any Linux system will work.

####Install Ubuntu as a Virtual Machine

We’ll leave this part up to you.  Plenty of guides exist on the internet.  To help get you started some links are below:

* [Ubuntu Desktop] (http://www.ubuntu.com/download/desktop)
* [Virtual Box] (https://www.virtualbox.org/wiki/Downloads)
* [VMware Player] (https://www.vmware.com/products/player)

####Install Latest Updates
After getting Ubuntu installed, we will want to ensure that it is up-to-date
```
sudo apt-get update
sudo apt-get upgrade
sudo apt-get dost-upgrade
reboot
```

####Install LAMP stack
The Linux, Apache, MySQL, PHP (LAMP) stack is a popular open source web platform commonly used to run dynamic web sites and servers.  Since the stack is so popular, we decided to pick on it.

#####Install Apache2
The A in LAMP
```
sudo apt-get install apache2-mpm-prefork apache2-utils libapache2-mod-dnssd -y
sudo a2enmod rewrite
```####Install PHP
The P in LAMP
```
sudo apt-get install libapache2-mod-php5 -y
sudo a2enmod php5
```

####Install MySQL
The M in LAMP.  This is also the most complicated install we will be doing.
```
sudo apt-get install mysql-server libapache2-mod-auth-mysql php5-mysql -y```

The install process for mysql-server will prompt you to enter a password for the “root” user of the database.  This will be the master administrator password and should be different then the password you use for your user account.  

####Prepare the Database
The www.angry_monkey.com website has its own unique database inside of MySQL.  We will need to create the database and a user to interact with the database.
```
sudo mysql -p
create database web;grant select, insert, update, delete, create, drop, index, alter, create temporary tables, lock tables on web.* to ‘luser’@’localhost’ identified by ‘s3cr3t1smIsTh3K3y’;```