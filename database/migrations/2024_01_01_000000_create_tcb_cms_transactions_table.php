<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('tcb-cms.logging.table', 'tcb_cms_transactions'), function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('reference')->nullable()->index();
            $table->string('status');
            $table->json('request')->nullable();
            $table->json('response')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('tcb-cms.logging.table', 'tcb_cms_transactions'));
    }
};
