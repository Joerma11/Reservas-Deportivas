# Plataforma de Reservas Deportivas y Gestión de Turnos

Sistema web integral desarrollado para la reserva en línea de canchas deportivas y la administración automatizada de complejos. La plataforma cuenta con una clara separación de responsabilidades entre el área pública orientada a clientes (con selección guiada por deporte, calendario semáforo interactivo y generación de entradas QR) y un panel de control privado protegido para administradores y encargados del recinto.

---

## 🚀 Características Clave

* ⚽ **Flujo de Reserva Público en 3 Pasos:** Proceso intuitivo (*Wizard*) que permite al cliente seleccionar la disciplina deportiva (Tenis, Pádel, Fútbol, Básquet), elegir la cancha correspondiente y consultar la disponibilidad en tiempo real.
* 🚦 **Calendario Dinámico con Semáforo:** Disponibilidad mensual categorizada por colores (Verde: Alta disponibilidad, Amarillo: Pocos cupos, Rojo: Agotado, Gris: Cerrado o pasado).
* 🔒 **Validación Matemática Anti-Traslapes:** Algoritmo de comprobación de solapamientos `(startA < endB) AND (endA > startB)` ejecutado en tiempo real con soporte para bloques flexibles (120 min, 90 min, 60 min) según la disciplina.
* 🎫 **Entradas y Escáner QR:** Generación automática de código QR único por reserva solicitada y módulo de recepción con buscador/escáner para validación y *check-in* inmediato.
* 🛠️ **Panel Administrativo Protegido:** Dashboard accesible exclusivamente vía URL e inicio de sesión protegido, con menú lateral (*Sidebar*) para la gestión operativa.
* 📋 **Gestión Avanzada de Canchas (Vista Modal Flotante):** Administración por cuadrículas agrupadas por deporte. Permite editar precios, horarios de atención por día de la semana y realizar deshabilitaciones suaves (*Soft Disable*) para preservar registros históricos.
* 📊 **Exportación Financiera a Excel:** Generación de reportes de recaudación y liquidaciones en formato `.xlsx` con filtrado por rango de fechas mediante Laravel Excel.

---

## 🛠️ Stack Tecnológico

* **Backend / Framework:** Laravel 11 / 12
* **Reactividad Full-Stack:** Livewire 3
* **Motor de Base de Datos:** MySQL 8.0
* **Estilos & Diseño UI:** Tailwind CSS (Diseño adaptable, componentes responsivos y modales flotantes)
* **Reportes & Exportaciones:** `maatwebsite/excel` (Laravel Excel / PhpSpreadsheet)
* **Entorno de Compilación:** Vite

---

## 📂 Arquitectura del Proyecto (Puntos Destacados)

Para garantizar un código modular, mantenible y escalable, se aplicaron las mejores prácticas de Laravel y Livewire:

* **Separación de Vistas y Middleware:** División completa entre las rutas de cara al cliente (`public.booking`) y el grupo administrativo protegido (`admin.*`) bajo el middleware `auth`.
* **Componentes Livewire Modulares:** Lógica aislada para la reserva paso a paso (`BookingCalendar`), gestión de cuadrículas de canchas (`AdminFieldsManager`), aprobación de turnos y validación QR (`AdminBookingApproval`) y exportación financiera (`SettlementReport`).
* **Inactivación Suave (*Soft Deletion*):** Las canchas no se eliminan físicamente de la base de datos para no corromper la integridad de reservas e informes pasados; en su lugar, se conmuta el campo `is_active`.
* **Plantilla Base unificada (`AdminLayout`):** Creación del componente `<x-admin-layout>` para envolver la administración en una interfaz homogénea con navegación por *Sidebar*.

---

## 🛠️ Requisitos Previos

Antes de comenzar, asegúrate de tener instalado lo siguiente en tu entorno local:

* **PHP** >= 8.2 (Con extensiones `pdo_mysql`, `mbstring`, `openssl`, `xml`, `gd`)
* **Composer** (Gestor de dependencias de PHP)
* **MySQL** >= 8.0
* **Node.js** (Versión LTS recomendada) y **NPM**

---

## 🔧 Instalación y Configuración Local

Si deseas clonar este proyecto y ejecutarlo en tu entorno local, sigue estos pasos:

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/tu-usuario/reservas-deportivas.git](https://github.com/tu-usuario/reservas-deportivas.git)
   cd reservas-deportivas
    ```
2. Instalar dependencias de PHP (Composer)
```bash
composer install
 ```

3.Instalar dependencias de JavaScript (NPM
```bash
npm install
 ```

4.Configurar el archivo de entorno
```bash
cp .env.example .env
php artisan key:generate
 ```

5.Configurar la base de datos MySQL en .env
```bash
Fragmento de código

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservas_deportivas
DB_USERNAME=root
DB_PASSWORD=
 ```

6.Ejecutar las migraciones y seeders de prueba
```bash
php artisan migrate --seed
 ```
7.Iniciar iniciar en dos consolas diferentes
```bash
npm run dev
php artisan serve
 ```


Una vez iniciado el servidor, visita http://127.0.0.1:8000 en tu navegador para ver el sitio en funcionamiento.

🔑 Acceso Administrativo: http://127.0.0.1:8000/login

Usuario Admin: cliente@test.com

Contraseña: 12345678
