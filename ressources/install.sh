#!/bin/bash
touch /tmp/compilation_motion_in_progress
echo 0 > /tmp/compilation_motion_in_progress
echo "*****************************************************************************************************"
echo "*                                   Installation des dépendance                                     *"
echo "*****************************************************************************************************"
sudo apt-get install -y autoconf
sudo apt-get install -y automake
sudo apt-get install -y pkgconf
sudo apt-get install -y libtool
sudo apt-get install -y libjpeg8-dev
sudo apt-get install -y libjpeg62-turbo-dev
sudo apt-get install -y build-essential
sudo apt-get install -y libzip-dev
echo "*****************************************************************************************************"
echo "*                                          Installation de FFMPEG                                   *"
echo "*****************************************************************************************************"
sudo apt-get install -y libavformat-dev
sudo apt-get install -f
sudo apt-get install -y libavcodec-dev
sudo apt-get install -f
sudo apt-get install -y libavutil-dev
sudo apt-get install -f
sudo apt-get install -y libswscale-dev
sudo apt-get install -f
sudo apt-get install -y libavdevice-dev
sudo apt-get install -f
if [ -d "/usr/local/src/ffmpeg" ]; then 
  sudo rm -R /usr/local/src/ffmpeg
fi
cd /usr/local/src/
git clone https://github.com/FFmpeg/FFmpeg.git ffmpeg
cd ffmpeg
./configure --prefix=/home/odroid/git/ffmpeg/out
make -j3
sudo make install
cd ../motion/
PKG_CONFIG_PATH=/usr/local/src/ffmpeg/out/lib/pkgconfig cmake .
make
echo 50 > /tmp/compilation_motion_in_progress
echo "*****************************************************************************************************"
echo "*                                          Compilation de motion:                                   *"
echo "*****************************************************************************************************"
if [ -d "/usr/local/src/motion" ]; then 
  sudo rm -R /usr/local/src/motion
fi
cd /usr/local/src/
git clone https://github.com/Motion-Project/motion.git
cd motion
sudo autoreconf -fiv
./configure
make
sudo make install
echo 95 > /tmp/compilation_motion_in_progress
sudo usermod -a -G motion www-data
echo 100 > /tmp/compilation_motion_in_progress
echo "*****************************************************************************************************"
echo "*                                Installation de motion terminé                                     *"
echo "*****************************************************************************************************"
rm /tmp/compilation_motion_in_progress
