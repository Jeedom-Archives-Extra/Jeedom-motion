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
git clone https://github.com/FFmpeg/FFmpeg.git ffmpeg
cd ffmpeg
./configure --prefix=/etc/ffmpeg
make -j3
sudo make install
cd ../motion/
PKG_CONFIG_PATH=/etc/ffmpeg/lib/pkgconfig cmake .
make
echo "*****************************************************************************************************"
echo "*                                          Compilation de motion:                                   *"
echo "*****************************************************************************************************"
git clone https://github.com/Motion-Project/motion.git motion
cd motion
sudo autoreconf -fiv
./configure --prefix=/etc/motion --with-ffmpeg=/etc/ffmpeg
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
