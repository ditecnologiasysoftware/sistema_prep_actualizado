# Instalación

1. Copiar `.env.example` como `.env`.
2. Configurar el ambiente activo en `APP_ENV` y completar las credenciales correspondientes.
3. Configurar `GOOGLE_MAPS_API_KEY` y, si se utiliza, las llaves de reCAPTCHA.
4. Importar `BASE DE DATOS/admin_prep_tablas.sql`.
5. Publicar la raíz del proyecto en PHP 8.1 o superior con MySQL/MariaDB y las extensiones `mysqli` y `mbstring`.

Las credenciales no se almacenan en Git. Los recursos públicos se sirven exclusivamente desde `assets/`.
