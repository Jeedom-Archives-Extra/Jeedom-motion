#!/bin/bash
touch /tmp/compilation_motion_in_progress
echo 0 > /tmp/compilation_motion_in_progress
if [ -d "/etc/motion/" ]
then
	echo "*****************************************************************************************************"
	echo "*                                Desinstallation des dépendance                                    *"
	echo "*****************************************************************************************************"
	sudo apt-get autoremove -y motion
	sudo apt-get autoremove -y ffmpeg
	sudo apt-get autoremove -y x264
	#rm -R /etc/motion/
fi
echo "*****************************************************************************************************"
echo "*                                   Installation des dépendance                                     *"
echo "*****************************************************************************************************"
sudo apt-get install -y -f autoconf automake libtool
sudo apt-get install -y -f pkg-config
sudo apt-get install -y -f libjpeg62-turbo-dev
sudo apt-get install -y -f zlib1g-dev
sudo apt-get install -y -f git
sudo apt-get install -y -f git-core
sudo apt-get install -y -f cmake
sudo apt-get install -y -f liblog4cplus-dev 
sudo apt-get install -y -f libcurl3-dev 
sudo apt-get install -y -f build-essential
sudo apt-get install -y -f libjasper-dev
sudo apt-get install -y -f libgtk2.0-dev
sudo apt-get install -y -f libavcodec-dev libavformat-dev libswscale-dev libv4l-dev
sudo apt-get install -y -f libzip-dev
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
sudo apt-get install -y -f  deb-multimedia-keyring
echo 40 > /tmp/compilation_motion_in_progress
sudo apt-get -y update
echo 50 > /tmp/compilation_motion_in_progress
sudo apt-get install -y -f ffmpeg
sudo apt-get install -y -f v4l-utils
echo 60 > /tmp/compilation_motion_in_progress
sudo apt-get install -y -f x264
echo 70 > /tmp/compilation_motion_in_progress
sudo apt-get install -y -f libavutil-dev libavformat-dev libavcodec-dev libswscale-dev libavdevice-dev
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
