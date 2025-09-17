<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Check if foreign keys already exist before adding them
            if (!$this->foreignKeyExists('stock_movements', 'stock_movements_product_id_foreign')) {
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
            }

            if (!$this->foreignKeyExists('stock_movements', 'stock_movements_user_id_foreign')) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            }
        });
    }

    private function foreignKeyExists($table, $constraintName)
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        
        $query = "SELECT CONSTRAINT_NAME 
                  FROM information_schema.KEY_COLUMN_USAGE 
                  WHERE TABLE_SCHEMA = ? 
                  AND TABLE_NAME = ? 
                  AND CONSTRAINT_NAME = ?";
        
        $result = $connection->select($query, [$database, $table, $constraintName]);
        return count($result) > 0;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);
        });
    }
};