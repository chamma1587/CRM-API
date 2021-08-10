<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerPhoneNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_phone_numbers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid', 45)->unique();
            $table->unsignedBigInteger('customer_id');     
            $table->string('phone_number', 15)->index('phone_number');   
            $table->nullableTimestamps();           

            $table->foreign('customer_id')->references('id')->on('customers'); 
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_phone_numbers', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);    
           $table->dropIndex('phone_number');                        
        });

        Schema::dropIfExists('customer_phone_numbers');
    }
}
