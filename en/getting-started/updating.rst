Updating QuickAppsCMS
#####################

It's always a good practice to keep your QuickAppsCMS installation up to date with
latest available version. QuickAppsCMS has been designed in such a way that your
site's configuration files and assets are isolated from the QuickAppsCMS's core
files. This means, that updating an existing installation is just as easy as
replacing the directory containing such core. This process is exactly the same as
installing a new fresh copy of QuickAppsCMS.

Update using FTP
================

Download the latest version of QuickAppsCMS at the `official repository
<https://github.com/quickapps/cms>`__.

Then extract the .zip package and upload its content to your server using the FTP
client of your choice.

.. warning::

    Make sure to merge folders and not replace them. Most FTP clients will merge the
    folders you’re uploading, but some replace folders instead.

Update using Composer
=====================

If you installed QuickAppsCMS using the Composer way, then you can update using the
following commands:

    ``cd /path/to/site/root/``
    ``composer self-update``
    ``composer update``

Where ``/path/to/site/root/`` is the directory that contains both **vendor/** and
**webroot/** directories of your website.