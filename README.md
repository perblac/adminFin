<p align="center">
    <a href="https://bitbucket.org/learning-yii/administracionfincas/src/master/" target="_blank">
        <img src="./frontend/web/img/logo.jpg" height="100px">
    </a>
    <h1 align="center">adminFin</h1>
    <br>
</p>

***
## Getting started
¡Bienvenido a nuestra plataforma web adminFin!

Nuestra aplicación está diseñada para facilitar la comunicación entre la empresa de administración de fincas y sus clientes, que son principalmente comunidades de vecinos que interactúan a través de su presidente de comunidad. A través de nuestra plataforma, la empresa podrá enviar notificaciones a sus clientes de forma rápida y sencilla.

En nuestra sección estática "Sobre nosotros", encontrarás información detallada sobre nuestra empresa y nuestro equipo de trabajadores, para que puedas conocernos mejor.

¿Estás interesado en recibir un presupuesto personalizado para tu comunidad de vecinos? Utiliza nuestro formulario "Presupuesto" para proporcionarnos las características de tu comunidad y te daremos un presupuesto ajustado a tus necesidades. Además, desde el formulario podrás contactar con nosotros para más información.

Si necesitas ponerte en contacto con nosotros, puedes hacerlo a través de nuestro formulario de contacto, donde estaremos encantados de atender tus consultas y dudas.

Para acceder a la zona de usuarios, simplemente inicia sesión con tu cuenta. Distinguimos dos tipos de usuarios: administradores y clientes.

Los clientes pueden ver y editar los datos de su perfil, así como solicitar restablecer su contraseña en la sección "Perfil". Además, podrán ver, enviar y responder notificaciones a los administradores en la sección "Notificaciones".

Los administradores, por su parte, podrán ver, enviar, recibir y marcar como no leídas las notificaciones de los clientes, registrar nuevas cuentas para clientes, administrar los usuarios del sistema y controlar las notificaciones del sistema.

Nuestra plataforma web está diseñada para facilitar la comunicación y la gestión entre la empresa de administración de fincas y sus clientes. ¡Esperamos que disfrutes de todas las funcionalidades que ofrece nuestra aplicación!

## Instalación
#### Requisitos:
- [_Git_](https://git-scm.com/): Es necesario tener Git instalado en el servidor para poder clonar el repositorio de la aplicación desde un repositorio remoto.

#### Despliegue:

Para desplegar adminFin, se han de seguir los siguientes pasos:
- Clonar repositorio

`git clone https://perblac@bitbucket.org/learning-yii/administracionfincas.git`
- Entrar en el directorio

`cd administracionfincas`

- Opcionalmente, configurar las siguientes opciones:

> - Para configurar el nombre del servidor (Ej. `nombre-de-servidor.tld`, `subdominio.nombre-de-servidor.tld`)
>
> > En el archivo `frontend/adminfin-vhost.conf`:
> > 
> > - líneas 22 y 30, opción `ServerAlias nombre-de-servidor.tld`
> > - líneas 56 y 64, opción `ServerAlias subdominio.nombre-de-servidor.tld`
>
> > En el archivo `domain-db-mail.php`:
> > 
> > - línea 5, opción `'@siteFront' => 'https://nombre-de-servidor.tld'`,
> > - línea 6, opción `'@siteBack' => 'https://subdominio.nombre-de-servidor.tld'`,
> > - línea 7, opción `'@domain' => '.nombre-de-servidor.tld'`,
>
> - Para configurar el servicio de correo SMTP:
>
> > En el archivo `domain-db-mail.php`:
> > 
> > - línea 23, opción `'dsn' => 'smtp://user:pass@smtp.example.com:port'`
> > 
> > donde:
> > 
> > - `user` → usuario del smtp
> > - `pass` → contraseña del smtp
> > - `smtp.example.com` → dirección del smtp
> > - `port` → número de puerto del smtp
> > 
> > Editar el archivo `common/config/params.php` para configurar las direcciones de correo
> - Para cambiar las urls de las redes sociales:
>
> > Editar el archivo `common/config/social-media.php`
>
> ###### Nota:
> Si no se dispone de servidor propio en el que se pueda configurar un subdominio, hay que hacer dos entradas en el archivo `hosts` de la maquina apuntando la ip a `adminfin.test` y `users.adminfin.test`

- Hacer ejecutables los scripts necesarios

`chmod +x start.sh up.sh down.sh populate.sh`
- Ejecutar el script de inicio (si se quiere empezar con una base de datos vacía añadir `--new-db`)

`./start.sh`