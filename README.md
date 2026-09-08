# Daisy Store

A full-stack e-commerce web application built with Laravel. Daisy Store provides a complete shopping experience featuring a custom, responsive frontend and a robust backend for managing products, user orders, and real-time shipping cost calculations.

## Tech Stack
* **Backend:** Laravel (PHP), MySQL
* **Frontend:** Blade Templates, Tailwind CSS, Alpine.js, Vite
* **API Integration:** Kommerce / RajaOngkir V2 (Real-time shipping calculation)

## Core Features
* **Role-Based Access:** Dedicated dashboards for Admins (Product & Inventory Management) and Users (Order History & Profiles).
* **Seamless Checkout:** Integrated shopping cart with live domestic shipping cost calculation filtering specific services (e.g., JNE, SiCepat, J&T).
* **Order Tracking:** Dynamic, tab-based order management system filtering statuses from "Unpaid" to "Completed".
* **Responsive Design:** A custom UI optimized for both desktop and mobile devices.

## Prerequisites
* PHP >= 8.1
* Composer
* Node.js & NPM
* MySQL

## Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/yourusername/daisy-web.git](https://github.com/yourusername/daisy-web.git)
   cd daisy-web
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup:**
   Copy the example environment file and generate your application key.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration:**
   Open the `.env` file and update your database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). Then, run the migrations:
   ```bash
   php artisan migrate
   ```

5. **API configuration:**
   To enable shipping calculations, ensure your Kommerce RajaOngkir API key is active. Insert your valid API key into the environment variables or directly within `App\Http\Controllers\RajaOngkirController` depending on your setup.

6. **Run the application:**
   Start the Vite development server and the Laravel local server simultaneously.
   ```bash
   npm run dev
   php artisan serve
   ```
   The application will be accessible at `http://127.0.0.1:8000`.
