# moodleSIL

## Carga de archivos de usuarios

Al registrar un estudiante es posible subir una imagen o archivo PDF. Los archivos se guardan en la carpeta `uploads/` dentro de un subdirectorio con el ID del usuario. En la base de datos solo se registra la ruta relativa del archivo.

En la página de lista de estudiantes hay un botón para ver el archivo subido. Al abrirlo se muestra un modal con una vista previa y un enlace para descargarlo.
