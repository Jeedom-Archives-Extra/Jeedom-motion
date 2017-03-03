#!/bin/bash
INSTALL_DIR=/usr/local/bin
TEMP_DIR=`mktemp -d /tmp/motion.XXXXXX`
motion_url="http://downloads.sourceforge.net/project/motion/motion%20-%203.2/3.2.12/motion-3.2.12.tar.gz?r=&ts=1446122652&use_mirror=skylink"
touch /tmp/compilation_motion_in_progress
echo 0 > /tmp/compilation_motion_in_progress
check_run()  {
    "$@"
    local status=$?
    if [ $status -ne 0 ]; then
        echo "error with $1" >&2
	exit
    fi
    return $status
}
if [ -f "/etc/motion/" ]
then
	echo "*****************************************************************************************************"
	echo "*                                Desinstallation des dépendance                                    *"
	echo "*****************************************************************************************************"
	sudo apt-get autoremove -y --force-yes  motion
	rm -R /etc/motion/
fi
echo "*****************************************************************************************************"
echo "*                                          Installation de FFMPEG                                   *"
echo "*****************************************************************************************************"
test=$(grep '#http://www.deb-multimedia.org' /etc/apt/sources.list)
if [ -z "$test" ] || [ $test = " " ] || [ $test = "" ]
then 
	echo "#http://www.deb-multimedia.org" | sudo tee -a /etc/apt/sources.list
	echo "deb http://www.deb-multimedia.org jessie main non-free" | sudo tee -a /etc/apt/sources.list
	echo "deb-src http://www.deb-multimedia.org jessie main non-free" | sudo tee -a /etc/apt/sources.list
fi 
echo 10 > /tmp/compilation_motion_in_progress
sudo apt-get -y update
echo 20 > /tmp/compilation_motion_in_progress
sudo apt-get -y upgrade
echo 30 > /tmp/compilation_motion_in_progress
sudo apt-get install -y --force-yes  deb-multimedia-keyring
echo 40 > /tmp/compilation_motion_in_progress
sudo apt-get -y update
echo 50 > /tmp/compilation_motion_in_progress
sudo apt-get remove -y --force-yes ffmpeg
echo 60 > /tmp/compilation_motion_in_progress
sudo apt-get install -y --force-yes x264
echo 70 > /tmp/compilation_motion_in_progress
echo "*****************************************************************************************************"
echo "*                                          Compilation de motion:                                   *"
echo "*****************************************************************************************************"
sudo apt-get install -y --force-yes motion
echo 90 > /tmp/compilation_motion_in_progress
sudo chmod -R 777 /etc/motion/
echo 95 > /tmp/compilation_motion_in_progress
sudo usermod -a -G motion www-data
echo 100 > /tmp/compilation_motion_in_progress
echo "*****************************************************************************************************"
echo "*                                Installation de motion terminé                                     *"
echo "*****************************************************************************************************"
rm /tmp/compilation_motion_in_progress
