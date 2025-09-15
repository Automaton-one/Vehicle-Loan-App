# Vehicle Loan App

A simple CRUD application for managing vehicle loan applications, built with:

- Laravel (PHP backend)
- Inertia.js + Vue 3 (SPA frontend)
- MySQL (database)
- Docker (local environment)

## Features

- Applicants can submit a loan application via SPA form.
- Consultants can list, filter, search, update status, and soft-delete applications.
- Clean, responsive UI with validation and inline errors.

## Requirements

- Docker
- Docker Compose

## Quick Start

1. Clone the repo:
   ```bash
   git clone https://github.com/yourname/vehicle-loan-app.git
   cd vehicle-loan-app

2. Run the setup script (copies .env, generates app key, migrates DB):
   ```bash
   .cicd/setup.sh

3. Start containers:
   ```bash
   docker-compose up --build
