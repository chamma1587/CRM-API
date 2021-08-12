<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid', 45)->unique();       
            $table->string('first_name', 30)->index('first_name');
            $table->string('last_name', 30)->index('last_name');          
            $table->string('email', 100)->unique(); 
            $table->json('phone_numbers')->nullable(); 
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();           
            $table->nullableTimestamps();       
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('first_name');                    
            $table->dropIndex('last_name');          
        });

        Schema::dropIfExists('customers');
    }
}
