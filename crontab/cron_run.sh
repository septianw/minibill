##############################################
# MiniBill billing and invoicing cron script #
##############################################
# Change the paths here to your php cli and  #
# your minibill installation directory       #
##############################################

# Unix command to figure out where php is (if it is your path)
# You may have to manually set this if it can't find php
PHP=`which php`

# Make sure this is pointing to the right path to where minibill is installed
minibill_path="/home/ultrize/www/minibill/dev"
minibill_path="/var/www/html/minibill/"

# Invoice script also sets due
echo "Running invoice script"
$PHP $minibill_path/crontab/run_invoicing.php $minibill_path

# Runs actual billing
echo "Running billing script"
$PHP $minibill_path/crontab/run_billing.php $minibill_path
