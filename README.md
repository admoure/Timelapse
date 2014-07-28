Software de vigilancia volcánica. 

INSTRUCCIONES

Descargar sistema operativo Raspbian http://www.raspberrypi.org/downloads/
Quemar en SD de 8Gb y hacer configuración inicial
Actualizar apt-get (apt-get update)
Ir hasta el directorio /home/pi (cd /home/pi)
Instalar los siguientes programas

- Servidor Apache (apt-get install apache2)
- PHP v.5 (apt-get install php5)
- Manejo de pines io (wget http://www.airspayce.com/mikem/bcm2835/bcm2835-1.36.tar.gz)
  - tar zxvf bcm2835-1.xx.tar.gz
  - cd bcm2835-1.xx
  - ./configure
  - make
  - make check
  - make install

Hacer -> git clone https://github.com/admoure/Timelapse.git && cd Timelapse

Ejecutar el instalador (./install.sh)
