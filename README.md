# CiviSociety
###CiviCRM extension for NDI

This is a prebuilt extension with NDI customizations and modules for CiviCRM. The full user and administrator documentation can be found at https://nditech.org/demtools/civiparty

###Installation Instructions

To install the CiviParty DemTool, you'll need a Linux server, comfort working with LAMP web stacks, some systems administration experience, and time to play around. This tool is built upon Drupal and CiviCRM systems.

You'll need to begin by installing the latest version of Drupal here. Follow the Drupal install instructions for your installation and system config.

After you've successfully installed Drupal, you need to install CiviCRM from the CiviCRM website and follow their guidelines for install and setup. CiviCRM is a web-based contact relationship management tool that integrates with Drupal for content management. Do NOT download or use .module or tarball files from drupal.org - they are placeholder files only. CiviParty currently supports integration with Civi 4.5 and Civi 4.6 and Drupal 7.

Before downloading the CiviParty extensions, create a directory to store these.  We like "sites/default/files/civicrm/extensions".

Next, tell CiviCRM where to find the extensions:
Administer -> System Settings -> Directories
Fill out the "CiviCRM Extensions Directory" setting with the directory you just created
Administer -> System Settings -> Resource URLs
Fill out the "Extension Resource URL" with the directory you just created in the folloing form:
http://yoursite.com/extensionsdirectory (eg: http://www.example.com/sites/default/files/civicrm/extensions)

Next, clone the extensions from the NDI Github repository straight into the extensions directory:
cd sites/default/files/civicrm/extensions
git clone https://github.com/nditech/org.ndi.civiparty-dashboard.git
git clone https://github.com/nditech/org.ndi.civi-local-permissions.git
git clone https://github.com/nditech/org.ndi.civiparty-config.git
git clone https://github.com/nditech/org.ndi.civi-simplifier.git

Then, enable the extensions within CiviCRM.
Administer -> System Settings -> Manage Extensions
