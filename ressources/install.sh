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
sudo apt-get install -y --force-yes autoconf
sudo apt-get install -y --force-yes automake
sudo apt-get install -y --force-yes pkgconf
sudo apt-get install -y --force-yes libtool
sudo apt-get install -y --force-yes libjpeg8-dev
sudo apt-get install -y --force-yes build-essential
sudo apt-get install -y --force-yes libzip-dev
echo "*****************************************************************************************************"
echo "*                                          Installation de FFMPEG                                   *"
echo "*****************************************************************************************************"
if [ -d "/usr/local/src/ffmpeg/" ]; then 
  sudo rm -R /usr/local/src/ffmpeg/
fi
sudo mkdir /usr/local/src/ffmpeg/
cd /usr/local/src/ffmpeg/
git clone https://github.com/FFmpeg/ffmpeg.git
cd ffmpeg
./configure
make -j3
sudo make install
cd ../motion/
PKG_CONFIG_PATH=/etc/ffmpeg/lib/pkgconfig cmake .
make
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
