<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('s_mailings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Untitled mailing');
            $table->string('domain')->default('default');
            $table->string('lang')->default('base');
            $table->string('status')->default('draft');
            $table->string('delivery_mode')->default('manual');
            $table->timestamp('scheduled_at')->nullable();
            $table->longText('document');
            $table->timestamps();

            $table->index('status');
            $table->index('domain');
            $table->index('lang');
            $table->index('delivery_mode');
            $table->index('scheduled_at');
            $table->index('updated_at');
        });

        Schema::create('s_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->default('default');
            $table->string('lang')->default('base');
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('status')->default('active');
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamp('blocked_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('domain');
            $table->index('lang');
            $table->unique(['domain', 'email']);
            $table->index('subscribed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('s_subscribers');
        Schema::dropIfExists('s_mailings');
    }
};
