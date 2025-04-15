# Medical Appointment Management System

A modern web application built with Laravel and Vue.js for managing medical appointments, doctors, and patients.

## 🚀 Features

- 🔐 Authentication and Role-based Access Control
- 👥 User Management (Admin, Doctors, Patients)
- 📅 Appointment Scheduling and Management
- 🌓 Light/Dark Mode Support
- 📱 Responsive Design
- ⚡ Server-Side Rendering (SSR)

## 🛠️ Tech Stack

- **Backend:** Laravel 12.x
- **Frontend:** Vue 3 + Inertia.js
- **Styling:** TailwindCSS + shadcn/vue
- **Database:** MySQL/PostgreSQL
- **Build Tool:** Vite
- **Type Safety:** TypeScript
- **Icons:** Lucide Icons

## 📋 Prerequisites

- PHP >= 8.2
- Node.js >= 22.x
- Composer
- MySQL/PostgreSQL

## 🔧 Installation

1. Clone the repository
```bash
git clone hhttps://github.com/felpabustan/patient-appointment-system.git
cd your-repo-name
```

2. Install PHP dependencies
```bash
composer install
```

3. Install Node.js dependencies
```bash
npm install
```

4. Copy environment file and configure your environment variables
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate
```

6. Run database migrations
```bash
php artisan migrate
```

7. Generate Ziggy route files
```bash
php artisan ziggy:generate
```

## 💻 Development

Start the development server:

```bash
# Run Laravel backend
php artisan serve

# Run Vite development server
npm run dev

# or Run using this command
composer run dev
```

For SSR development:
```bash
npm run dev:ssr
```

## 🧪 Testing

```bash
# Run PHP tests
./vendor/bin/phpunit

# Run frontend tests (if configured)
npm run test
```

## 🎨 Code Style

This project uses Laravel Pint for PHP code styling and Prettier for frontend code formatting.

```bash
# Format PHP code
./vendor/bin/pint

# Format frontend code
npm run format

# Check frontend code formatting
npm run format:check

# Lint frontend code
npm run lint
```

## 🏗️ Building for Production

```bash
# Build frontend assets
npm run build

# Build with SSR
npm run build:ssr
```

## 📁 Project Structure

```
├── app/                  # PHP application files
├── resources/
│   ├── js/              # Vue components and frontend logic
│   │   ├── components/  # Reusable Vue components
│   │   ├── layouts/     # Layout components
│   │   ├── pages/       # Inertia page components
│   │   └── types/       # TypeScript type definitions
│   └── views/           # Blade templates
├── routes/              # Laravel route definitions
├── database/            # Database migrations and seeders
└── tests/               # Test files
```

## 🔒 Security

If you discover any security-related issues, please email [your-email] instead of using the issue tracker.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 Contributing

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## ✨ Credits

- Fel Pabustan

## 📝 Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.
