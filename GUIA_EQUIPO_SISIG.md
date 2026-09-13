# 🚀 GUÍA DE INICIO RÁPIDO PARA EL EQUIPO — SISIG

Documentación oficial para que los integrantes del equipo (**Keiner**, **Kevin** y **Manuel**) puedan clonar el repositorio, configurar su entorno de desarrollo local y sincronizarse con los avances de la rama `cristian`.

---

## ⚙️ Requisitos previos en el computador
Antes de comenzar, asegúrate de tener instalado e iniciado:
- **Git**: [https://git-scm.com/](https://git-scm.com/)
- **Laragon**: con servicios de **Apache** y **MySQL** encendidos.
- **PHP**: versión 8.1 o superior.
- **Composer**: [https://getcomposer.org/](https://getcomposer.org/)
- **Node.js**: versión 18 o superior.

---

## 🔹 PARTE 1: Instalación desde Cero (Solo se hace la primera vez)

### 1. Clonar el repositorio
Abre una terminal (**PowerShell** o **Git Bash**) en la carpeta donde guardas tus proyectos (por ejemplo `C:\laragon\www` o `Documents`) y ejecuta:

```bash
git clone https://github.com/cristiancamiloramireztorres89-sys/SISIG.git
```

### 2. Entrar a la carpeta del proyecto
```bash
cd SISIG
```
*(Si la carpeta clonada se llama `erpsenaempresacefa`, entra con: `cd erpsenaempresacefa`)*

---

### 3. Instalar dependencias de PHP y JavaScript
Ejecuta los siguientes comandos para descargar las librerías del proyecto:

```bash
composer install
```

```bash
npm install
```

---

### 4. Configurar el archivo de entorno (`.env`)
Crea una copia del archivo de configuración base:

```bash
copy .env.example .env
```
*(En Linux/Mac o Git Bash se usa `cp .env.example .env`)*

Abre el archivo `.env` en VS Code y asegúrate de tener configurada la base de datos `sisig`:

```env
APP_NAME=SENA-Empresa
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sisig
DB_USERNAME=root
DB_PASSWORD=
```

---

### 5. Generar la clave de la aplicación
```bash
php artisan key:generate
```

---

### 6. Ubicarse en su propia rama de trabajo
Cada integrante del equipo debe trabajar en su rama personal asignada:

- **Si eres Keiner:**
  ```bash
  git checkout keiner
  ```
- **Si eres Kevin:**
  ```bash
  git checkout kevin
  ```
- **Si eres Manuel:**
  ```bash
  git checkout manuel
  ```

---

### 7. Traer el trabajo de Cristian a su rama
Para fusionar las 9 migraciones de base de datos, vistas de bienvenida y componentes creados por Cristian:

```bash
git fetch origin
git merge origin/cristian
```

---

### 8. Crear la base de datos y correr las migraciones
1. En Laragon, abre **HeidiSQL** o **phpMyAdmin**.
2. Crea una nueva base de datos llamada `sisig` con cotejamiento `utf8mb4_unicode_ci`.
3. En la terminal de tu proyecto ejecuta las migraciones de SISIG:

```bash
php artisan migrate --path=Modules/SISIG/Database/Migrations
```

*(Esto creará de forma exitosa las 9 tablas del modelo entidad-relación de SISIG).*

---

### 9. Registrar SISIG en el Portal ERP (Seeder)
Para que aparezca la tarjeta oficial de SISIG dentro de **Procesos de Apoyo** en el menú principal del ERP:

```bash
php artisan db:seed --class=Modules\SICA\Database\Seeders\BloqueAppSeeder
```

---

### 10. Iniciar el servidor local
```bash
php artisan serve
```

Ahora abre tu navegador en:
- 🌐 **Portal ERP SENA Empresa**: [http://127.0.0.1:8000](http://127.0.0.1:8000) *(Verás los bloques Estratégicos, Misionales y de Apoyo con SISIG).*
- 🌐 **Módulo SISIG (Welcome)**: [http://127.0.0.1:8000/sisig](http://127.0.0.1:8000/sisig) *(Página de inducción con ejes SIG, submódulos y preguntas frecuentes).*

---

## 🔹 PARTE 2: Flujo Diario de Trabajo (Actualizaciones)

### ¿Cómo actualizarte cuando Cristian suba nuevos cambios?
Cada vez que Cristian anuncie un nuevo commit/push, solo debes ejecutar estos 3 comandos en tu terminal:

```bash
# 1. Consultar novedades en GitHub
git fetch origin

# 2. Fusionar los cambios de Cristian en tu rama
git merge origin/cristian

# 3. Si se crearon nuevas migraciones, ejecutarlas
php artisan migrate --path=Modules/SISIG/Database/Migrations
```

---

### ¿Cómo subir tu propio avance a GitHub?
Cuando termines de programar una funcionalidad en tu rama:

```bash
# 1. Preparar archivos modificados
git add .

# 2. Guardar commit con mensaje descriptivo en español
git commit -m "Descripcion clara de lo que se desarrollo"

# 3. Subir a tu rama remota
git push origin <nombre-de-tu-rama>
```
*(Ejemplo: `git push origin keiner`)*
