# ERP Food & Beverage Manufacturing System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue.svg)](https://www.mysql.com)

> Sistem ERP (Enterprise Resource Planning) lengkap untuk industri manufaktur makanan dan minuman dengan fitur production planning, inventory management, quality control, dan compliance management (BPOM, Halal, HACCP).

---

## **Table of Contents**

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [User Roles](#-user-roles)

---

## **Features**

### **Core Modules**

#### **Human Resource Management (HRM)**
- Employee Directory & Management
- Attendance & Shift Management
- Leave Management & Approval Workflow
- Payroll Processing & Salary Calculation
- Performance Reviews & KPI Tracking
- Training Programs & Certifications
- Employee Self Service (ESS) Portal

#### **Inventory Management**
- Product Management (Raw Material, Semi-Finished, Finished Goods)
- Bill of Materials (BOM) & Recipe Management
- Lot & Batch Tracking (Full Traceability)
- Multi-Warehouse Management
- Stock Movement Tracking
- Expiry Date Management & Alerts
- Reorder Point & Automated Procurement

#### **Production Management**
- Production Planning (MRP - Material Requirement Planning)
- Work Order Management
- Batch Production Tracking
- Quality Control (QC) at Every Stage
- Production Cost Calculation
- Real-time Production Monitoring

#### **Business Management**
- Supplier & Vendor Management
- Purchase Orders & Receipts
- Purchase Returns
- Customer Management (CRM)
- Sales Orders & Quotations
- Delivery & Distribution Management
- Sales Returns & Refunds

#### **Finance Management**
- Chart of Accounts
- Journal Entries (General Ledger)
- Accounts Payable (AP)
- Accounts Receivable (AR)
- Payment Processing
- Product Costing & Margin Analysis
- Budgeting & Variance Analysis
- Profit & Loss Reports

#### **Communication & Announcements**
- Meeting Scheduling & Minutes
- Company-wide Announcements
- Broadcast Messages
- Notification System

#### **Maintenance Management**
- Machine & Equipment Registry
- Preventive Maintenance Scheduling
- Maintenance History & Logs
- Breakdown Tickets & Requests
- Downtime Tracking & Analysis
- Spare Parts Inventory

#### **Quality Assurance & Compliance**
- Sanitation & Hygiene Checklists
- Daily Audit Reports
- Certification Management (ISO, HACCP, BPOM, Halal)
- Non-Conformance Reports (NCR)
- Corrective & Preventive Actions (CAPA)
- Quality Parameter Tracking

#### **Logistics Management**
- Fleet Management
- Delivery Route Optimization
- Driver Management
- Delivery Confirmation with Proof of Delivery (POD)
- Vehicle Maintenance Tracking

#### **Reporting & Analytics**
- Production Batch Reports
- Stock & Material Reports
- Quality & Rejection Reports
- Financial Reports (P&L, Cash Flow, Aging)
- Employee Performance Reports
- Custom Report Builder
- Export to Excel/PDF

#### **System Settings**
- User Management & Role-Based Access Control
- Permissions Management (CRUD per module)
- Audit Logs (All system activities)
- Activity Logs (User actions)
- Document Format Templates
- Multi-Currency Support
- Tax Configuration
- Workflow & Approval Configuration
- Email & Notification Settings
- Company Profile Management

---

## **Tech Stack**

### **Backend**
- **Framework:** Laravel 12.x
- **Language:** PHP 8.3+
- **Database:** MySQL 8.0+
- **Authentication:** Laravel Sanctum (API Token)
- **Queue:** Redis/Database Queue
- **Cache:** Redis/File Cache

### **Frontend**
- **CSS Framework:** Bootstrap 5
- **Pre-processor:** SASS/SCSS
- **Build Tool:** Vite

### **Additional Tools**
- **PDF Generator:** DomPDF / Laravel Snappy
- **Excel Export:** Laravel Excel (Maatwebsite)
- **Barcode/QR:** SimpleSoftwareIO/simple-qrcode
- **Logging:** Laravel Log / Monolog
- **Testing:** Pest

---

## **User Roles**

| Role | Code | Access Level | Description |
|------|------|--------------|-------------|
| **Administrator** | `admin` | Full Access | Complete system access including settings & user management |
| **Operational Staff** | `operator` | Production & Inventory | Access to production, inventory, QC, and maintenance modules |
| **Finance & HR Staff** | `finance_hr` | Finance & HR | Access to finance, HR, and administrative modules |

### **Role Permissions Matrix**

| Module | Admin | Operator | Finance/HR |
|--------|-------|----------|------------|
| Dashboard | ✅ | ✅ | ✅ |
| HRM | ✅ | ❌ | ✅ |
| Inventory | ✅ | ✅ | ❌ |
| Production | ✅ | ✅ | ❌ |
| Business Mgmt | ✅ | 📝 Partial | 📝 Partial |
| Finance | ✅ | ❌ | ✅ |
| QA/QC | ✅ | ✅ | ❌ |
| Maintenance | ✅ | ✅ | ❌ |
| Logistics | ✅ | ✅ | ❌ |
| Reports | ✅ | 📝 Production | 📝 Finance |
| Settings | ✅ | ❌ | ❌ |
| User Management | ✅ | ❌ | ❌ |

---

**Built with ❤️ for Food & Beverage Manufacturing Industry**

*Last Updated: October 2025*