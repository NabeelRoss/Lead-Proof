# 🛡️ LeadProof
### Intelligent Data Hygiene & CRM Validation Platform

![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/Status-Portfolio_Ready-success?style=for-the-badge)

**LeadProof** is a SaaS-style web application designed to solve the "dirty data" problem in sales operations. It allows users to upload raw CSV files, automatically cleanses formatting errors, detects duplicates, and validates records against strict **Salesforce** and **HubSpot** data rules.

---

## ✨ Key Features

* **🚀 High-Performance CSV Parsing**: Efficiently processes large datasets using PHP Generators (`yield`) to minimize memory usage.
* **🧠 CRM Readiness Score**: Algorithms analyze every row to assign a 0-100 "health score" based on field completeness and validity.
* **🧹 Smart Auto-Cleaning**:
    * Normalizes names (e.g., `JOHN` → `John`).
    * Formats phone numbers to E.164 standards.
    * Fixes column header mismatches (e.g., `first_name` → `First Name`).
* **📄 PDF Audit Reports**: Generates professional PDF summaries using `FPDF` for stakeholders.
* **🔒 Secure Authentication**: Built-in session-based auth with password hashing.
* **⚡ Modern UI**: Fully responsive interface built with Tailwind CSS.

---

## 🛠️ Tech Stack

* **Backend**: Native PHP 8.2+ (Strict Types, OOP, MVC Architecture).
* **Frontend**: HTML5, Tailwind CSS (via CDN), JavaScript (Fetch API).
* **Database**: MySQL / MariaDB.
* **Dependencies**:
    * `fpdf/fpdf` (Report Generation)
    * `vlucas/phpdotenv` (Environment Variables)

---

## ⚙️ Installation & Setup

Follow these steps to run LeadProof locally.

### 1. Prerequisites
Ensure you have the following installed:
* PHP >= 8.0
* Composer
* MySQL (via XAMPP, MAMP, or Docker)

### 2. Clone the Repository
```bash
git clone [https://github.com/yourusername/leadproof.git](https://github.com/yourusername/leadproof.git)
cd leadproof
