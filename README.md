# CiviSociety
###CiviCRM extension for NDI

This is a prebuilt extension with NDI customizations and modules for CiviCRM. The full user and administrator documentation can be found at https://nditech.org/demtools/civiparty

###Installation Instructions

To install the CiviParty DemTool, you'll need a Linux server, comfort working with LAMP web stacks, some systems administration experience, and time to play around. This tool is built upon Drupal and CiviCRM systems. After you've successfully installed Drupal, you need to install CiviCRM from the CiviCRM website. CiviCRM is a web-based contact relationship management tool that integrates with Drupal for content management. Do NOT download or use .module or tarball files from drupal.org - they are placeholder files only. CiviParty currently supports integration with Civi 4.5 and Civi 4.6 and Drupal 7.

1. Get the root privileges.

    sudo su

2. The restart option is a shorthand way of stopping and then starting the Apache HTTP Server.

    service httpd restart

3. Configure the /etc/httpd/conf/httpd.conf file. The httpd.conf file is the main configuration file for the Apache web server.

    vim /etc/httpd/conf/httpd.conf

    Here, change document root to :  /var/www/html/website_name

    Eg: /var/www/html/civiukraine

    Add line at the bottom:

    <Directory "/var/www/html/civiukraine" >

            AllowOverride All

        		</Directory>

4. We need to create a file named after our website, as per the document root previously modified.

		mkdir /var/www/html/civiukraine

5. The restart option is a shorthand way of stopping and then starting the Apache HTTP Server.

		service httpd restart

6. Now, change the current working directory to the file we just created. We will install drupal here.

    cd /var/www/html/civiukraine/

7. Download the latest version of drupal using drupal shell (drush). Drush is a shell interface for managing Drupal right from your cloud server command line. It is a very useful tool as it helps you perform various admin tasks using just one or two commands.

    drush dl drupal-7.39

8. Notice that we now have a drupal-7.39 directory with all of the Drupal files. We want the Drupal files to be in our document root, not in a 'drupal-7.39' subdirectory. So, we'll move the contents of the directory up one level.

    mv drupal-7.39/* ./

    mv drupal-7.39/.htaccess ./

9. List all the existing files. Remove all files and subdirectories. 

    ls -lah drupal-7.39

    rm -rf drupal-7.39

10. Invoke MySQL. It will ask you for your password. Enter the password.

    mysql -u root -p 

11. We create the files and drop all the tables in the civiukraineDrupal database. Drupal installation will be complete with: username=admin and password=admin.

    drush site-install standard --account-name=admin--account-pass=admin--db-url=mysql://root:YFL4WI7gLHogS41shk2s@localhost/civiukraineDrupal

12. The restart option is a shorthand way of stopping and then starting the Apache HTTP Server.

    service httpd restart

13. Create settings.php in default folder.

    vim /sites/default/settings.php

14. Enter into the newly created directory named "modules."

    cd sites/all/modules/

15. Create a new CiviCRM module for Drupal. Go to the latest release of CiviCRM. Copy the link online. 

    wget https://download.civicrm.org/civicrm-4.6.8-drupal.tar.gz

16. Uncompress the downloaded folder.

    tar -zxvf ‘name of the file downloaded’

17. The installer will verify that you've downloaded the correct version of CiviCRM, and will check your server environment to make sure it meets CiviCRM requirements. It will then create and populate a database for CiviCRM as well as create your CiviCRM settings file (civicrm.settings.php). Login to your Drupal site with Administrator level permissions. Point your web browser to the following URL.

    http://<your_drupal_home>/sites/all/modules/civicrm/install/index.php

18. Fill in the CiviCRM Database Settings. Fill in the Drupal Database Settings for your existing Drupal database. Select the appropriate language for the base installation. You will be able to add other languages after the installation for multilingual sites. 

Back in terminal, change what? to the parent’s parent directory.

    ls -lah /var/www/html/civiukraine/sites/default/

    cd ../ ../

18. Allow everyone to read and execute the file, and make sure the file owner is allowed to write to the file as well.
 
 		chmod -Rv 755 /var/www/html/civiukraine/sites/default/files/

19. Change directory to html present inside www.

		cd /var/www/html

20. List all the existing files. Remove all files and subdirectories.

		ls -lah

		rm -rf drupal

21. Allow everyone to read and execute the file, and make sure the file owner is allowed to write to the file as well. Use the chown command to change file owner and group information. Use the chmod command to change file access permissions such as read, write, and access.

		chown -Rv apache:apache civiukraine/

		chmod -Rv 755 civiukraine/sites/default/settings.php


22. Back in the website, click the Check Requirements button. All the requirements should turn green. Click on the Install CiviCRM button.

23. Go to your website. CiviCRM has been successfully installed. 

24. Next, tell CiviCRM where to find the extensions:

		Administer -> System Settings -> Directories

25. Fill out the "CiviCRM Extensions Directory" setting with the directory you just created

		Administer -> System Settings -> Resource URLs

26. Fill out the "Extension Resource URL" with the directory you just created in the following: 

		http://yoursite.com/extensionsdirectory 
		(eg: http://www.example.com/sites/default/files/civicrm/extensions)

27. Next, clone the extensions from the NDI Github repository straight into the extensions directory:

		cd sites/default/files/civicrm/extensions

		git clone https://github.com/nditech/org.ndi.civiparty-dashboard.git
		
		git clone https://github.com/nditech/org.ndi.civi-local-permissions.git

		git clone https://github.com/nditech/org.ndi.civiparty-config.git

		git clone https://github.com/nditech/org.ndi.civi-simplifier.git

28. Then, enable the extensions within CiviCRM.

		Administer -> System Settings -> Manage Extensions






















