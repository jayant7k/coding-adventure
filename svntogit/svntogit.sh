#!/usr/bin/php
<?php
#
#/*
# *	Script to port delta changes from svn to git
# *	Both svn and git path to be provided
# *	git path should contain "svnversion.txt" file having the last revision no of svn till when the changes were ported to git
# */
#

$svn_path="/home/jayant/temp/svn";
$git_path="/home/jayant/temp/git/3rdparty";

echo 'Migrating changes from'.PHP_EOL.'  svn:['.$svn_path.'] '.PHP_EOL.'to '.PHP_EOL.'  git:['.$git_path.']'.PHP_EOL;

$git_ver_file=$git_path.'/svnversion.txt';

if(!file_exists($git_ver_file))
	die('==> Unable to locate svn version file in git repo');

$cmd_svnversion = 'svnversion '.$svn_path;

$old_svn_revno = intval(file_get_contents($git_ver_file));
echo 'Running : '.$cmd_svnversion.PHP_EOL;
$new_svn_revno = intval(shell_exec($cmd_svnversion));

if(is_nan($new_svn_revno))
	die('unable to get svn version of svn repo ==> '.$cmd_svnversion.PHP_EOL);

if($old_svn_revno >= $new_svn_revno)
	die('no porting required as old svn no['.$old_svn_revno.'] >= new svn no['.$new_svn_revno.']'.PHP_EOL);

$cmd_takediff = 'cd '.$svn_path.'; svn diff -r '.$old_svn_revno.':'.$new_svn_revno.' > /tmp/svn_patch.diff';
$cmd_applygit = 'cd '.$git_path.'; patch -p0 < /tmp/svn_patch.diff';

echo 'Running : '.$cmd_takediff.PHP_EOL;
$op_takediff = shell_exec($cmd_takediff);
echo '###########'.PHP_EOL.$op_takediff.PHP_EOL.'###########';
echo 'Running : '.$cmd_applygit.PHP_EOL;
$op_applygit = shell_exec($cmd_applygit);
echo '###########'.PHP_EOL.$op_applygit.PHP_EOL.'###########';

echo 'Updating svn version in git repo to '.$new_svn_revno.PHP_EOL;
file_put_contents($git_ver_file, $new_svn_revno);
?>