        Schema::create('$[table]', function (Blueprint $table) {
$[field.each(all)]
$[field.if(primary)]
            $table->increments('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(reference)]
            $table->unsignedInteger('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(date)]
            $table->date('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(time)]
            $table->time('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(datetime)]
            $table->dateTime('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(currency)]
            $table->decimal('$[field]', 19, 4)$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(float)]
            $table->float('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(double)]
            $table->double('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(bigint)]
            $table->bigInteger('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(integer)]
            $table->integer('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(blob)]
            $table->binary('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(text)]
            $table->text('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(boolean)]
            $table->boolean('$[field]')$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.else.if(enum)]
            $table->enum('$[field]', [$[field.each(option)]$[field.if(first)]$[field.else], $[field.end]'$[field.option]'$[field.end]])$[field.if(null)]->nullable()$[field.end]$[field.if(default)]->default($[field.default])$[field.end];
$[field.else.if(string)]
            $table->string('$[field]', $[field.length])$[field.if(null)]->nullable()$[field.end]$[field.if(info)]->default($[Field.info])$[field.end];
$[field.end]
$[field.end]
$[table.if(reference)]

$[table.end]
$[table.each(unique)]
$[unique.if(primary)]
$[unique.else]
            $table->unique([$[unique.each(all)]$[field.if(first)]$[field.else], $[field.end]'$[field]'$[unique.end]]);
$[unique.end]
$[table.end]
$[table.each(index)]
            $table->index([$[index.each(all)]$[field.if(first)]$[field.else], $[field.end]'$[field]'$[index.end]]);
$[table.end]
$[field.each(reference)]
            $table->foreign('$[field]')
                ->references('id')->on('$[reference]')
                ->onUpdate('cascade')
                ->onDelete('$[field.on.delete]');
$[field.end]
        });
