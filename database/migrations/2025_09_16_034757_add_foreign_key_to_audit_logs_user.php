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
        Schema::table('audit_logs', function (Blueprint $table) {
            // Add user_id column if it doesn't exist
            if (!Schema::hasColumn('audit_logs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            // Check if foreign key already exists before adding it
            if (!$this->foreignKeyExists('audit_logs', 'audit_logs_user_id_foreign')) {
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
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};