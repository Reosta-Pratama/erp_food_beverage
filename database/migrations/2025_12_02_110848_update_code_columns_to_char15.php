<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all xxx_code columns from CHAR(10) to CHAR(15)
        // to accommodate CodeGeneratorHelper format: PREFIX-RANDOMSTRING
        
        DB::statement('ALTER TABLE bill_of_materials        MODIFY bom_code         CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE recipes                  MODIFY recipe_code      CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE products                 MODIFY product_code     CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE lots                     MODIFY lot_code         CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE batches                  MODIFY batch_code       CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE work_orders              MODIFY work_order_code  CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE purchase_orders          MODIFY po_code          CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE sales_orders             MODIFY so_code          CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE deliveries               MODIFY delivery_code    CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE quality_control          MODIFY qc_code          CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE stock_movements          MODIFY movement_code    CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE suppliers                MODIFY supplier_code    CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE customers                MODIFY customer_code    CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE warehouses               MODIFY warehouse_code   CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE machines                 MODIFY machine_code     CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE maintenance_requests     MODIFY ticket_code      CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE non_conformance_reports  MODIFY ncr_code         CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE capa                     MODIFY capa_code        CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE production_planning      MODIFY plan_code        CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE purchase_receipts        MODIFY receipt_code     CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE purchase_returns         MODIFY pr_code          CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE sales_returns            MODIFY sr_code          CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE payments                 MODIFY payment_code     CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE accounts_payable         MODIFY ap_code          CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE accounts_receivable      MODIFY ar_code          CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE journal_entries          MODIFY journal_code     CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE chart_of_accounts        MODIFY account_code     CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE cost_centers             MODIFY cost_center_code CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE vehicles                 MODIFY vehicle_code     CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE delivery_routes          MODIFY route_code       CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE spare_parts              MODIFY part_code        CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE tax_rates                MODIFY tax_code         CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE departments              MODIFY department_code  CHAR(15) NOT NULL');
        DB::statement('ALTER TABLE positions                MODIFY position_code    CHAR(15) NOT NULL');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback to CHAR(10)
        
        DB::statement('ALTER TABLE bill_of_materials MODIFY bom_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE recipes MODIFY recipe_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE products MODIFY product_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE lots MODIFY lot_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE batches MODIFY batch_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE work_orders MODIFY work_order_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE purchase_orders MODIFY po_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE sales_orders MODIFY so_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE deliveries MODIFY delivery_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE quality_control MODIFY qc_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE stock_movements MODIFY movement_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE suppliers MODIFY supplier_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE customers MODIFY customer_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE warehouses MODIFY warehouse_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE machines MODIFY machine_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE maintenance_requests MODIFY ticket_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE non_conformance_reports MODIFY ncr_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE capa MODIFY capa_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE production_planning MODIFY plan_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE purchase_receipts MODIFY receipt_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE purchase_returns MODIFY pr_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE sales_returns MODIFY sr_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE payments MODIFY payment_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE accounts_payable MODIFY ap_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE accounts_receivable MODIFY ar_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE journal_entries MODIFY journal_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE chart_of_accounts MODIFY account_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE cost_centers MODIFY cost_center_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE vehicles MODIFY vehicle_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE delivery_routes MODIFY route_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE spare_parts MODIFY part_code CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE tax_rates                MODIFY tax_code         CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE departments              MODIFY department_code  CHAR(10) NOT NULL');
        DB::statement('ALTER TABLE positions                MODIFY position_code    CHAR(10) NOT NULL');
    }
};