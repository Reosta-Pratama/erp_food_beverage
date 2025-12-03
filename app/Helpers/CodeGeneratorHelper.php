<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CodeGeneratorHelper
{
    /**
     * Generate unique secure code with format: PREFIX-RANDOMSTRING
     * More secure and harder to guess for URL parameters
     * 
     * @param string $table - Table name
     * @param string $column - Column name (e.g., 'bom_code', 'recipe_code')
     * @param string $prefix - Code prefix (e.g., 'BOM', 'RCP')
     * @return string
     */
    public static function generate(string $table, string $column, string $prefix): string
    {
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            // Generate random string (alphanumeric, case-sensitive)
            // Format: PREFIX-XXXXXXXXXX (total 15 chars if prefix is 3 chars)
            $randomLength = 15 - strlen($prefix) - 1; // -1 for dash separator
            
            // Using cryptographically secure random string
            $randomString = strtoupper(substr(
                str_replace(['/', '+', '='], '', base64_encode(random_bytes(12))),
                0,
                $randomLength
            ));
            
            $code = $prefix . '-' . $randomString;
            
            // Check if code already exists
            $exists = DB::table($table)
                ->where($column, $code)
                ->exists();
            
            if (!$exists) {
                return $code;
            }

            $attempt++;
            
        } while ($attempt < $maxAttempts);
        
        throw new \Exception("Failed to generate unique code after {$maxAttempts} attempts");
    }

    /**
     * Alternative: Generate with timestamp + random for better uniqueness
     * Format: PREFIX-TTTTRRRRRR (T=timestamp, R=random)
     */
    public static function generateWithTimestamp(string $table, string $column, string $prefix): string
    {
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            // Get compact timestamp (base36)
            $timestamp = base_convert(time(), 10, 36);
            
            // Calculate remaining length
            $remainingLength = 15 - strlen($prefix) - strlen($timestamp) - 1;
            
            // Generate random string for remaining space
            $random = strtoupper(substr(
                str_replace(['/', '+', '='], '', base64_encode(random_bytes(8))),
                0,
                $remainingLength
            ));
            
            $code = $prefix . '-' . $timestamp . $random;
            
            // Check if code already exists
            $exists = DB::table($table)
                ->where($column, $code)
                ->exists();
            
            if (!$exists) {
                return $code;
            }
            
            $attempt++;
            usleep(100000); // 100ms delay
            
        } while ($attempt < $maxAttempts);
        
        throw new \Exception("Failed to generate unique code after {$maxAttempts} attempts");
    }

    /**
     * Alternative: Using UUID-like format (most secure)
     * Format: PREFIX-XXYYZZZZ (shorter UUID)
     */
    public static function generateUUIDStyle(string $table, string $column, string $prefix): string
    {
        $maxAttempts = 10;
        $attempt = 0;
        
        do {
            // Generate UUID and take parts
            $uuid = Str::uuid()->toString();
            $uuidParts = explode('-', $uuid);
            
            // Take first segments to fit in 15 chars
            $remainingLength = 15 - strlen($prefix) - 1;
            $shortUuid = strtoupper(substr(str_replace('-', '', $uuid), 0, $remainingLength));
            
            $code = $prefix . '-' . $shortUuid;
            
            // Check if code already exists
            $exists = DB::table($table)
                ->where($column, $code)
                ->exists();
            
            if (!$exists) {
                return $code;
            }
            
            $attempt++;
            
        } while ($attempt < $maxAttempts);
        
        throw new \Exception("Failed to generate unique code after {$maxAttempts} attempts");
    }

    /**
     * Generate Unit Of Measure Code
     */
    public static function generateUOMCode(): string
    {
        return self::generate('units_of_measure', 'uom_code', 'UOM');
    }

    /**
     * Generate Tax Rate Code
     */
    public static function generateTaxRateCode(): string
    {
        return self::generate('tax_rates', 'tax_code', 'TXR');
    }

    /**
     * Generate BOM Code
     */
    public static function generateBOMCode(): string
    {
        return self::generate('bill_of_materials', 'bom_code', 'BOM');
    }

    /**
     * Generate Recipe Code
     */
    public static function generateRecipeCode(): string
    {
        return self::generate('recipes', 'recipe_code', 'RCP');
    }

    /**
     * Generate Product Code
     */
    public static function generateProductCode(): string
    {
        return self::generate('products', 'product_code', 'PRD');
    }

    /**
     * Generate Lot Code
     */
    public static function generateLotCode(): string
    {
        return self::generate('lots', 'lot_code', 'LOT');
    }
    
    /**
     * Generate Batch Code
     */
    public static function generateBatchCode(): string
    {
        return self::generate('batches', 'batch_code', 'BCH');
    }
    
    /**
     * Generate Work Order Code
     */
    public static function generateWorkOrderCode(): string
    {
        return self::generate('work_orders', 'work_order_code', 'WO');
    }
    
    /**
     * Generate Purchase Order Code
     */
    public static function generatePOCode(): string
    {
        return self::generate('purchase_orders', 'po_code', 'PO');
    }
    
    /**
     * Generate Sales Order Code
     */
    public static function generateSOCode(): string
    {
        return self::generate('sales_orders', 'so_code', 'SO');
    }
    
    /**
     * Generate Delivery Code
     */
    public static function generateDeliveryCode(): string
    {
        return self::generate('deliveries', 'delivery_code', 'DEL');
    }
    
    /**
     * Generate QC Code
     */
    public static function generateQCCode(): string
    {
        return self::generate('quality_control', 'qc_code', 'QC');
    }
    
    /**
     * Generate Stock Movement Code
     */
    public static function generateMovementCode(): string
    {
        return self::generate('stock_movements', 'movement_code', 'MOV');
    }
    
    /**
     * Generate Supplier Code
     */
    public static function generateSupplierCode(): string
    {
        return self::generate('suppliers', 'supplier_code', 'SUP');
    }
    
    /**
     * Generate Customer Code
     */
    public static function generateCustomerCode(): string
    {
        return self::generate('customers', 'customer_code', 'CUS');
    }
    
    /**
     * Generate Warehouse Code
     */
    public static function generateWarehouseCode(): string
    {
        return self::generate('warehouses', 'warehouse_code', 'WH');
    }
    
    /**
     * Generate Machine Code
     */
    public static function generateMachineCode(): string
    {
        return self::generate('machines', 'machine_code', 'MCH');
    }
    
    /**
     * Generate Maintenance Ticket Code
     */
    public static function generateTicketCode(): string
    {
        return self::generate('maintenance_requests', 'ticket_code', 'TKT');
    }
    
    /**
     * Generate NCR Code
     */
    public static function generateNCRCode(): string
    {
        return self::generate('non_conformance_reports', 'ncr_code', 'NCR');
    }
    
    /**
     * Generate CAPA Code
     */
    public static function generateCAPACode(): string
    {
        return self::generate('capa', 'capa_code', 'CAPA');
    }
    
    /**
     * Generate Production Plan Code
     */
    public static function generatePlanCode(): string
    {
        return self::generate('production_planning', 'plan_code', 'PLAN');
    }
    
    /**
     * Generate Purchase Receipt Code
     */
    public static function generateReceiptCode(): string
    {
        return self::generate('purchase_receipts', 'receipt_code', 'RCP');
    }
    
    /**
     * Generate Purchase Return Code
     */
    public static function generatePRCode(): string
    {
        return self::generate('purchase_returns', 'pr_code', 'PRN');
    }
    
    /**
     * Generate Sales Return Code
     */
    public static function generateSRCode(): string
    {
        return self::generate('sales_returns', 'sr_code', 'SRN');
    }
    
    /**
     * Generate Payment Code
     */
    public static function generatePaymentCode(): string
    {
        return self::generate('payments', 'payment_code', 'PAY');
    }
    
    /**
     * Generate AP Code
     */
    public static function generateAPCode(): string
    {
        return self::generate('accounts_payable', 'ap_code', 'AP');
    }
    
    /**
     * Generate AR Code
     */
    public static function generateARCode(): string
    {
        return self::generate('accounts_receivable', 'ar_code', 'AR');
    }
    
    /**
     * Generate Journal Code
     */
    public static function generateJournalCode(): string
    {
        return self::generate('journal_entries', 'journal_code', 'JE');
    }
    
    /**
     * Generate Account Code
     */
    public static function generateAccountCode(): string
    {
        return self::generate('chart_of_accounts', 'account_code', 'ACC');
    }
    
    /**
     * Generate Cost Center Code
     */
    public static function generateCostCenterCode(): string
    {
        return self::generate('cost_centers', 'cost_center_code', 'CC');
    }
    
    /**
     * Generate Vehicle Code
     */
    public static function generateVehicleCode(): string
    {
        return self::generate('vehicles', 'vehicle_code', 'VHC');
    }
    
    /**
     * Generate Route Code
     */
    public static function generateRouteCode(): string
    {
        return self::generate('delivery_routes', 'route_code', 'RTE');
    }
    
    /**
     * Generate Spare Part Code
     */
    public static function generateSparePartCode(): string
    {
        return self::generate('spare_parts', 'part_code', 'SPR');
    }
    
    /**
     * Validate if code already exists
     * 
     * @param string $table
     * @param string $column
     * @param string $code
     * @return bool
     */
    public static function exists(string $table, string $column, string $code): bool
    {
        return DB::table($table)->where($column, $code)->exists();
    }

    /**
     * Generate unique code with retry mechanism
     * Useful for high-concurrency scenarios
     * 
     * @param string $table
     * @param string $column
     * @param string $prefix
     * @param int $maxRetries
     * @return string
     * @throws \Exception
     */
    public static function generateWithRetry(
        string $table, 
        string $column, 
        string $prefix, 
        int $maxRetries = 5
    ): string {
        $attempts = 0;
        
        do {
            $code = self::generate($table, $column, $prefix);
            
            if (!self::exists($table, $column, $code)) {
                return $code;
            }
            
            $attempts++;
            
            // Add small delay to prevent race conditions
            usleep(100000); // 100ms
            
        } while ($attempts < $maxRetries);
        
        throw new \Exception("Failed to generate unique code after {$maxRetries} attempts");
    }
}