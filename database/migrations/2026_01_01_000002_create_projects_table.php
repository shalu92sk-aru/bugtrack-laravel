<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{Schema::create('projects',function(Blueprint $t){$t->id();$t->string('name');$t->string('key',12)->unique();$t->text('description')->nullable();$t->foreignId('created_by')->constrained('users')->restrictOnDelete();$t->boolean('is_active')->default(true)->index();$t->timestamps();});}public function down():void{Schema::dropIfExists('projects');}};
