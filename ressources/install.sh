#!/bin/bash
touch /tmp/compilation_motion_in_progress
echo 0 > /tmp/compilation_motion_in_progress
if [ -d "/etc/motion/" ]
then
	echo "*****************************************************************************************************"
	echo "*                                Desinstallation des dépendance                                    *"
	echo "*****************************************************************************************************"
	sudo apt-get autoremove -y --force-yes  motion
	sudo apt-get autoremove -y --force-yes  ffmpeg
	sudo apt-get autoremove -y --force-yes  x264
	#rm -R /etc/motion/
fi
echo "*****************************************************************************************************"
echo "*                                   Installation des dépendance                                     *"
echo "*****************************************************************************************************"
sudo apt-get install -y --force-yes autoconf automake libtool
sudo apt-get install -y --force-yes pkg-config
sudo apt-get install -y --force-yes libjpeg62-turbo-dev
sudo apt-get install -y --force-yes zlib1g-dev
sudo apt-get install -y --force-yes git
sudo apt-get install -y --force-yes git-core
sudo apt-get install -y --force-yes cmake
sudo apt-get install -y --force-yes liblog4cplus-dev 
sudo apt-get install -y --force-yes libcurl3-dev 
sudo apt-get install -y --force-yes build-essential
sudo apt-get install -y --force-yes libjasper-dev
sudo apt-get install -y --force-yes libgtk2.0-dev
sudo apt-get install -y --force-yes libavcodec-dev libavformat-dev libswscale-dev libv4l-dev
sudo apt-get install -y --force-yes libzip-dev
sudo apt-get install -y --force-yes libavcodec56 
sudo apt-get install -y --force-yes libavdevice56
sudo apt-get install -y --force-yes libavfilter5
sudo apt-get install -y --force-yes libavformat56
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
sudo apt-get update -y
echo 30 > /tmp/compilation_motion_in_progress
sudo apt-get install -y --force-yes  deb-multimedia-keyring
echo 40 > /tmp/compilation_motion_in_progress
sudo apt-get -y update
echo 50 > /tmp/compilation_motion_in_progress
sudo apt-get install -y --force-yes ffmpeg
sudo apt-get install -y --force-yes v4l-utils
echo 60 > /tmp/compilation_motion_in_progress
sudo apt-get install -y --force-yes x264
echo 70 > /tmp/compilation_motion_in_progress
sudo apt-get install -y --force-yes libavutil-dev libavformat-dev libavcodec-dev libswscale-dev libavdevice-dev
echo "*****************************************************************************************************"
echo "*                                          Compilation de motion:                                   *"
echo "*****************************************************************************************************"
if [ -d "/usr/local/src/motion/" ]; then 
  sudo rm -R /usr/local/src/motion/
fi
sudo mkdir /usr/local/src/motion/
cd /usr/local/src/motion
git clone https://github.com/Motion-Project/motion.git
cd motion
sudo autoreconf -fiv
./configure
make
sudo make install
echo 90 > /tmp/compilation_motion_in_progress
sudo chmod -R 777 /etc/motion/
echo 95 > /tmp/compilation_motion_in_progress
sudo usermod -a -G motion www-data
echo 100 > /tmp/compilation_motion_in_progress
echo "*****************************************************************************************************"
echo "*                                Installation de motion terminé                                     *"
echo "*****************************************************************************************************"
rm /tmp/compilation_motion_in_progress
