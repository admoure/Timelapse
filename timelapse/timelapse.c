#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <bcm2835.h>

int counter = 600;
char name[64] = {"image"};
char dir_base_usb[64] = {"/media/CAM"};
char dir_base_pi[64] = ("/home/pi");
char dir_images[64] = {"/home/pi/temp/"};
char web[64] = {"/var/www/actual.jpg"};
char temp[32] = {"/home/pi/debug.txt"};
char orto[64] = {"/home/pi/timelapse/_orto_ocaso.sh"};

char ortocaso[8];
char ortoh[2],ortom[3],ocasoh[3],ocasom[3];
int minorto,minocaso;
int minactual;

char msg[256];
char impresion[64];
char directorio_mes[64];
char directorio_dia[64];


char hor[5];
char min[5];
char seg[5];

char anio[6];
char dia[5];
char mes[5];
char mes_l[5];
int hours,minutes,seconds,year,day,month;

int bytes;

FILE *pipe;
FILE *fich;

void take_image();
void settime(void);
void imprime(void);
void resize(void);
void borra(void);

#define PIN04 RPI_V2_GPIO_P1_07


//#define WEBCAM_ALWAYS_ON
//#define WEBCAM_PIN_ON
#define WEBCAM_TABLE_ON

int main ()
{
	if (!bcm2835_init())
		return 1;
	bcm2835_gpio_fsel(PIN04,BCM2835_GPIO_FSEL_INPT);

	#ifdef WEBCAM_PIN_ON

		if (bcm2835_gpio_lev(PIN04))
		{
			sprintf(msg,"cp %sblack.jpg %s",dir_base_pi,web);
			system(msg);
			return 1;
		}
	#endif

	#ifdef WEBCAM_TABLE_ON

		system(orto);
		pipe = popen(orto,"r");			//devuelve la hora
		fgets(ortocaso,8,pipe);
		pclose(pipe);
		ortoh[0] = ortocaso[0];
		ortoh[1] = '\0';
		ortom[0] = ortocaso[1];
		ortom[1] = ortocaso[2];
		ortom[2] = '\0';
		ocasoh[0] = ortocaso[3];
		ocasoh[1] = ortocaso[4];
		ocasoh[2] = '\0';
		ocasom[0] = ortocaso[5];
		ocasom[1] = ortocaso[6];
		ocasom[2] = '\0';
		printf("orto -> %c:%c%c\nocaso -> %c%c:%c%c\n",ortoh[0],ortom[0],ortom[1],ocasoh[0],ocasoh[1],ocasom[0],ocasom[1]);


	#endif



	settime();

	#ifdef WEBCAM_TABLE_ON
	if (minactual < minorto || minactual > minocaso)
	{
		printf("durmiendo\n");
		sprintf(msg,"echo \"min actuales:%d\nmin orto:%d\nmin ocaso:%d\n\" > /home/pi/timelapse/caquita.txt",minactual,minorto,minocaso);
		system(msg);
		return(1);
	}
	#endif

	take_image();
	imprime();
	resize();
//	borra();

	return 1;

}
void take_image()
{
	//se crea el directorio del anio
	sprintf(msg,"mkdir %s/%4d",dir_base_usb,year);
	system(msg);
	sprintf(msg,"mkdir %s/%4d/%s",dir_base_usb,year,mes_l);
	system(msg);
	sprintf(msg,"mkdir %s/%4d/%s/%.2d",dir_base_usb,year,mes_l,day);
	system(msg);

	sprintf(msg,"raspistill -awb horizon -hf -vf -n -q 25 -o %s/%.4d%.2d%.2d%.2d%.2d.jpg > %s",dir_base_pi,year,month,day,hours,minutes,temp);
	system(msg);


	//esto a partir de aqui es para reiniciar el rpi por si se atasca la cam
	fich = fopen(temp,"r+");
	if (fich == NULL)
	{
		printf("el fichero no existe\n");
	}
	bytes = 0;
	while (1)
	{
		fgetc(fich);
		if (!feof(fich))
		{
			bytes++;
		}
		else
		{
			break;
		}
	}
	fclose(fich);
	printf("numero de bytes en el fichero %s = %d\n",temp,bytes);

	if (bytes != 0)
	{
		sprintf(msg,"echo \"orden de reinicio a las %02d:%02d:%02d >> log.txt\"",hours,minutes,seconds);
		system(msg);
		sprintf(msg,"/sbin/shutdown -r now");
		system(msg);
	}

}
void settime()
{
	pipe = popen("date -u +%2H","r");			//devuelve la hora
	fgets(hor,3,pipe);
	pclose(pipe);
	pipe = popen("date -u +%2M","r");			//devuelve los minutos
	fgets(min,3,pipe);
	pclose(pipe);
	pipe = popen("date -u +%2S","r");			//devuelve los segundos
	fgets(seg,3,pipe);
	pclose(pipe);
	pipe = popen("date -u +%Y","r");			//devuelve el año
	fgets(anio,5,pipe);
	pclose(pipe);
	pipe = popen("date -u +%2m","r");			//devuelve el mes
	fgets(mes,3,pipe);
	pclose(pipe);
	pipe = popen("date -u +%2d","r");			//devuelve el dia
	fgets(dia,3,pipe);
	pclose(pipe);
	pipe = popen("date -u +%b","r");
	fgets(mes_l,4,pipe);
	pclose(pipe);
	mes_l[3] = '\0';
	//se convierten a entero
	hours = atoi(hor);
	minutes = atoi(min);
	seconds = atoi(seg);
	month = atoi(mes);
//	counter = hours*60 + minutes;
//	printf("counter = %d\n", counter);
	day = atoi(dia);
	year = atoi(anio);
	sprintf(impresion,"%.2d/%.2d/%.4d %.2d:%.2d.%.2d",day,month,year,hours,minutes,seconds);
	minorto = atoi(ortoh)*60 + atoi(ortom);
	minocaso = atoi(ocasoh)*60 + atoi(ocasom);
	minactual = atoi(hor)*60 + atoi(min);
	printf("orto horas %d minutos %d\nocaso horas %d minutos %d\n",atoi(ortoh),atoi(ortom),atoi(ocasoh),atoi(ocasom));
        printf("actual horas %d minutos %d\n",atoi(hor),atoi(min));
	printf("En minutos orto -> %d ocaso -> %d actual -> %d\n",minorto,minocaso,minactual);
	printf("%s\n",impresion);

}

void imprime()
{

//	sprintf(msg,"raspistill -awb horizon -hf -vf -n -q 25 -o %s/%.4d/%s/%d/%.4d%.2d%.2d%.2d%.2d.jpg > %s",dir_base_usb,year,mes_l,day,year,month,day,hours,minutes,temp);
	//aniade la hora a la imagen anterior y la guarda en temp con otro nombre dentro del directorio asociado
	sprintf(msg,"convert %s/%.4d%.2d%.2d%.2d%.2d.jpg -gravity southeast -pointsize 50 -stroke white -strokewidth 5 -annotate 0 '%s' %s/lapse%.4d%.2d%.2d%.2d%.2d.jpg",dir_base_pi,year,month,day,hours,minutes,impresion,dir_base_pi,year,month,day,hours,minutes);
	system(msg);
	//ahora aniade el logo y se queda en la carpeta temp las 3 fotos, la final es lapsexxxx.jpg
//	sprintf(msg,"composite -gravity southwest %slogo.png %slapseb%.4d.jpg %s%s/%s/lapse%.4d.jpg",dir_base_pi,dir_images,counter,dir_images,directorio_mes,directorio_dia,counter); 
//	system(msg);
	//ahora se copia al usb la imagen final
	sprintf(msg,"mv %s/lapse%.4d%.2d%.2d%.2d%.2d.jpg %s/%.4d/%s/%.2d/%.4d%.2d%.2d%.2d%.2d.jpg",dir_base_pi,year,month,day,hours,minutes,dir_base_usb,year,mes_l,day,year,month,day,hours,minutes);
	system(msg);
	//escribimos la imagen a enviar en un fichero para indicar la imagen que hay que enviar, se hace append siempre sobre el existente
//	sprintf(msg,"echo %s%s/%s/lapse%.4d.jpg >> %sftp_files.txt",dir_images,directorio_mes,directorio_dia,counter,dir_base_pi);
//	system(msg);

	//Si el fichero no existe se encontra en el mismo directorio pero en el usb.
	//NOS CARGAMOS LA ORIGINAL
	sprintf(msg,"rm %s/%.4d%.2d%.2d%.2d%.2d.jpg",dir_base_pi,year,month,day,hours,minutes);
	system(msg);
}

void resize()
{
	//reducimos la imagen final para mostrar en la web
	sprintf(msg,"convert %s/%.4d/%s/%.2d/%.4d%.2d%.2d%.2d%.2d.jpg -resize 640x480 %s",dir_base_usb,year,mes_l,day,year,month,day,hours,minutes,web);
	system(msg);

}

//borramos los ficheros intermedios del raspberry pi, el borrado de la imagen final se hace desde el script de envio por ftp.  Las imagenes siempre
//estan disponibles en el pendrive.
void borra()
{
	//se borran las originales tal cual salen de la camara y las intermedias
	sprintf(msg,"rm %s*.jpg",dir_images);
	system(msg);
}
