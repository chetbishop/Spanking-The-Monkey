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
sudo apt-get dist-upgrade
reboot
```

####Install LAMP stack
The Linux, Apache, MySQL, PHP (LAMP) stack is a popular open source web platform commonly used to run dynamic web sites and servers.  Since the stack is so popular, we decided to pick on it.

On Ubuntu the LAMP stack can be installed either separately or with the special package lamp-server

```
sudo apt-get install lamp-server^
```

(Mind the caret (^) at the end)

The install process for mysql-server will prompt you to enter a password for the “root” user of the database.  This will be the master administrator password and should be different then the password you use for your user account.  

####Prepare the Database
The www.angry_monkey.com website has its own unique database inside of MySQL.  We will need to create the database and a user to interact with the database.
```
sudo mysql -p
```
Enter password from install
```
create database web;
grant select, insert, update, delete, create, drop, index, alter, create temporary tables, lock tables on web.* to 'luser'@'localhost' identified by 's3cr3t1smIsTh3K3y';
```

####Setup website
Now comes the fun part.  Installing the www.angry_monkey.com website.  To do this we will clone the git repo and move the files around. 

First we need to make sure that git is installed
```
sudo apt-get install git -y
```

Next pick a directory and clone the repo.  
```
cd ~/Desktop
git clone https://github.com/chetbishop/Spanking-The-Monkey.git
```

Populate the database
```
cd Spanking-The-Monkey/WebSite
gunzip < Database/web.gz | sudo mysql -p web
```

Copy the Apache2 config file
```
sudo cp Apache\ Config/default /etc/apache2/sites-available/000-default.conf
```

Copy the website content
```
sudo cp -r Content/vulnsite/ /var/www/
```

Restart Apache
```
sudo service apache2 restart
```

####Update the /etc/hosts file
Since we are running the website locally, and not using a DNS server, we need to tell Ubuntu how to reach www.angry_monkey.com

Open the /etc/hosts file with your favorite text editor
```
sudo vi /etc/hosts
```

Add the following lines to the bottom of the file
```
#www.angry_monkey vulnerable site
127.0.0.1 www.angry_monkey.com
```

####Go Have Fun
You should now be able to open your web browser and go to www.angry_monkey.com
