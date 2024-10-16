<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\InvoiceProductRelationship;

class BugDemoController extends Controller
{
    public function demonstrateBug()
    {
        // Create test data
        $invoice = Invoice::create();
        $product1 = Product::create(['is_membership_product' => true]);
        $product2 = Product::create(['is_membership_product' => false]);
        InvoiceProductRelationship::create(['invoice_id' => $invoice->id, 'product_id' => $product1->id]);
        InvoiceProductRelationship::create(['invoice_id' => $invoice->id, 'product_id' => $product2->id]);

        // Demonstrate the bug

        // Method 1: Without using a closure on the relationship method
        // EXPECTED BEHAVIOR: This should include the 'is_membership_product' condition
        $queryWithoutClosure = $invoice->lineItems()->whereHas('product', function ($query) {
            $query->where('is_membership_product', true);
        });

        // Method 2: Using a closure on the relationship method
        // BUG: This does NOT include the 'is_membership_product' condition
        $queryWithClosure = $invoice->lineItems(function ($query) {
            $query->whereHas('product', function ($productQuery) {
                $productQuery->where('is_membership_product', true);
            });
        });

        // Output the SQL and bindings for both methods to demonstrate the difference
        return response()->json([
            'sql_without_closure' => $queryWithoutClosure->toSql(),
            'bindings_without_closure' => $queryWithoutClosure->getBindings(),
            'sql_with_closure' => $queryWithClosure->toSql(),
            'bindings_with_closure' => $queryWithClosure->getBindings(),
        ]);
    }
}
