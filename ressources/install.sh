#!/bin/bash
touch /tmp/compilation_motion_in_progress
echo 0 > /tmp/compilation_motion_in_progress
echo "*****************************************************************************************************"
echo "*                                Desinstallation des dépendance                                    *"
echo "*****************************************************************************************************"
sudo apt-get purge --auto-remove  -y motion
sudo apt-get purge --auto-remove  -y ffmpeg
sudo apt-get purge --auto-remove  -y x264
echo "*****************************************************************************************************"
echo "*                                   Installation des dépendance                                     *"
echo "*****************************************************************************************************"
sudo apt-get install -y autoconf automake libtool
sudo apt-get install -y pkg-config
sudo apt-get install -y libjpeg62-turbo-dev
sudo apt-get install -y zlib1g-dev
sudo apt-get install -y git
sudo apt-get install -y git-core
sudo apt-get install -y cmake
sudo apt-get install -y liblog4cplus-dev 
sudo apt-get install -y libcurl3-dev 
sudo apt-get install -y build-essential
sudo apt-get install -y libjasper-dev
sudo apt-get install -y libgtk2.0-dev
sudo apt-get install -y libavcodec-dev libavformat-dev libswscale-dev libv4l-dev
sudo apt-get install -y libzip-dev
sudo apt-get install -y libavutil-dev 
sudo apt-get install -y libavformat-dev 
sudo apt-get install -y libavcodec-dev 
sudo apt-get install -y libswscale-dev l
sudo apt-get install -y ibavdevice-dev
echo "*****************************************************************************************************"
echo "*                                          Installation de FFMPEG                                   *"
echo "*****************************************************************************************************"
sudo apt-get install -y ffmpeg
sudo apt-get install -y x264
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
