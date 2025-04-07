<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.



# Trade Finance Guarantee Issuance System

A comprehensive web application for managing the lifecycle of financial guarantees, supporting different types of guarantees (Bank, Bid Bond, Insurance, and Surety) with both manual entry and bulk upload capabilities.

## Overview

The Trade Finance Guarantee Issuance System helps financial institutions streamline the management of trade finance guarantees through a secure, role-based web application. The system supports the complete lifecycle of guarantees from creation to issuance, with proper controls and validations at each stage.

## Features

- **User Authentication**: Secure login with role-based access control (admin and user roles)

  
- **Guarantee Management**:
  - Create and manage different guarantee types
  - Complete workflow management (draft, review, apply, issue, reject)
  - Automatic reference number generation
  - Comprehensive validation rules
- **Bulk Processing**:
  - Upload guarantees via CSV, JSON, or XML files
  - Sample files available for download
  - Detailed processing results and error reporting
- **File Management**:
  - Secure storage of uploaded files
  - File content preview and download
  - Processing status tracking
- **Admin Dashboard**:
  - Overview of guarantees by status
  - Pending review management
  - File processing queue

## Technology Stack

- **Backend**: Laravel PHP Framework
- **Frontend**: Blade templates with Bootstrap
- **Database**: MySQL
- **Deployment**: Containerized using Podman/Docker

## System Requirements

- PHP 8.2 or higher
- MySQL 8.0 or higher
- Podman or Docker with Compose
- Git

## Installation

### Using Podman/Docker (Recommended)

1. Clone the repository:
   ```
    git clone https://github.com/HP181/Trade-Finance-Guarantee-Issuance-System.git
    cd Trade-Finance-Guarantee-Issuance-System

2. Start the containers:
   ```
    podman-compose up -d

3. Build and run the containers with podman-compose
   ```
    podman-compose -f docker-compose.yml up -d

4. Set up the database:
   ```
    podman exec -it trade_finance_app php artisan migrate

5. Create admin and user accounts:
   ```
    podman exec -it trade_finance_app php artisan tinker --execute="
    \App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
    ]);
    \App\Models\User::create([
    'name' => 'Regular User',
    'email' => 'user@example.com',
    'password' => bcrypt('password'),
    'role' => 'user'
    ]);"

6. Access the application at http://localhost:8000

   
### Manual Installation

1. Clone the repository:
   ```
    git clone https://github.com/HP181/Trade-Finance-Guarantee-Issuance-System.git
    cd Trade-Finance-Guarantee-Issuance-System

2. Install dependencies:
   ```
    composer install
    npm install
    npm run build

3. Set up environment:
   ```
    cp .env.example .env
    php artisan key



4. Update database configuration in .env file


5. Run migrations:
   ```
    php artisan migrate

6. Create admin and user accounts:
   ```

   php artisan tinker
       User::create(['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin']);
       User::create(['name' => 'Regular User', 'email' => 'user@example.com', 'password' => bcrypt('password'), 'role' => 'user']);
   exit
   

7. Start the server:
   ```
    php artisan serve

8. Access the application at http://localhost:8000


## Usage

### User Guide

1. **Authentication**:
- Log in with provided credentials
- Users can only access their own guarantees
- Admins have full system access

2. **Creating Guarantees**:
- Navigate to "Guarantees" > "Create New"
- Fill out the required information
- Submit to create a draft guarantee

3. **Guarantee Workflow**:
- Users submit draft guarantees for review
- Admins review, apply, and issue guarantees
- Each stage has appropriate validations

4. **Bulk Upload**:
- Upload CSV, JSON, or XML files with guarantee data
- Download sample files for reference
- Process files to create multiple guarantees at once

5. **File Management**:
- View uploaded files and their processing status
- Preview and download file contents
- Process uploaded files (admin only)

### Admin Guide

1. **Dashboard**:
- View guarantees by status
- Access pending reviews
- Monitor file processing queue

2. **Guarantee Management**:
- Review submitted guarantees
- Apply or reject guarantees
- Issue applied guarantees

3. **File Processing**:
- Process uploaded files
- View processing results
- Manage bulk-created guarantees

## Deployment

### Container Management

- **Start containers**:
podman-compose up -d
Copy
- **Stop containers**:
podman-compose down
Copy
- **View logs**:
podman logs trade_finance_app
podman logs trade_finance_web
podman logs trade_finance_db
Copy
### Database Backup
podman exec trade_finance_db mysqldump -u root -pyour_mysql_password trade_finance_guarantee_issuance_system_production > backup_$(date +%Y%m%d).sql
Copy
## Development

### Project Structure

The application follows Laravel's standard structure with these key components:

- **Controllers**: App/Http/Controllers
- **Models**: App/Models
- **Repositories**: App/Repositories
- **Interfaces**: App/Interfaces
- **Views**: resources/views

### Testing

The system includes comprehensive tests for all core functionality:
php artisan test
Copy
## Troubleshooting

### Common Issues

1. **Database Connection Issues**:
   - Check database credentials in .env
   - Ensure database service is running
   - Verify network connectivity between containers

2. **File Upload Problems**:
   - Check file size (max 10MB)
   - Verify file format (CSV, JSON, or XML)
   - Ensure storage permissions are correct

3. **Permission Errors**:
   - Verify user role assignments
   - Check file and directory permissions
   - Ensure containers have proper volume mounts

## Contributing

1. Fork the repository
2. Create your feature branch: `git checkout -b feature/my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin feature/my-new-feature`
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Laravel Framework
- Bootstrap CSS Framework
- Podman/Docker Containerization
